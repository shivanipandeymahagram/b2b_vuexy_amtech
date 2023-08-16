<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\User;
use App\Models\Report;
use Carbon\Carbon;
use App\Models\Api;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class RechargeController extends Controller
{
    protected $api;
    public function __construct()
    {
        $this->api = Api::where('code', 'recharge2')->first();
    }
    
    public function providersList(Request $post)
    {
        if($post->type == "lpggas"){
           $post['type']  = "lpg";
        }else if($post->type == "loanrepay"){
            $post['type']  ="emi" ;
        }else if($post->type  == "muncipal")
        {
            $post['type'] = "municipality" ;
        }else if($post->type == "lifeinsurance"){
             $post['type'] = "insurance" ;
        }
        
        $providers = Provider::where('type', $post->type)->where('status', "1")->orderBy('name')->get(['id', 'name']);
        return response()->json(['statuscode' => "TXN", 'message' => "Provider Fetched Successfully", 'data' => $providers]);
    }

    public function transaction(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
            'provider_id'      => 'required|numeric',
            'amount'      => 'required|numeric|min:10',
            'number' => 'required|numeric'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->first();
        if(!$user){
            $output['statuscode'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        if (!\Myhelper::can('recharge_service', $user->id)) {
            //return response()->json(['statuscode' => "ERR", "message" => "Service Not Allowed"]);
        }

        if($user->status != "active"){
            return response()->json(['statuscode' => "ERR", "message" => "Your account has been blocked."]);
        }

        $provider = Provider::where('id', $post->provider_id)->first();

        if(!$provider){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Not Found"]);
        }

        if($provider->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
        }

        if(!$provider->api || $provider->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Recharge Service Currently Down."]);
        }

        if($user->mainwallet < $post->amount){
            return response()->json(['statuscode' => "ERR", "message"=> 'Low Balance, Kindly recharge your wallet.']);
        }
        if ($this->pinCheck($post) == "fail") {
            return response()->json(['statuscode' => "ERR", "message" => "Transaction Pin is incorrect"]);
        }
         do {
            $post['txnid'] = $this->transcode().rand(11, 99).Carbon::now()->timestamp;
        }while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);
        switch ($provider->api->code) {
            case 'recharge1':
                $url = $provider->api->url."/pay?token=".$provider->api->username."&number=".$post->number."&operator=".$provider->recharge1."&amount=".$post->amount."&apitxnid=".$post->txnid;
                $header = [];
                $query = '';
                break;
            case 'recharge4':
                    $gpsdata       =  geoip($post->ip());
                    $latlong =  $gpsdata->lat.','.$gpsdata->lon;
                    
                    do {
                        $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                    } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);
                    $url = $provider->api->url."TransactionAPI?UserID=".$provider->api->username."&Token=".$provider->api->password."&Account=".$post->number."&SPKey=".$provider->recharge4."&Amount=".$post->amount."&APIRequestID=".$post->txnid."&Optional1=&Optional2=&Optional3=&Optional4=&CustomerNumber=8910992282&Pincode=743129&Format=1&GEOCode=".$latlong;
                    $query = json_encode([]);
                    $header = [];
                    $method = "GET";
                break;
              case 'recharge2':
                $url = $provider->api->url."recharge/dorecharge";
               // $url = 'https://paysprint.in/service-api/api/v1/service/recharge/recharge/dorecharge' ; 
                $parameter = [
                    "operator" => $provider->recharge3,
                    "canumber" => $post->number,
                    "amount"   => $post->amount,
                    "referenceid" => $post->txnid   
                ];

                $token = $this->getToken($post->user_id."RECH".Carbon::now()->timestamp);
                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".$token['token'],
                    "Authorisedkey: ".$provider->api->optional3
                );
               
                $query = json_encode($parameter);
                $method = "POST";
                break;
                
        }  

        $previousrecharge = Report::where('number', $post->number)->where('amount', $post->amount)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
        if($previousrecharge > 0){
            return response()->json(['statuscode' => "ERR", "message"=> 'Same Transaction allowed after 2 min.'], 400);
        }

        $post['profit'] = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
        $debit = User::where('id', $user->id)->decrement('mainwallet', $post->amount - $post->profit);
        if($debit){

            $insert = [
                'number' => $post->number,
                'mobile' => $user->mobile,
                'provider_id' => $provider->id,
                'api_id' => $provider->api->id,
                'amount' => $post->amount,
                'profit' => $post->profit,
                'txnid'  => $post->txnid,
                'status' => 'pending',
                'user_id'=> $user->id,
                'credit_by' => $user->id,
                'rtype' => 'main',
                'via'   => 'app',
                'balance' => $user->mainwallet,
                'trans_type' => 'debit',
                'product'    => 'recharge'
            ];

            $report = Report::create($insert);

            if (env('APP_ENV') == "server") {
                $result = \Myhelper::curl($url, $method, $query, $header, "yes", "App\Models\Report", $post->txnid);
            }else{
                $result = [
                    'error'     => true,
                    'response'  => ''
                ];
            }

            if($result['error'] || $result['response'] == ''){
                $update['status'] = "pending";
                $update['payid'] = "pending";
                $update['refno'] = "pending";
                $update['description'] = "recharge pending";
            }else{
                switch ($provider->api->code) {
                    case 'recharge1':
                        $doc = json_decode($result['response']);
                        if(isset($doc->status)){
                            if($doc->status == "TXN" || $doc->status == "TUP"){
                                $update['status'] = "success";
                                $update['payid'] = $doc->payid;
                                $update['refno'] = $doc->refno;
                            }elseif($doc->status == "TXF"){
                                $update['status'] = "failed";
                                $update['payid'] = $doc->payid;
                                $update['refno'] = (isset($doc->message)) ? $doc->message : "failed";
                            }else{
                                $update['status'] = "failed";
                                if(isset($doc->message) && $doc->message == "Insufficient Wallet Balance"){
                                    $update['refno'] = "Service down for sometime.";
                                }else{
                                    $update['refno'] = (isset($doc->message)) ? $doc->message : "failed";
                                }
                            }
                        }else{
                            $update['status'] = "pending";
                            $update['payid'] = "pending";
                            $update['refno'] = "pending";
                        }
                        break;

                    case 'recharge4':
                            $doc = json_decode($result['response']);
                            if(isset($doc->status)){
                                if($doc->status == "2" || $doc->status == "1"){
                                    $update['status'] = "success";
                                    $update['payid'] = $doc->rpid;
                                    $update['refno'] = $doc->opid;
                                    $update['description'] = "Recharge Accepted";
                                }elseif($doc->status == "3"){
                                    $update['status'] = "failed";
                                    $update['payid'] = $doc->rpid;
                                    $update['refno'] = $doc->opid;
                                    $update['description'] = (isset($doc->MSG)) ? $doc->MSG : "failed";
                                }else{
                                    $update['status'] = "failed";
                                    if(isset($doc->MSG) && $doc->MSG == "Insufficient Wallet Balance"){
                                        $update['description'] = "Service down for sometime.";
                                    }else{
                                        $update['description'] = (isset($doc->MSG)) ? $doc->MSG : "failed";
                                    }
                                }
                            }else{
                                $update['status'] = "pending";
                                $update['payid'] = "pending";
                                $update['refno'] = "pending";
                                $update['description'] = "recharge pending";
                            }
                            break;
                      case 'recharge2':
                        
                        \DB::table('rp_log')->insert([
                            'ServiceName' => "Recharge",
                            'header' => json_encode($header),
                            'body' => json_encode($parameter),
                            'response' => $result['response'],
                            'url' => $url,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
        
                        $doc = json_decode($result['response']);
                        if(isset($doc->response_code) && in_array($doc->response_code, [1, 3])){
                            $update['status'] = "success";
                            $update['payid']  =  $doc->ackno;
                            $update['refno']  =  $doc->operatorid;
                        }elseif(isset($doc->response_code) && in_array($doc->response_code, [0,2,4,5,6,7,8,9,10,11,12,16,18])){
                            $update['status'] = "failed";
                            $update['refno'] =  $doc->message;
                            if($doc->message == "Insufficient fund in your account. Please topup your wallet before initiating transaction."){
                                $update['refno'] =  "Service down for sometime";
                            }
                        }else{
                            $update['status'] = "pending";
                            $update['refno']  = "Please wait for status change or contact service provider";
                        }
                        break;    
                        default:
                        return response()->json(['statuscode' => "ERR", "message" => "Contact to Administrator."]);
                        break ;  
                }
            }

            if($update['status'] == "success" || $update['status'] == "pending"){
                Report::where('id', $report->id)->update($update);
                \Myhelper::commission($report);
                $output['statuscode'] = "TXN";
                $output['message'] = "Recharge Accepted";
            }else{
                User::where('id', $user->id)->increment('mainwallet', $post->amount - $post->profit);
                Report::where('id', $report->id)->update($update);
                $output['statuscode'] = "TXF";
                $output['message'] = $update['refno'];
            }
            $output['txnid'] = $post->txnid;
            $output['rrn'] = $update['refno'];
            return response()->json($output);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Something went wrong"]);
        }
    }

   public function status(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
            'txnid'      => 'required|numeric'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->first();
        if(!$user){
            $output['statuscode'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        if (!\Myhelper::can('recharge_status', $user->id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Service Not Allowed"]);
        }

        $report = Report::where('id', $post->txnid)->first();

        if(!$report || !in_array($report->status , ['pending', 'success'])){
            return response()->json(['status' => "Recharge Status Not Allowed"], 400);
        }

        switch ($report->api->code) {
            case 'recharge1':
                  $method = "GET";
                  $parameter = "";
                  $header = [];
                  $url = $report->api->url.'/status?token='.$report->api->username.'&apitxnid='.$report->txnid;
                break;
           	case 'recharge2':
        				$url =$report->api->url."recharge/status";
        				$method = "POST";
        				$parameter = json_encode(array(
        					'referenceid' => $report->txnid,
        				));
        				
                        $payload =  [
                            "timestamp" => time(),
                            "partnerId" => $report->api->username,
                            "reqid"     => $report->user_id.Carbon::now()->timestamp
                        ];
                        
                        $key = $report->api->password;
                        $signer = new HS256($key);
                        $generator = new JwtGenerator($signer);
                        $header = array(
                            "Cache-Control: no-cache",
                            "Content-Type: application/json",
                            "Token: ".$generator->generate($payload),
                            "Authorisedkey: ".$report->api->optional3
                        );
                       // dd($url,$parameter,$header) ;
        				break;    
            default:
                return response()->json(['statuscode' => "ERR", "message" => "Recharge Status Not Allowed"]);
                break;
        }

    
        if (env('APP_ENV') != "local") {
                $result = \Myhelper::curl($url, $method, $parameter, $header);
            }else{
                $result = [
                    'error' => false,
                    'response' => json_encode([
                        'statuscode' => 'TXN',
                        'trans_status'  => 'success',
                        'refno'  => 'local',
                        'message'=> 'local'
                    ]) 
                ];
            }
        if($result['response'] != ''){
            switch ($report->api->code) {
                case 'recharge1':
                $doc = json_decode($result['response']);
                if($doc->statuscode == "TXN" && ($doc->trans_status =="success" || $doc->trans_status =="pending")){
                    $update['refno'] = $doc->refno;
                    $update['status'] = "success";

                    $output['statuscode'] = "TXN";
                    $output['txn_status'] = "success";
                    $output['refno'] = $doc->refno;

                }elseif($doc->statuscode == "TXN" && $doc->trans_status =="reversed"){
                    $update['status'] = "reversed";
                    $update['refno'] = $doc->refno;

                    $output['statuscode'] = "TXR";
                    $output['txn_status'] = "reversed";
                    $output['refno'] = $doc->refno;
                }else{
                    $update['status'] = "Unknown";
                    $update['refno'] = $doc->refno;

                    $output['statuscode'] = "TNF";
                    $output['txn_status'] = "unknown";
                    $output['refno'] = $doc->refno;
                }
                break;
               	case 'recharge2':
    				\DB::table('rp_log')->insert([
                        'ServiceName' => "RechargeStatus",
                        'header' => json_encode($header),
                        'body' => json_encode($parameter),
                        'response' => $result['response'],
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                         
					$doc = json_decode($result['response']);
				//	dd($doc,$result['response'],$url, $method, $parameter, $header) ;
					if(isset($doc->data->status) && $doc->data->status == "1"){
						$update['refno'] = $doc->data->operatorid ?? $report->refno;
						$update['status'] = "success";
					    $output['statuscode'] = "TXN";
					    
                        $output['txn_status'] = "success";
                        $output['refno'] = $doc->data->operatorid ?? $report->refno;
					}elseif(isset($doc->data->status) && $doc->data->status == "0"){
						$update['status'] = "reversed";
					    $update['refno'] = (isset($doc->data->operatorid)) ? $doc->data->operatorid : "failed";
					    
					    $output['statuscode'] = "TXR";
                        $output['txn_status'] = "reversed";
                        $output['refno'] = $doc->data->operatorid ?? $report->refno;
			    	}else{
						$update['status'] = "Unknown";
						$update['refno'] = (isset($doc->data->operatorid)) ? $doc->data->operatorid : "Unknown";
						
					   $output['statuscode'] = "TNF";
                       $output['txn_status'] = "unknown";
                       $output['refno'] = (isset($doc->data->operatorid)) ? $doc->data->operatorid : "Unknown";
					}
					break;            
            }
            
            $product = "recharge";

            if ($update['status'] != "Unknown") {
                $reportupdate = Report::where('id', $report->id)->update($update);
                if ($reportupdate && $update['status'] == "reversed") {
                    \Myhelper::transactionRefund($post->id);
                }

                if($report->user->role->slug == "apiuser" && $report->status == "pending" && $post->status != "pending"){
                    \Myhelper::callback($report, $product);
                }
            }
            return response()->json($output);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Something went wrong, contact your service provider"]);
        }
    }
 public function getplan(Request $post)
    {
        $url = "https://api.paysprint.in/api/v1/service/recharge/hlrapi/browseplan";   
        $parameter = [
            "op" =>  $post->providername,
            "circle" =>  $post->circle
        ];
           
        $token = $this->getToken($post->user_id."MP".Carbon::now()->timestamp);
        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Token: ".$token['token'],
            "Authorisedkey: ".$this->api->optional3
        );


        $query = json_encode($parameter);
        $method = "POST"; 
        
        $result = \Myhelper::curl($url, $method, $query, $header, "no");
        //dd($url, $parameter, $result);
          \DB::table('rp_log')->insert([
             'ServiceName' => "Recharge Plan",
             'header' => json_encode($header),
             'body' => json_encode($query),
             'response' => $result['response'],
             'url' => $url,
             'created_at' => date('Y-m-d H:i:s')
             ]);
        if($result['response'] != ''){
            $response = json_decode($result['response']);
            if(isset($response->response_code) && $response->response_code == "1"){
                return response()->json(['status' => "success", "data" => $response->info], 200);
            }
            return response()->json(['status' => "failed", "message" => "Something went wrong"]);
        }else{
            return response()->json(['status' => "failed", "message" => "Something went wrongs"]);
        }
    }
    
    
 public function getoperator(Request $post)
    {   
        if($post->type ="mobile"){
             $url = "https://api.paysprint.in/api/v1/service/recharge/hlrapi/hlrcheck";
             $parameter = [
                "number" =>  $post->number, 
                "type"   =>  "mobile"
             ];
        }else{
              return response()->json(['status' => "failed", "message" => "Something went wrongs"]);
            $url = "https://api.paysprint.in/api/v1/service/recharge/hlrapi/dthinfo" ;
             $parameter = [
            "number" =>  "1369814007", 
            "op"   =>    "TataSky"
            ];
        }
       
       // dd($url,$parameter,$post->all()) ;
       // $url = "https://paysprint.in/service-api/api/v1/service/recharge/hlrapi/hlrcheck" ;
       

        $token = $this->getToken($post->user_id."OP".Carbon::now()->timestamp);
        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Token: ".$token['token'],
           "Authorisedkey: OWU3ZjExYjI1YmVhYjkyMGU5ZWRkMmMxYTVmZTYzOWE="
        );

        $query = json_encode($parameter);
        $method = "POST"; 
        
        $result = \Myhelper::curl($url, $method, $query, $header, "no");
        
         \DB::table('rp_log')->insert([
            'ServiceName' => "getoprator",
            'header' => json_encode($header),
            'body' => json_encode($parameter),
            'response' => $result['response'],
             'url' => $url,
             'created_at' => date('Y-m-d H:i:s')
          ]);
       // dd($result,$url,$parameter,$post->all()) ;
        if($result['response'] != ''){
            $response = json_decode($result['response']);
            if(isset($response->response_code) && $response->response_code == "1"){
                $provider = Provider::where('name', 'like', '%'.strtolower($response->info->operator).'%')->where('type', $post->type)->first();
                //dd($result,$url,$parameter, $provider);
                return response()->json(['status' => "success", "provider_id" => $provider->id, "circle" => $response->info->circle, "providername" => $response->info->operator], 200);
            }
            return response()->json(['status' => "failed", "message" => "Something went wrong"]);
        }else{
            return response()->json(['status' => "failed", "message" => "Something went wrongs"]);
        }
    }
    
    
     public function getToken($uniqueid)
    {
        $payload =  [
            "timestamp" => time(),
            "partnerId" => $this->api->username,
            "reqid"     => $uniqueid
        ];
        
        $key = $this->api->password;
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }
    
    
    public function getdthinfo(Request $post)
    {
        $provider = Provider::where('id', $post->operator)->first();
        
        $url = "https://api.paysprint.in/api/v1/service/recharge/hlrapi/dthinfo";
        $parameter = [
            "canumber"   =>  $post->number,
            "op" =>  $provider->recharge3
        ];

        $token = $this->getToken($post->user_id."DTH".Carbon::now()->timestamp);
        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Token: ".$token['token'],
            "Authorisedkey: ".$this->api->optional3
        );

        $query = json_encode($parameter);
        $method = "POST"; 
        
        $result = \Myhelper::curl($url, $method, $query, $header, "no");
        //dd($result,$url,$parameter);
        if($result['response'] != ''){
            $response = json_decode($result['response']);
            if(isset($response->response_code) && $response->response_code == "1"){
                return response()->json(['status' => "success", "data" => $response->info[0]], 200);
            }
            return response()->json(['status' => "failed", "message" => "Something went wrong"]);
        }else{
            return response()->json(['status' => "failed", "message" => "Something went wrongs"]);
        }
    }
    
}
