<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\User;
use App\Models\Report;
use App\Models\Mahaagent;
use Carbon\Carbon;
use App\Models\Api;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class BillpayController extends Controller
{
    protected $billapi,$api;
    public function __construct()
    {
        $this->billapi = Api::where('code', 'mhbill')->first();
        $this->api = Api::where('code', 'paysprintbill')->first();
    }

    public function providersList(Request $post)
    {
        $providers = Provider::where('type', $post->type)->where('status', "1")->where('mode', "online")->orderBy('name')->get();
        return response()->json(['statuscode' => "TXN", 'message' => "Provider Fetched Successfully", 'data' => $providers]);
    }
    
    
    public function getToken($uniqueid)
      {
        $payload =  [
            "timestamp" => time(),
            "partnerId" =>  $this->api->username,
            "reqid"     => $uniqueid
        ];
        
        $key = $this->api->password;
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
       // dd($payload,$key,$generator);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }

    public function getprovider(Request $post)
    {
        
        $provider = Provider::where('id', $post->provider_id)->first();
        
        if($provider->api_id==10){
            $mode="false";
            
        }
        elseif($provider->api_id==21){
            $mode="true";
        }
        else{
             $mode="false";
        }
        $params = [];
        for ($i=0; $i < sizeOf($provider->paramname); $i++) {
            $param['paramname'] = isset($provider->paramname[$i])?$provider->paramname[$i]:'';
            $param['maxlength'] = isset($provider->maxlength[$i])?$provider->maxlength[$i]:'';
            $param['minlength'] = isset($provider->minlength[$i])?$provider->minlength[$i]:'';
            $param['ismandatory'] = isset($provider->ismandatory[$i])?$provider->ismandatory[$i]:'';
            $param['fieldtype'] = isset($provider->fieldtype[$i])?$provider->fieldtype[$i]:'';
            $param['regex'] = isset($provider->regex[$i])?$provider->regex[$i]:'';
            
            $params[] = $param;
        }
        $provider['parame'] = $params;
        $provider['mode']=$mode;
        return response()->json($provider);
    }

    public function transaction(Request $post)
    {
        if(!in_array($post->type, ['validate', 'payment'])){
            return response()->json(['statuscode' => "ERR", "message" => "Type parameter request in invalid"]);
        }

        
        if($post->type == "validate"){
            $rules = array(
                'apptoken' => 'required',
                'user_id'  =>'required|numeric',
                'provider_id' => 'required|numeric'
            );
        }else{
            $rules = array(
                'apptoken' => 'required',
                'user_id'  =>'required|numeric',
                'provider_id' => 'required|numeric',
                'amount'      => 'required|numeric|min:10'
            );
        }
        
        $agent = Mahaagent::where('user_id', $post->user_id)->first();

        // if(!$agent){
        //     $output['statuscode'] = "ERR";
        //     $output['message']    = "BBPS Onboarding Pending";
        //     return response()->json($output);
        // }

        // $agent    = $this->bbpsregistration($post, $agent);

        // if(!$agent->bbps_id){
        //     $output['statuscode'] = "ERR";
        //     $output['message']    = "BBPS Onboarding Approval Pending";
        //     return response()->json($output);
        // }

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->first();
        if(!$user){
            $output['statuscode'] = "ERR";
            $output['message']    = "User details not matched";
            return response()->json($output);
        }

        if (!\Myhelper::can('billpayment_service', $user->id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Service Not Allowed"]);
        }

        if($user->status != "active"){
            return response()->json(['statuscode' => "ERR", "message" => "Your account has been blocked."]);  
        }

        if(!isset($post->mode)){
            $post['mode'] = "online" ;
        }

        $provider = Provider::where('id', $post->provider_id)->where('mode',$post->mode)->first();

        if(!$provider){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Not Found"]);
        }

        if($provider->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
        }

        if(!$provider->api || $provider->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => " Service Currently Down."]);
        }
         $post['operatorType'] = $provider->type ;

        $post['crno'] = "";
        for ($i=0; $i < $provider->paramcount; $i++) { 
            if($provider->ismandatory[$i] == "TRUE"){
                $rules['number'.$i] = "required";
                $post['crno'] .= $post['number'.$i]."|";
            }
        }
      
        switch ($post->type) {
            case 'validate':
                switch ($provider->api->code) {
                    case 'paysprintbill':
                        switch ($post->operatorType) {
                            case 'fasttag':
                                $url = $provider->api->url."fastag/Fastag/fetchConsumerDetails";
                                $parameter = [
                                    "operator" => $provider->recharge1,
                                    "canumber" => $post->number0
                                ];
                                $token = $this->getToken(\Auth::id().Carbon::now()->timestamp);
                                
                                // $url = "https://paysprint.in/service-api/api/v1/service/fastag/Fastag/fetchConsumerDetails";
                                // $payload =  [
                                //     "timestamp" => time(),
                                //     "partnerId" => "PS00418",
                                //     "reqid"     => \Auth::id().Carbon::now()->timestamp
                                // ];
                                
                                // $signer = new HS256("UFMwMDQxOGFlMGMyZWU4MTEzY2QwODY2MmEwMmM1OWUyYjFkMDdh");
                                // $generator = new JwtGenerator($signer);
        
                                // $header = array(
                                //     "Cache-Control: no-cache",
                                //     "Content-Type: application/json",
                                //     "Token: ".$generator->generate($payload),
                                //     "Authorisedkey: MDE5NzEyMDg5Y2RiYmVkYTdmOTQyM2MzZTk0MzE4MTU="
                                // );
                                
                                $header = array(
                                    "Cache-Control: no-cache",
                                    "Content-Type: application/json",
                                    "Token: ".$token['token'],
                                    "Authorisedkey: ".$this->api->optional1
                                );
                                
                                break;
                            
                            default:
                                $url = $provider->api->url."bill-payment/bill/fetchbill";
                                $parameter = [
                                    "operator" => $provider->recharge1,
                                    "canumber" => $post->number0,
                                    "mode"     => $post->mode ?? "online"
                                ];
                                
                                if($post->number1){
                                  $parameter['ad1'] = $post->number1; 
                               }
                                for ($i=1; $i < sizeof($provider->paramname); $i++) { 
                               //     $parameter[$provider->paramname[$i]] = $post['number'.$i]; 
                                }
                               
                                
                                $token = $this->getToken(\Auth::id().Carbon::now()->timestamp);
                                $header = array(
                                    "Cache-Control: no-cache",
                                    "Content-Type: application/json",
                                    "Token: ".$token['token'],
                                    "Authorisedkey: ".$this->api->optional1
                                );
                                break;
                        }
                        
                        $result = \Myhelper::curl($url, "POST", json_encode($parameter), $header, "no");
                     //   dd($url, $header, $parameter, $result);
                        
                        \DB::table('rp_log')->insert([
                            'ServiceName' => "BillFetch",
                            'header' => json_encode($header),
                            'body' => json_encode($parameter),
                            'response' => $result['response'],
                            'url' => $url,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        if(!$result['error'] && $result['response'] != ''){
                            $doc = json_decode($result['response']);
                            if(isset($doc->response_code) && in_array($doc->response_code, [1, 4, 8])){
                                return response()->json([
                                    'statuscode' => "TXN",
                                    'data'       => [
                                        "customername" => $doc->name,
                                        "duedate"      => $doc->duedate,
                                        "dueamount"    => $doc->amount,
                                        "TransactionId"     => json_encode($doc->bill_fetch),
                                         "mode"     => $post->mode
                                    ]
                                ]);
                            }else{
                                $offline = Provider::where('recharge1', $provider->recharge1)->where('mode', 'offline')->first();
                                
                                if($offline){
                                    $url = $provider->api->url."bill-payment/bill/fetchbill";
                                    $parameter = [
                                        "operator" => $provider->recharge1,
                                        "canumber" => $post->number0,
                                         "mode"     => $post->mode ??'online'
                                    ];
                                    
                                    for ($i=1; $i < sizeof($provider->paramname); $i++) { 
                                        //$parameter[$provider->paramname[$i]] = $post['number'.$i]; 
                                    }
                                    
                                    $token = $this->getToken(\Auth::id().'OFF'.Carbon::now()->timestamp);
                                    $header = array(
                                        "Cache-Control: no-cache",
                                        "Content-Type: application/json",
                                        "Token: ".$token['token'],
                                        "Authorisedkey: ".$this->api->optional1
                                    );
                                    
                                    $result = \Myhelper::curl($url, "POST", json_encode($parameter), $header, "no");
                            
                                    \DB::table('rp_log')->insert([
                                        'ServiceName' => "BillFetch-Offline",
                                        'header' => json_encode($header),
                                        'body' => json_encode($parameter),
                                        'response' => $result['response'],
                                        'url' => $url,
                                        'created_at' => date('Y-m-d H:i:s')
                                    ]);
                                    
                                    if(!$result['error'] && $result['response'] != ''){
                                        $doc = json_decode($result['response']);
                                        if(isset($doc->response_code) && in_array($doc->response_code, [1, 4, 8])){
                                            return response()->json([
                                                'statuscode' => "TXN",
                                                'data'       => [
                                                    "customername" => $doc->name,
                                                    "duedate"      => $doc->duedate,
                                                    "dueamount"    => $doc->amount,
                                                    "TransactionId"     => json_encode($doc->bill_fetch),
                                                    "mode"     => $post->mode
                                                ]
                                            ]);
                                        }else{
                                            return response()->json(['statuscode' => "ERR", "message" => $doc->message]);
                                        }
                                    }else{
                                        return response()->json(['statuscode' => "ERR", "message" => $result['error']]);
                                    }
                                }else{
                                    return response()->json(['statuscode' => "ERR", "message" => $doc->message]);
                                }
                            }
                        }else{
                            return response()->json(['statuscode' => "ERR", "message" => $result['error']]);
                        }
                    break;
                    
                    default:
                        $url = $this->billapi->url."GetAmount";
        
                        $json_data = [
                            "requestby"    => $this->billapi->username,
                            "securityKey"  => $this->billapi->password,
                            "billerId"     => $provider->recharge2,
                            'mobileNumber' => $user->mobile,
                            "crno"         => rtrim($post->crno, "|"),
                            "ip"           => $post->ip(),
                            "mac"          => "BC-EE-7B-9C-F6-C0",
                            "agentId"      => $agent->bbps_id
                        ];
        
                        $header = array(
                            "authorization: Basic ".base64_encode($this->billapi->username.":".$this->billapi->optional1),
                            "cache-control: no-cache",
                            "content-type: application/json"
                        );
                        $result = \Myhelper::curl($url, "POST", json_encode($json_data), $header, "no");
                       //dd([$url, $header, $json_data, $result]);
                        if($result['response'] != ""){
                            $response = json_decode($result['response']);
                           //dd($response);
                            if(isset($response->ResponseCode) && $response->ResponseCode == "000"){
                                //dd($response->AdditionalDetails[0]->Value);
                                if(isset($response->AdditionalDetails[0]->Value)){
                                    $extradata = isset($response->AdditionalDetails[0]->Value)?$response->AdditionalDetails[0]->Value:0;
                                }else{
                                    $extradata = 0;
                                }
                                
                                return response()->json([
                                    'statuscode' => "TXN",
                                    'data'       => [
                                        "customername" => $response->CustomerName,
                                        "duedate"      => $response->BillDueDate,
                                        "dueamount"       => $response->BillAmount,
                                        "TransactionId"=> $response->TransactionId,
                                        'balance'      => $extradata
                                    ]
                                ], 200);
                            }
                        }
                        return response()->json(['statuscode' => "ERR", "message" => isset($response->ResponseMessage) ? $response->ResponseMessage : "Something went wrong"], 400);
                        break;
                }
                break;

            case 'payment':
                
                if ($this->pinCheck($post) == "fail") {
                    return response()->json(['statuscode' => "ERR", "message" => "Transaction Pin is incorrect"]);
                }
                
                if($user->mainwallet - $this->mainlocked() < $post->amount){
                    return response()->json(['statuscode' => "ERR", "message"=> 'Low Balance, Kindly recharge your wallet.']);
                }

          
                $previousrecharge = Report::where('number', $post->number0)->where('amount', $post->amount)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                if($previousrecharge > 0){
                    return response()->json(['statuscode' => "ERR", "message"=> 'Same Transaction allowed after 2 min.'], 200);
                }
                if($provider->api->code=="paysprintbill"){
                      $providers = Provider::where('id', $post->provider_id)->first();
                        //   switch ($post->operatorType) { 
                        //       case 'fasttag':
                        //           $providers = Provider::where('type', 'fasttagslab')->first();
                        //           $post['provider_id']=$providers->id;
                        //         break;
                        //         default:
                        //             if($post->mode=="offline"){
                        //         if($post->amount > 0 && $post->amount <= 100000){
                        //           $providers = Provider::where('recharge1', 'electricityslab1')->first();
                        //           $post['provider_id']=$providers->id;
                                  
                        //           } 
                        //          else{
                        //           $providers = Provider::where('recharge1', 'electricityslab2')->first();
                        //           $post['provider_id']=$providers->id;
                                  
                        //          } 
                        //         }
                        //         break;
                        //         $post['provider_id']=0;
                        //       }
                          }
                        else{
                            $providers = Provider::where('id', $post->provider_id)->first();
                            $post['provider_id']=0;
                        }
                        
                    $post['profit'] = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
                    $post['tds']    =  0 ;
                    $post['gst']    =  0 ;
                    $post['debitAmount'] = $post->amount - ($post->profit - $post->tds - $post->gst);
                    $debit = User::where('id', $user->id)->decrement('mainwallet', $post->amount - $post->profit);
                if ($debit) {
                    do {
                        $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                    } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

                    $insert = [
                        'number' => $post->number0,
                        'mobile'  => isset($post->number1)?$post->number1:$user->mobile,
                        'provider_id' => $provider->id,
                        'api_id' => $provider->api->id,
                        'amount' => $post->amount,
                        'profit' => $post->profit,
                        'tds' => $post->tds,
                        'gst' => $post->gst,
                        'txnid' => $post->txnid,
                        'option1' => $post->biller,
                        'option2' => $post->duedate,
                        'status' => 'pending',
                        'user_id'    => $user->id,
                        'credit_by'  => $user->id,
                        'rtype'      => 'main',
                        'via'        => 'app',
                        'balance'    => $user->mainwallet,
                        'trans_type' => 'debit',
                        'product'    => 'billpay'
                    ];

                    $report = Report::create($insert);

                    switch ($provider->api->code) {
                        case 'billpayment':
                            $url = $provider->api->url."/payment";
                            
                            $parameter = [
                                "token"    => $provider->api->username,
                                "operator" => $provider->recharge1,
                                "number"   => $post->number,
                                "mobile"   => $post->mobile,
                                "amount"   => $post->amount,
                                "biller"   => $post->biller,
                                "duedate"  => $post->duedate,
                                "apitxnid" => $post->txnid,
                            ];

                            if (env('APP_ENV') == "server") {
                                $result = \Myhelper::curl($url, "POST", json_encode($parameter), ["Content-Type: application/json", "Accept: application/json"], "yes", "App\Models\Report", $post->txnid);
                            }else{
                                $result = [
                                    'error'    => false,
                                    'response' => ''
                                ];
                            }
                            break;
                        
                        case 'paysprintbill':
                            $gpsdata = geoip($post->ip());
                            
                            $parameter = [
                                "operator" => $provider->recharge1,
                                "canumber" => $post->number0,
                                "amount"   => $post->amount,
                                "referenceid" => $post->txnid,
                                "latitude"    => $gpsdata->lat,
                                "longitude"   => $gpsdata->lon,
                                "mode"        => $post->mode,
                                "bill_fetch"  => json_decode($post->TransactionId)
                            ];
                            
                            for ($i=1; $i < sizeof($provider->paramname); $i++) { 
                                $parameter[$provider->paramname[$i]] = $post["number".$i];
                            }
                            
                            //dd($parameter);
                            
                            $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                            $header = array(
                                "Cache-Control: no-cache",
                                "Content-Type: application/json",
                                "Token: ".$token['token'],
                                "Authorisedkey: ".$this->api->optional1
                            );
                            
                            switch ($post->operatorType) {

                                case 'fasttag':
                                    $url = $provider->api->url."fastag/Fastag/recharge";
                                    break;
                                    
                                default:
                                    $url = $provider->api->url."bill-payment/bill/paybill";
                                    break;
                            }

                            $query  = json_encode($parameter);
                            $method = "POST";
                            if (env('APP_ENV') == "server") {
                                $result = \Myhelper::curl($url, "POST", json_encode($parameter), $header, "yes", "App\Models\Report", $post->txnid);
                                //dd($result,json_encode($parameter),$url);
                            }else{
                                $result = [
                                    'error' => true,
                                    'response' => '' 
                                ];
                            }
                            break;
                        
                        default:
                            $agent = Mahaagent::where('user_id', $post->user_id)->first();
                            if(!$agent){
                                return response()->json(['statuscode' => 'ERR', 'message' => "Agent Onboard Pending"]);
                            }

                            if(!$agent->bbps_id){
                                return response()->json(['statuscode' => 'ERR', 'message' => "Agent Approval Pending"]);
                            }

                            $url = $provider->api->url."SendBillPaymentRequest";
                            $parameter = [
                                "requestby"   => $provider->api->username,
                                "securityKey" => $provider->api->password,
                                "billAmount"  => $post->amount,
                                "transid"     => $post->TransactionId,
                                "billerId"    => $provider->recharge2,
                                'mobileNumber'=> $user->mobile,
                                "crno"        => rtrim($post->crno, "|"),
                                "ip"          => $post->ip(),
                                "mac"         => "BC-EE-7B-9C-F6-C0",
                                "agentId"     => $agent->bbps_id,
                                'BillerCategory' => "Electricity"
                            ];
                            
                            $header = array(
                                "authorization: Basic ".base64_encode($provider->api->username.":".$provider->api->optional1),
                                "cache-control: no-cache",
                                "content-type: application/json"
                            );

                            if (env('APP_ENV') == "server") {
                                $result = \Myhelper::curl($url, "POST", json_encode($parameter), $header, "yes", "App\Models\Report", $post->txnid);
                            }else{
                                $result = [
                                    'error' => true,
                                    'response' => '' 
                                ];
                            }

                            break;
                    }

                    if($result['error'] || $result['response'] == ''){
                        $update['status'] = "pending";
                        $update['payid'] = "pending";
                        $update['description'] = "billpayment pending";
                    }else{
                        $doc = json_decode($result['response']);

                        switch ($provider->api->code) {
                            case 'billpayment':
                                if(isset($doc->statuscode)){
                                    if($doc->statuscode == "TXN"){
                                        $update['status'] = "success";
                                        $update['payid'] = $doc->data->txnid;
                                        $update['description'] = "Billpayment Accepted";
                                    }elseif($doc->statuscode == "TXF"){
                                        $update['status'] = "failed";
                                        $update['payid'] = $doc->data->txnid;
                                        $update['description'] = (isset($doc->message)) ? $doc->message : "failed";
                                    }else{
                                        $update['status'] = "failed";
                                        if($doc->status == "Insufficient Wallet Balance"){
                                            $update['description'] = "Service down for sometime.";
                                        }else{
                                            $update['description'] = (isset($doc->message)) ? $doc->message : "failed";
                                        }
                                    }
                                }else{
                                    $update['status'] = "pending";
                                    $update['payid'] = "pending";
                                    $update['description'] = "billpayment pending";
                                }
                                break;
                                
                            case 'paysprintbill':  
                                \DB::table('rp_log')->insert([
                                    'ServiceName' => "BillPay",
                                    'header' => json_encode($header),
                                    'body' => json_encode($parameter),
                                    'response' => $result['response'],
                                    'url' => $url,
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                
                                $doc = json_decode($result['response']);
                                if(isset($doc->response_code) && in_array($doc->response_code, [1, 0])){
                                    $update['status'] = "success";
                                    $update['description'] =  "Billpayment Accepted";
                                }elseif(isset($doc->response_code) && in_array($doc->response_code, [5,14,16,6,17,8,18,12,9,13,14,15,16,17,18,10,11, 2])){
                                    $update['status'] = "failed";
                                    $update['refno'] =  $doc->message;
                                    $update['description'] =  $doc->message;
                                    if($doc->message == "Insufficient fund in your account. Please topup your wallet before initiating transaction."){
                                        $update['refno'] =  "Service down for sometime";
                                        $update['description'] =  $doc->message;
                                    }
                                }else{
                                    $update['status'] = "pending";
                                    $update['refno']  = "Please wait for status change or contact service provider";
                                    $update['description'] =  $doc->message;
                                }
                                break; 
                            
                            default:
                                if(isset($doc->ResponseCode)){
                                    if($doc->ResponseCode == "000"){
                                        $update['status'] = "success";
                                        $update['refno']  = isset($doc->TransactionRefId) ? $doc->TransactionRefId : "Success";
                                        $update['payid']  = isset($doc->TransactionId) ? $doc->TransactionId : "Success";
                                        $update['option3']= isset($doc->PaymentRefId) ? $doc->PaymentRefId : "Success";
                                        $update['description'] = "Billpayment Accepted";
                                    }else{
                                        $update['status'] = "failed";
                                        if($doc->ResponseCode == "Insufficient Wallet Balance"){
                                            $update['description'] = "Service down for sometime.";
                                        }else{
                                            $update['description'] = (isset($doc->ResponseMessage)) ? $doc->ResponseMessage : "failed";
                                        }
                                    }
                                }else{
                                    $update['status'] = "pending";
                                    $update['payid'] = "pending";
                                    $update['description'] = "billpayment pending";
                                }
                                break;
                        }
                    }

                    if($update['status'] == "success" || $update['status'] == "pending"){
                        Report::where('id', $report->id)->update($update);
                        \Myhelper::commission($report);
                        $output['statuscode'] = "TXN";
                        $output['message'] = "Billpayment Request Submitted";
                    }else{
                        User::where('id', $user->id)->increment('mainwallet', $post->debitAmount);
                        Report::where('id', $report->id)->update($update);
                        $output['statuscode'] = "TXF";
                        $output['message'] = $update['description'];    
                    }
                    $output['txnid']   = $post->txnid;
                    $output['rrn']     = $post->txnid;
                    return response()->json($output);
                }else{
                    return response()->json(['statuscode'=> "ERR" , "message" => 'Transaction Failed, please try again.']);
                }
                break;
        }
    }

    public function status(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  => 'required|numeric',
            'txnid'    => 'required|numeric'
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

        if (!\Myhelper::can('billpayment_status', $user->id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Service Not Allowed"]);
        }

        $report = Report::where('id', $post->txnid)->first();

        if(!$report || !in_array($report->status , ['pending', 'success'])){
            return response()->json(['status' => "Recharge Status Not Allowed1"], 200);
        }
       // dd($report->api->code);
        switch ($report->api->code) {
            case 'billpayment':
                $url = $report->api->url.'/status?token='.$report->api->username.'&apitxnid='.$report->txnid;
                $method = "GET";
                $parameter = "";
                $header = [];
                break;
        	case 'paysprintbill':
        //	$url = $report->api->url."bill/status";
         // 	$url = 'https://paysprint.in/service-api/api/v1/service/bill-payment/bill/status' ;
         //  $url = $report->api->url."bill/status";
         	$url = 'https://api.paysprint.in/api/v1/service/bill-payment/bill/status';
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
             "Authorisedkey: ".$report->api->optional1
            );
    	break;    
            default:
                return response()->json(['statuscode' => "ERR", "message" => "Recharge Status Not Allowed2"]);
                break;
        }

       

        if (env('APP_ENV') != "local") {
                $result = \Myhelper::curl($url, $method, $parameter, $header);
            }else{
                $result = [
                    'error' => false,
                    'response' => json_encode([
                        'statuscode' => 'TXN',
                        'message'=> 'local',
                        'data' => ['status'=> 'success', 'ref_no' => 'local']
                    ]) 
                ];
            }
        if($result['response'] != ''){
            switch ($report->api->code) {
                case 'billpayment':
                $doc = json_decode($result['response']);
               
                if($doc->statuscode == "TXN" && ($doc->data->status =="success" || $doc->data->status =="pending")){
                    $update['refno'] = $doc->data->ref_no;
                    $update['status'] = "success";

                    $output['statuscode'] = "TXN";
                    $output['txn_status'] = "success";
                    $output['message'] = $doc->message;
                    $output['refno'] = $doc->data->ref_no;

                }elseif($doc->statuscode == "TXN" && $doc->data->status =="reversed"){
                    $update['status'] = "reversed";
                    $update['refno'] = $doc->data->refno;

                    $output['statuscode'] = "TXR";
                    $output['txn_status'] = "reversed";
                    $output['message'] = $doc->message;
                    $output['refno'] = $doc->data->refno;
                }else{
                    $update['status'] = "Unknown";
                    $update['refno'] = $doc->message;

                    $output['statuscode'] = "TNF";
                    $output['txn_status'] = "unknown";
                    $output['message'] = $doc->message;
                    $output['refno'] = $doc->message;
                }
                break;
                
            	case 'paysprintbill':
            	          $doc = json_decode($result['response']);
						    \DB::table('rp_log')->insert([
                                'ServiceName' => "BillpayStatus",
                                'header' => json_encode($header),
                                'body' => json_encode($parameter),
                                'response' => $result['response'],
                                'url' => $url,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                            
							if(isset($doc->response_code) && in_array($doc->response_code, [1])){
                                $update['status'] = "success";
                                $update['refno'] =  $doc->data->operatorid;
                                
                                $output['statuscode'] = "TXN";
                                $output['txn_status'] = "success";
                                $output['message'] = $doc->message;
                                $output['refno'] = $doc->data->operatorid;
                            }elseif(isset($doc->response_code) && in_array($doc->response_code, [2])){
                                $update['status'] = "reversed";
                                $update['refno'] =  $doc->message;
                                
                               $output['statuscode'] = "TXR";
                               $output['txn_status'] = "reversed";
                               $output['message'] = $doc->message;
                               $output['refno'] = $doc->data->operatorid;
                            }else{
                                $update['status'] = "Unknown";
                                $update['refno']  = "Please wait for status change or contact service provider";
                                
                                $output['statuscode'] = "TNF";
                                $output['txn_status'] = "unknown";
                                $output['message'] = $doc->message ?? "Status Not Found";
                                $output['refno'] = $doc->message ?? '';   
                                
                            }
            }
            $product = "billpay";

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
}
