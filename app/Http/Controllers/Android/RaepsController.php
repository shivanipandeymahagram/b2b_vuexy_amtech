<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api;
use Carbon\Carbon;
use App\User;
use App\Models\Aepsreport;
use App\Models\Provider;
use App\Models\Aepsaccount;
use App\Models\Aepsuser;
use App\Models\Circle;
use Illuminate\Validation\Rule;

use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class RaepsController extends Controller
{
    protected $api;
    public function __construct()
    {
        $this->api = Api::where('code', 'raeps')->first();
    }
    
    public function getdata(Request $post){
        $data['agent'] = Aepsuser::where('user_id', $post->user_id)->first();
       // $data['mahastate'] = Circle::all();
        $data['bankName'] = \DB::table('fingaepsbanks')->get();
        return response()->json(["statuscode" => "TXN", 'message' => "data fetched successfully",'data'=>$data]);
    }
    
   public function getkyc(Request $post){
         $rules = array(
                    'user_id'      => 'required|numeric'
                    );
          $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }            
        $user = \App\User::where('id',$post->user_id)->first();
        if(!$user){
             return response()->json(["statuscode" => "TXF", 'message' => "User Not Found"]);
        }
        
        $aepsuser = Aepsuser::where('user_id', $post->user_id)->first();
        if(isset($aepsuser->status) && $aepsuser->status == "approved" || isset($aepsuser->status)  && $aepsuser->status == "success"){
              $aepsmerchent = "true";    
            }else{
              $aepsmerchent = "false";   
            }
        if(!$aepsuser){
          $agentId =  "MB".date('ymd').$user->id; 
         $action = Aepsuser::updateOrCreate(['user_id'=> $user->id], [
                    "merchantLoginId" => $agentId,
                    "merchantName" => $user->name,
                    "merchantEmail" => $user->email,
                    "merchantPhoneNumber" => $user->mobile,
                    "merchantShopname" => $user->shopname,
                    'status' => 'pending',
                    'user_id' => $user->id
                ]);    
        }else{
          $agentId =  $aepsuser->merchantLoginId ; 
          
        }
        $gpsdata = geoip($post->ip());
        $data['pId'] = $this->api->username;
        $data['pApiKey'] = $this->api->password;
        $data['mCode'] = $agentId ;
        $data['mobile'] = $user->mobile;
        $data['firm'] = $user->shopname;
        $data['email'] = $user->email;
        $data['lat'] = $gpsdata->lon;
        $data['lng'] = $gpsdata->lat;
    
        return response()->json(["statuscode" => "TXN",'pasprintonboard'=>$aepsmerchent, 'message' => "KYC deatils fetched successfully",'data'=>$data]);
    }

    public function trasaction(Request $post)
    {
        // if (\Myhelper::hasRole('admin') || !\Myhelper::can('aeps_service')) {
        //     return response()->json(["statuscode" => "ERR", 'status' => "Permission not allowed"]);
        // }

        if(!$this->api || $this->api->status == 0){
            return response()->json(["statuscode" => "ERR", 'status' => "R-Aeps Service Currently Down."]);
        }

        $user = User::where('id', $post->user_id)->first();
        $agent = Aepsuser::where('user_id', $post->user_id)->first();

        switch ($post->transactionType) {
            case 'BE':
            case "MS":
                $rules = array(
                    'user_id'      => 'required|numeric',
                    'mobileNumber' => 'required|numeric|digits:10',
                    'adhaarNumber' => 'required|numeric|digits:12',
                    "txtPidData"   => "required",
                    'bankid'       => 'required|numeric'
                );
            break;

            case 'CW':
            case "M":
                $rules = array(
                    'user_id'      => 'required|numeric',
                    'mobileNumber' => 'required|numeric|digits:10',
                    'adhaarNumber' => 'required|numeric|digits:12',
                    "txtPidData"   => "required",
                    'bankid'       => 'required|numeric',
                    'transactionAmount' => 'required|numeric|min:100'
                );
            break;

            case 'getbanks':
            case 'getaepsfundbanks':
                $rules = array('user_id' => 'required|numeric');
            break;

            default:
                return response()->json(['statuscode' => "ERR", "message" => "Invalid Transaction Type"]);
            break;
        }
        
        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        
        $gpsdata = geoip($post->ip());
        $token = $this->getToken($post->user_id.Carbon::now()->timestamp);

        switch ($post->transactionType) {
            case "getbanks":
                $url = $this->api->url."settlement/addaccount/banklist";
                $parameter['longitude'] = $gpsdata->lon;
                $parameter['latitude']  = $gpsdata->lat;

                $key = $this->api->optional2;
                $iv  = $this->api->optional3;
                $cipher   = openssl_encrypt(json_encode($parameter,true), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                $request  = base64_encode($cipher);
                $request  = array('body'=>$request);
                break;

            case "getaepsfundbanks":
                $banks = \DB::table('aepssettlebanks')->get();
                return response()->json(['statuscode' => 'TXN', 'message' => 'Bank List Fetched Successfully', 'data' => $banks]);
                break;

            case "BE":
            case "MS":
                $post['transactionAmount'] = 0;
                $bank = \DB::table('fingaepsbanks')->where('iinno', $post->bankid)->first();
                do {
                    $post['txnid'] = rand(111,9999999999);
                } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport);
                 
                 
                if($post->transactionType == "MS"){
                    $provider = Provider::where('recharge1', 'aepsmini')->first(); 
                      $post['provider_id'] = $provider->id ?? 0;
                     $post['charge'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id, $post->provider_id, $user->role->slug);
                }
                   
                 $insert = [
                        "mobile" => $post->mobileNumber,
                        "aadhar" => "XXXXXXXX".substr($post->adhaarNumber, -4),
                        "txnid"  => $post->txnid,
                        "amount" => 0,
                        "charge" => $post->charge ?? 0,
                        "gst"    => 0,
                        "tds"     => 0,
                        "bank"    => $bank->bankName,
                        "user_id" => $user->id,
                        'aepstype'=> $post->transactionType,
                        'authcode'=> $post->device,
                        'status'  => 'pending',
                        'credited_by' => $user->id,
                        'type' => 'credit',
                        'balance' => $user->aepsbalance,
                        'provider_id' => $post->provider_id ?? 0,
                        'api_id' => $this->api->id,
                        'product' => 'aeps'
                    ];
                   try {
                        $report = Aepsreport::create($insert);
                    } catch (\Exception $e) {
                        return response()->json(['status' => "ERR", "message" => "Technical Issue, Try Again"]);
                    }
                if($post->transactionType == "BE"){
                    $url = $this->api->url."aeps/balanceenquiry/index";
                }else{
                    $url = $this->api->url."aeps/ministatement/index";
                }
                $parameter['timestamp'] = Carbon::now()->format('d/m/Y h:i:s');
                $parameter['transactiontype'] = $post->transactionType;
                $parameter['longitude'] = $gpsdata->lon;
                $parameter['latitude'] = $gpsdata->lat;
                $parameter['nationalbankidentification'] = $bank->iinno;
                $parameter['requestremarks'] = "Aeps";
                $parameter['mobilenumber'] = $post->mobileNumber;
                $parameter['adhaarnumber'] = $post->adhaarNumber;
                $parameter['data'] = $post->txtPidData;
                $parameter['referenceno'] = $post->txnid;
                $parameter['accessmodetype'] = "APP";
                $parameter['ipaddress'] = $post->ip();
                $parameter['pipe'] = "bank1";
                $parameter['submerchantid'] = $agent->merchantLoginId;
                $parameter['is_iris'] = false;

                $key = $this->api->optional2;
                $iv  = $this->api->optional3;
                $cipher   = openssl_encrypt(json_encode($parameter,true), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                $request  = base64_encode($cipher);
                $request  = array('body'=>$request);
                
                
                
                break;

            case "CW":
            case "M":
                $bank = \DB::table('fingaepsbanks')->where('iinno', $post->bankid)->first();
                do {
                    $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport);
        
                if($post->transactionType == "CW" || $post->transactionType == "M"){
                    if($post->transactionType == "CW"){
                        // if($post->transactionAmount > 99 && $post->transactionAmount <= 499){
                        //     $provider = Provider::where('recharge1', 'aeps1')->first();
                        // }elseif($post->transactionAmount>499 && $post->transactionAmount<=999){
                        //     $provider = Provider::where('recharge1', 'aeps1')->first();
                        // }elseif($post->transactionAmount>999 && $post->transactionAmount<=1499){
                        //     $provider = Provider::where('recharge1', 'aeps3')->first();
                        // }elseif($post->transactionAmount>1499 && $post->transactionAmount<=1999){
                        //     $provider = Provider::where('recharge1', 'aeps4')->first();
                        // }elseif($post->transactionAmount>1999 && $post->transactionAmount<=2499){
                        //     $provider = Provider::where('recharge1', 'aeps5')->first();
                        // }elseif($post->transactionAmount>2499 && $post->transactionAmount<=2999){
                        //     $provider = Provider::where('recharge1', 'aeps6')->first();
                        // }elseif($post->transactionAmount>2999 && $post->transactionAmount<=3499){
                        //     $provider = Provider::where('recharge1', 'aeps7')->first();
                        // }elseif($post->transactionAmount>3499 && $post->transactionAmount<=7999){
                        //     $provider = Provider::where('recharge1', 'aeps8')->first();
                        // }elseif($post->transactionAmount>7999 && $post->transactionAmount<=10000){
                        //     $provider = Provider::where('recharge1', 'aeps9')->first();
                        // }
                        
                        if($post->transactionAmount > 499 && $post->transactionAmount <= 999){
                            $provider = Provider::where('recharge1', 'aeps1')->first();
                        }elseif($post->transactionAmount>999 && $post->transactionAmount <= 1499){
                            $provider = Provider::where('recharge1', 'aeps2')->first();
                        }elseif($post->transactionAmount > 1499 && $post->transactionAmount <= 1999){
                            $provider = Provider::where('recharge1', 'aeps3')->first();
                        }elseif($post->transactionAmount > 1999 && $post->transactionAmount <= 2499){
                            $provider = Provider::where('recharge1', 'aeps4')->first();
                        }elseif($post->transactionAmount > 2499 && $post->transactionAmount <= 2999){
                            $provider = Provider::where('recharge1', 'aeps5')->first();
                        }elseif($post->transactionAmount > 2999 && $post->transactionAmount <= 5999){
                            $provider = Provider::where('recharge1', 'aeps6')->first();
                        }elseif($post->transactionAmount > 5999 && $post->transactionAmount <= 10000){
                            $provider = Provider::where('recharge1', 'aeps7')->first();
                        }
                        
                        $post['provider_id'] = $provider->id ?? 0;
                        if($post->transactionAmount >= 500){
                            $post['charge'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id, $post->provider_id, $user->role->slug);
                        }else{
                            $post['charge'] = 0;
                        }
                    }else{
                        if($post->transactionAmount > 0 && $post->transactionAmount <= 10000){
                            $provider = Provider::where('recharge1', 'aadharpay')->first();
                        }
                        
                        $post['provider_id'] = $provider->id;
                        if($post->transactionAmount > 0){
                            $post['charge'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id, $post->provider_id, $user->role->slug);
                        }else{
                            $post['charge'] = 0;
                        }
                    }
                    
                    $insert = [
                        "mobile" => $post->mobileNumber,
                        "aadhar" => "XXXXXXXX".substr($post->adhaarNumber, -4),
                        "txnid"  => $post->txnid,
                        "amount" => $post->transactionAmount,
                        "charge" => $post->charge,
                        "bank"   => $bank->bankName,
                        "user_id"=> $user->id,
                        'aepstype'=> $post->transactionType,
                        'authcode'=> $post->device,
                        'status'  => 'pending',
                        'credited_by' => $user->id,
                        'type' => 'credit',
                        'balance' => $user->aepsbalance,
                        'provider_id' => $post->provider_id,
                        'api_id' => $this->api->id,
                        'product' => 'aeps'
                    ];
                    
                    if($post->transactionType == "M"){
                        $insert['product'] = "aadharpay";
                    }
                    try {
                        $report = Aepsreport::create($insert);
                    } catch (\Exception $e) {
                        return response()->json(['status' => "ERR", "message" => "Technical Issue, Try Again"]);
                    }
                }
                
                if($post->transactionType == "CW"){
                    $url = $this->api->url."aeps/cashwithdraw/index";
                }else{
                    $url = $this->api->url."aadharpay/aadharpay/index";
                }

                do {
                    $post['txnid'] = rand(111,9999999999);
                } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport);
                
                $parameter['timestamp'] = Carbon::now()->format('d/m/Y h:i:s');
                $parameter['transactiontype'] = $post->transactionType;
                $parameter['longitude'] = $gpsdata->lon;
                $parameter['latitude'] = $gpsdata->lat;
                $parameter['nationalbankidentification'] = $bank->iinno;
                $parameter['requestremarks'] = "Aeps";
                $parameter['mobilenumber'] = $post->mobileNumber;
                $parameter['adhaarnumber'] = $post->adhaarNumber;
                $parameter['data'] = $post->txtPidData;
                $parameter['referenceno'] = $post->txnid;
                $parameter['accessmodetype'] = "APP";
                $parameter['amount'] = $post->transactionAmount;
                $parameter['ipaddress'] = $post->ip();
                $parameter['pipe'] = "bank1";
                $parameter['submerchantid'] = $agent->merchantLoginId;
                $parameter['is_iris'] = false;

                $key = $this->api->optional2;
                $iv  = $this->api->optional3;
                $cipher   = openssl_encrypt(json_encode($parameter,true), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                $request  = base64_encode($cipher);
                $request  = array('body' => $request);
                break;
        }

        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/x-www-form-urlencoded",
            "Token: ".$token['token'],
            "Authorisedkey: ".$this->api->optional1
        );

        if(env('APP_ENV') == "local"){
            $result = array(
                "error" => false,
                "response" => json_encode([
                    "status" => true,
                    "message" => "Request Completed",
                    "ackno" => "local",
                    "amount" => $post->amount,
                    "balanceamount" => 0,
                    "bankrrn" => "local",
                    "response" => 200
                ])
            );
        }else{
            $result = \Myhelper::curl($url, "POST", http_build_query($request), $header, "yes", "App/Model/Aepsreport", $post->txnid);
        }
        //dd([$url, $token['payload'], $parameter, $request, $header, $result]);
        if($post->transactionType == "MS"){
            //dd([$url, $token['payload'], $parameter, $request, $header, $result]);
        }
        
        if(isset($result['response']) && $result['response'] != ''){
            $response = json_decode($result['response']);
            switch ($post->transactionType) {
                case 'getbanks':
                    $banks = \DB::table('aepsbanks')->get();
                    return response()->json(['statuscode' => 'TXN', 'message' => 'Bank List Fetched Successfully', 'data' => $banks]);
                    break;
                    
                case "BE":
                case "MS":
                    if(isset($response->status) && $response->status == true){
                        $outputdata['status'] = "success";
                        $outputdata['message'] = isset($response->message) ? $response->message : "Transaction Successfull";
                        $outputdata['id'] = isset($response->ackno) ? $response->ackno : "Not Found";
                        $outputdata['balance'] = isset($response->balanceamount) ? $response->balanceamount : "0";
                        $outputdata['rrn'] = isset($response->bankrrn) ? $response->bankrrn : "Not Found";
                        $outputdata['amount'] = $post->transactionAmount;
                        $outputdata['aadhar'] = "XXXXXXXX".substr($post->adhaarNumber, -4);
                        $outputdata['bank'] = $bank->bankName;
                        $outputdata['transactionType'] = $post->transactionType;
                        $outputdata['created_at'] = isset($report->created_at)?$report->created_at: date('d M Y H:i');
                        if($post->transactionType == "BE"){
                            $outputdata['title'] = "Balance Enquiry";
                        }else{
                            $outputdata['title'] = "Mini Statement";
                        }
                        $outputdata['data'] = isset($response->ministatement) ? $response->ministatement : [];
                        if($post->transactionType == "MS"){
                            User::where('id', $user->id)->increment('aepsbalance', $post->charge);
                        }    
                        // $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                        // $url = $this->api->url."aeps/threeway/threeway";
                        // $parameter['reference'] = $post->txnid;
                        // $parameter['status']    = "success";
        
                        // $key = $this->api->optional2;
                        // $iv  = $this->api->optional3;
                        // $cipher   = openssl_encrypt(json_encode($parameter,true), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                        // $request  = base64_encode($cipher);
                        // $request  = array('body'=>$request);
                        
                        // $header = array(
                        //     "Cache-Control: no-cache",
                        //     "Content-Type: application/json",
                        //     "Token: ".$token['token']
                        // );
                        // $result = \Myhelper::curl($url, "POST", json_encode($request), $header, "yes", "App/Model/Aepsreport", $post->txnid);
                    }else{
                        $outputdata['status'] = "failed";
                        $outputdata['message'] = isset($response->message) ? $response->message : "Transaction Failed";
                        $outputdata['id'] = isset($response->ackno) ? $response->ackno : "failed";
                        $outputdata['balance'] = isset($response->balanceamount) ? $response->balanceamount : "0";
                        $outputdata['rrn'] = isset($response->bankrrn) ? $response->bankrrn : "failed";
                        $outputdata['amount'] = $post->transactionAmount;
                        $outputdata['aadhar'] = "XXXXXXXX".substr($post->adhaarNumber, -4);
                        $outputdata['bank'] = $bank->bankName;
                        $outputdata['amount'] = $post->transactionAmount;
                        $outputdata['transactionType'] = $post->transactionType;
                        $outputdata['created_at'] = isset($report->created_at)?$report->created_at: date('d M Y H:i');
                        if($post->transactionType == "BE"){
                            $outputdata['title'] = "Balance Enquiry";
                        }else{
                            $outputdata['title'] = "Mini Statement";
                        }
                        $outputdata['data'] = [];
                        
                        // $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                        // $url = $this->api->url."aeps/threeway/threeway";
                        // $parameter['reference'] = $post->txnid;
                        // $parameter['status']    = "failed";
        
                        // $key = $this->api->optional2;
                        // $iv  = $this->api->optional3;
                        // $cipher   = openssl_encrypt(json_encode($parameter,true), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                        // $request  = base64_encode($cipher);
                        // $request  = array('body'=>$request);
                        
                        // $header = array(
                        //     "Cache-Control: no-cache",
                        //     "Content-Type: application/json",
                        //     "Token: ".$token['token']
                        // );
                        // $result = \Myhelper::curl($url, "POST", json_encode($request), $header, "yes", "App/Model/Aepsreport", $post->txnid);
                    }
                    return response()->json($outputdata);
                    break;
                    
                case "CW":
                case "M":
                    if(isset($response->status) && $response->status == true){
                        $update['status'] = "success";
                        $update['payid'] = isset($response->ackno) ? $response->ackno : "pending";
                        $update['refno'] = isset($response->bankrrn) ? $response->bankrrn : "pending";
                        $update['remark'] = isset($response->balanceamount) ? $response->balanceamount : "0";
                        Aepsreport::where('id', $report->id)->update($update);
                        
                        if($post->transactionType == "CW"){
                            User::where('id', $user->id)->increment('aepsbalance', $post->transactionAmount + $post->charge);
                        }else{
                            User::where('id', $post->user_id)->increment('aepsbalance', $post->transactionAmount - $post->charge);
                        }
                        
                         if($post->transactionAmount > 99 && $post->transactionType == "CW"){
                            \Myhelper::commission(Aepsreport::where('id', $report->id)->first());
                        }
                        
                        $outputdata['status'] = "success";
                        $outputdata['message'] = isset($response->message) ? $response->message : "Transaction Successfull";
                        $outputdata['id'] = isset($response->ackno) ? $response->ackno : "Not Found";
                        $outputdata['balance'] = isset($response->balanceamount) ? $response->balanceamount : "0";
                        $outputdata['rrn'] = isset($response->bankrrn) ? $response->bankrrn : "Not Found";
                        $outputdata['amount'] = $post->transactionAmount;
                        $outputdata['aadhar'] = "XXXXXXXX".substr($post->adhaarNumber, -4);
                        $outputdata['bank'] = $bank->bankName;
                        $outputdata['transactionType'] = $post->transactionType;
                        $outputdata['created_at'] = isset($report->created_at)?$report->created_at: date('d M Y H:i');
                        if($post->transactionType == "CW"){
                            $outputdata['title'] = "Cash Withdrawal";
                        }else{
                            $outputdata['title'] = "Aadhar Pay";
                        }
                        // $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                        // $url = $this->api->url."aeps/threeway/threeway";
                        // $parameter['reference'] = $post->txnid;
                        // $parameter['status']    = "success";
        
                        // $key = $this->api->optional2;
                        // $iv  = $this->api->optional3;
                        // $cipher   = openssl_encrypt(json_encode($parameter,true), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                        // $request  = base64_encode($cipher);
                        // $request  = array('body'=>$request);
                        
                        // $header = array(
                        //     "Cache-Control: no-cache",
                        //     "Content-Type: application/json",
                        //     "Token: ".$token['token']
                        // );
                        // $result = \Myhelper::curl($url, "POST", json_encode($request), $header, "yes", "App/Model/Aepsreport", $post->txnid);
                        
                    }else{
                        $update['status'] = "failed";
                        $update['payid'] = isset($response->ackno) ? $response->ackno : "failed";
                        $update['refno'] = isset($response->message) ? $response->message : "failed";
                        $update['remark'] = isset($response->balanceamount) ? $response->balanceamount : "0";
                        Aepsreport::where('id', $report->id)->update($update);
                        
                        $outputdata['status'] = "failed";
                        $outputdata['message'] = isset($response->message) ? $response->message : "Transaction Failed";
                        $outputdata['id'] = isset($response->ackno) ? $response->ackno : "Not Found";
                        $outputdata['balance'] = isset($response->balanceamount) ? $response->balanceamount : "0";
                        $outputdata['rrn'] = isset($response->bankrrn) ? $response->bankrrn : "Not Found";
                        $outputdata['amount'] = $post->transactionAmount;
                        $outputdata['aadhar'] = "XXXXXXXX".substr($post->adhaarNumber, -4);
                        $outputdata['bank'] = $bank->bankName;
                        $outputdata['transactionType'] = $post->transactionType;
                        $outputdata['created_at'] = isset($report->created_at)?$report->created_at: date('d M Y H:i');
                        if($post->transactionType == "CW"){
                            $outputdata['title'] = "Cash Withdrawal";
                        }else{
                            $outputdata['title'] = "Aadhar Pay";
                        }
                    }

                    Aepsreport::where('id', $report->id)->update($update);
                    return response()->json($outputdata);
                    break;
            }
        }
    }

    public function setbank($data)
    {
        \DB::table('aepssettlebanks')->delete();
        foreach ($data as $data) {
            $insert['bankname'] = $data->bankname;
            $inserts[] = $insert;
        }
        \DB::table('aepssettlebanks')->insert($inserts);
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
    
  
}
