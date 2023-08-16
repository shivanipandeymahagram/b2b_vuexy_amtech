<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Report;
use App\Models\Api;
use App\Models\Circle;
use App\User;
use Carbon\Carbon;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\Generator;

class RechargeController extends Controller
{
    protected $api;
    public function __construct()
    {
        $this->api = Api::where('code', 'recharge2')->first();
    }
    
    public function index($type)
    {
        if (\Myhelper::hasRole('admin') || !\Myhelper::can('recharge_service')) {
            abort(403);
        }
        $data['type'] = $type;
        $data['providers'] = Provider::where('type', $type)->where('status', "1")->orderBy('name')->get();
        $data['circle'] = Circle::where('maha_circle_name', '!=', '')->get();
        return view('service.recharge')->with($data);
    }

    public function payment(\App\Http\Requests\Recharge $post)
    {
        if (\Myhelper::hasRole('admin') || !\Myhelper::can('recharge_service')) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }
        
        $user = \Auth::user();
        $post['user_id'] = $user->id;
        if($user->status != "active"){
            return response()->json(['status' => "Your account has been blocked."], 400);
        }

        $provider = Provider::where('id', $post->provider_id)->first();

        if(!$provider){
            return response()->json(['status' => "Operator Not Found"], 400);
        }

        if($provider->status == 0){
            return response()->json(['status' => "Operator Currently Down."], 400);
        }

        if(!$provider->api || $provider->api->status == 0){
            return response()->json(['status' => "Recharge Service Currently Down."], 400);
        }
        
          if ($this->pinCheck($post) == "fail") {
            // return response()->json(['status' => "Transaction Pin is incorrect"], 400);
        }

        if($user->mainwallet < $post->amount){
            return response()->json(['status'=> 'Low Balance, Kindly recharge your wallet.'], 400);
        }

        $previousrecharge = Report::where('number', $post->number)->where('amount', $post->amount)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
        if($previousrecharge > 0){
            return response()->json(['status'=> 'Same Transaction allowed after 2 min.'], 400);
        }
        
        // dd($provider->api->code);
        
         do {
            $post['txnid'] = $this->transcode().rand(11, 99).Carbon::now()->timestamp;
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);
                
        $method = "GET";
        $header = [];
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
                    $method = "GET";
                break;
            case 'recharge3':
                    $ip_server = $_SERVER['SERVER_ADDR'];
                    $gpsdata = geoip($post->ip());
                    
                    do {
                        $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                    } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);
                     
                    $parameter['mmusercode']=$provider->api->optional1;
                    $parameter['phoneno']=$post->number;
                    $parameter['operatorid']=$provider->recharge4;
                    $parameter['amount']=$post->amount;
                    $parameter['rechargetype']="1";
                    $parameter['switchto']="2";
                    $parameter['latitude']=sprintf('%0.4f', $gpsdata->lat);
                    $parameter['longitude']=sprintf('%0.4f', $gpsdata->lon);
                    $parameter['ip']=$post->ip();
                    $parameter['routetype']="web";
                    $parameter['clientrefid']=$post->txnid;
                    $url = $provider->api->url."Recharges/Process";
    
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
               // $result = \Myhelper::curl($url, "POST", json_encode($parameter),$header , "yes", "App\Models\Report", $post->txnid);
               /// dd(json_encode($parameter),$header,$url,$result);

                //$query = json_encode($parameter);
                break;

                case 'recharge5':
                    $gpsdata =  geoip($post->ip());
                    $latlong =  $gpsdata->lat.','.$gpsdata->lon;
                    
                    do {
                        $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                    } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);
                    $url = $provider->api->url."TransactionAPI?UserID=".$provider->api->username."&Token=".$provider->api->password."&Account=".$post->number."&SPKey=".$provider->recharge4."&Amount=".$post->amount."&APIRequestID=".$post->txnid."&Optional1=&Optional2=&Optional3=&Optional4=&CustomerNumber=8910992282&Pincode=743129&Format=1&GEOCode=".$latlong;
                    $header = [];
                $query = '';
                    break; 
                      
               default :
                     return response()->json(['status'=> 'Api down for Sometime.'], 400);
                break;
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
                'via'   => 'portal',
                'balance' => $user->mainwallet,
                'trans_type'  => 'debit',
                'product'     => 'recharge',
                'create_time' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            try {
                $report = Report::create($insert);
            } catch (\Exception $e) {
                User::where('id', $user->id)->increment('mainwallet', $post->amount - $post->profit);
                return response()->json(['status' => "failed", "description" => "Something went wrong"], 200);
            }
            

            if (env('APP_ENV') == "server") {
                $result = \Myhelper::curl($url, $method, $query, $header, "yes", "App\Models\Report", $post->txnid);

            }else{
                $result = [
                    'error' => true,
                    'response' => '' 
                ];
            }

            // dd($url,$result);

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
                                $update['description'] = "Recharge Accepted";
                            }elseif($doc->status == "TXF"){
                                $update['status'] = "failed";
                                $update['payid'] = $doc->payid;
                                $update['refno'] = $doc->refno;
                                $update['description'] = (isset($doc->message)) ? $doc->message : "failed";
                            }else{
                                $update['status'] = "failed";
                                if(isset($doc->message) && $doc->message == "Insufficient Wallet Balance"){
                                    $update['description'] = "Service down for sometime.";
                                }else{
                                    $update['description'] = (isset($doc->message)) ? $doc->message : "failed";
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

                    case 'recharge3':
                            $doc = json_decode($result['response']);
                            if(isset($doc->statuscode)){
                                if(strtolower($doc->statuscode) == "000"){
                                    $update['status'] = "success";
                                    $update['payid'] = $doc->txnid;
                                    $update['refno'] = $doc->operatorid;
                                    $update['description'] = "Recharge Accepted";
                                }elseif(strtolower($doc->statuscode) == "001"){
                                    $update['status'] = "failed";
                                    $update['payid'] = isset($doc->txnid) ? $doc->txnid : '';
                                    $update['refno'] = (isset($doc->operatorid)) ? $doc->operatorid : "failed";
                                    //$update['description'] = (isset($doc->message)) ? $doc->message : "failed";
                                    if(isset($doc->message) && $doc->message == "Insufficient balance"){
                                        $update['description'] = "Service down for sometime.";
                                    }else{
                                        $update['description'] = (isset($doc->message)) ? $doc->message : "failed";
                                    }
                                }else{
                                    $update['status'] = "pending";
                                    $update['payid'] = (isset($doc->txnid)) ? $doc->txnid : "pending";
                                    $update['refno'] = (isset($doc->operatorid)) ? $doc->operatorid : "pending";
                                    $update['description'] = (isset($doc->message)) ? $doc->message : "failed";
                                }
                            }else{
                                $update['status'] = "pending";
                                $update['payid'] = "pending";
                                $update['refno'] = "pending";
                                $update['description'] = "recharge pending";
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
                            
                            case 'recharge5':

                                $doc = json_decode($result['response']);

                                if(isset($doc->status)){
                                    if($doc->status == "2" && $doc->errorcode == "200"){
                                        $update['status'] = "success";
                                        $update['payid'] = $doc->rpid;
                                        $update['refno'] = $doc->opid;
                                        $update['description'] = "Recharge Accepted";
                                    }elseif($doc->status == "3"){
                                        $update['status'] = "failed";
                                        $update['payid'] = $doc->rpid;
                                        $update['refno'] = $doc->opid;
                                        $update['description'] = (isset($doc->MSG)) ? $doc->MSG : "Failed";
                                    }elseif($doc->status == "1"){
                                        $update['status'] = "pending";
                                        $update['payid'] = $doc->rpid;
                                        $update['refno'] = $doc->opid;
                                        $update['description'] = (isset($doc->MSG)) ? $doc->MSG : "Pending";
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
                        default:
                        return response()->json([ "status" => "Contact to Administrator."]);
                        break ;      
                }
            }

            if($update['status'] == "success" || $update['status'] == "pending"){
                Report::where('id', $report->id)->update($update);
                \Myhelper::commission($report);
            }else{
                User::where('id', $user->id)->increment('mainwallet', $post->amount - $post->profit);
                Report::where('id', $report->id)->update($update);
                 
            }
            return response()->json($update, 200);
        }else{
            return response()->json(['status' => "failed", "description" => "Something went wrong"], 200);
        }
    }
    
    public function getoperator(Request $post)
    {
        $url = "https://api.paysprint.in/api/v1/service/recharge/hlrapi/hlrcheck";
       // $url = "https://paysprint.in/service-api/api/v1/service/recharge/hlrapi/hlrcheck" ;
      
        $parameter = [
            "number" =>  $post->number, 
            "type"   =>  $post->type 
        ];

        $token = $this->getToken1($post->user_id."OP".Carbon::now()->timestamp);
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
                            'ServiceName' => "Get Oprator",
                            'header' => json_encode($header),
                            'body' => json_encode($parameter),
                            'response' => $result['response'],
                            'url' => $url,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
        if($result['response'] != ''){
            $response = json_decode($result['response']);
            if(isset($response->response_code) && $response->response_code == "1"){
                $provider = Provider::where('name', 'like', '%'.strtolower($response->info->operator).'%')->where('type', $post->type)->first();
                //dd($result,$url,$parameter, $provider);
                return response()->json(['status' => "success", "data" => $provider->id, "circle" => $response->info->circle, "providername" => $response->info->operator], 200);
            }
            return response()->json(['status' => "failed", "message" => "Something went wrong"]);
        }else{
            return response()->json(['status' => "failed", "message" => "Something went wrongs"]);
        }
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
    
    public function getplanpaysprint(Request $post)
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
                            'ServiceName' => "Get Plan",
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
    

    public function getplan(Request $post)
    {
        
        $provider = Provider::where('id', $post->providername)->first();
        if(!$provider){
            return response()->json(['status' => "Operator Not Found"], 400);
        }
        $apis = Api::where('code', 'recharge3')->first();
        
        //$url = "http://securepayments.net.in/api/recharge/getplan?token=".$provider->api->username."&operator=".$provider->recharge1;
        $header = array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "secretkey:".$apis->username,
                        "saltkey:".$apis->password
                    );
                    
         if($post->type=="mobile"){
            $url="https://partners.mahagram.in/rechargesplan/api/PlanAPI/simpleplan";
            $parameter['cricle']=$post->circle;
            $parameter['Operator']=$provider->name;
         }else{
             $parameter['Operator']=$provider->name;
             $url="https://partners.mahagram.in/rechargesplan/api/PlanAPI/dthplan";
         }  
      
        
        $result = \Myhelper::curl($url, "POST", json_encode($parameter),$header , "yes");
        //dd($result);
        //  dd($url,$parameter,$header,$result);
        if($result['response'] != ''){
            $response = json_decode($result['response']);
            
            if(isset($response->statuscode) && $response->statuscode ==000){
                return response()->json(['status' => "success", "data" => $response->data], 200);
            }

            return response()->json(['status' => "failed", "message" =>$response->message ?? "Something went wrong"]);
        }else{
            return response()->json(['status' => "failed", "message" => "Something went wrongs"]);
        }
    }

    public function getToken1($uniqueid)
    {
        $payload =  [
            "timestamp" => time(),
            "partnerId" => "PS003380",
            "reqid"     => $uniqueid
        ];
        
        $key = "UFMwMDMzODBjZTI1ZjZkYzM4MGEzMDUzZTVmZjY0MDE4YjlkYzU3YQ==";
        $signer = new HS256($key);
        $generator = new Generator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }
    
    
    public function getToken($uniqueid)
    {
        $payload =  [ 
            "timestamp" => time(),
            "partnerId" => $this->api->username,
            "reqid"     => $uniqueid
        ];
        
        $key =$this->api->password;
        $signer = new HS256($key);
        $generator = new Generator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }
    
    public function getProviderrp(Request $post)
    {
    //    $url = "https://paysprint.in/service-api/api/v1/service/bill-payment/bill/getoperator";
        $url = " https://api.paysprint.in/api/v1/service/recharge/recharge/getoperator" ;
      //  $url= "https://api.paysprint.in/api/v1/service/balance/balance/authenticationcheck" ;
      //  $url = "https://api.paysprint.in/api/v1/service/balance/balance/mainbalance" ;
        $parameter = [
            "mode" =>"offline"
        ];

        $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Token: ".$token['token'],
            "Authorisedkey: ".$this->api->optional3
        );

        $query = json_encode($parameter);
        $method = "POST";
        
        $result = \Myhelper::curl($url, "POST", json_encode($parameter),$header , "yes", "App\Models\Report", $post->txnid);
        // dd(json_encode($parameter),$header,$url,$result);

                //$query = json_encode($parameter);
    }
    
    public function getbalance()
    {

         //$token = $this->getToken("2".Carbon::now()->timestamp);
            $curl = curl_init(); 
           
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.paysprint.in/api/v1/service/balance/balance/mainbalance',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_HTTPHEADER => array(
                'Token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0aW1lc3RhbXAiOjE2NzUyNTQyMjksInBhcnRuZXJJZCI6IlBTMDAzMDU5IiwicmVxaWQiOiI4OTc1OFNTRkdBU0RHREYifQ.joANm496waltF8s27ZEATKcO08IGRgOZ_oNNKzFQGgc',
                'Authorisekdkey: ZjcyYWI4ODFmODdiYWQ2Yjg5OTA5OWRmZjEwMzE2NzE=',
                'Cookie: UqZBpD3n3iPIDwJU=v1Mqt1g++CknF; ci_session=a9348e260d0d6780854007669f8b0ce05765f3d2'
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            echo $response;
    
        
        
    }
}
