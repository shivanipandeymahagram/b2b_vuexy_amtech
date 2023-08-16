<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api;
use App\User;
use App\Models\Aepsreport;
use App\Models\Balanceinqury;
use App\Models\Fingagent;
use App\Models\Mahastate;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use App\Models\Provider;
use App\Models\Fingtempdata;
use App\Models\Fingaepsbank;
use App\Models\Fingaadharpaybank;

class FingpayController extends Controller
{
    public function index()
    { 
        $agent = Fingagent::where('user_id', \Auth::id())->first();
        $data['agent'] = $agent;
        $data['mahastate'] = Mahastate::get();
        $data['bankName']  = Fingaepsbank::get();
        $data['bankName1'] = Fingaadharpaybank::get();
        return view('service.iaeps')->with($data);
    }
    public function ekycdet(Request $get, $id=0){
        $agent = Fingagent::where('user_id',$id)->first();
        //dd($agent->merchantName);
        $data['agent'] = $agent;
        $data['mahastate'] = Mahastate::get();
        $data['bankName']  = Fingaepsbank::get();
        $data['bankName1'] = Fingaadharpaybank::get();
        return view('profile.ekyc')->with($data);
    }
   
    public function transaction(Request $post)
    {        
        //dd('hello');
        $post['user_id'] = \Auth::id();
        $user = User::where('id', $post->user_id)->first(); 
        
        switch ($post->transactionType) {
            case 'useronboard':
                $fingdata = Fingagent::where('user_id', $post->user_id)->where('status', 'rejected')->first();
                
                if($fingdata){
                    try {
                        \Storage::deleteDirectory('kyc/useronboard'.$post->user_id);
                    } catch (\Exception $e) {}
                    
                    Fingagent::where('user_id', $post->user_id)->where('status', 'rejected')->delete();
                }
                
                $rules = array(
                    'merchantName'    => 'required',
                    'merchantAddress' => 'required',
                    'merchantState'   => 'required',
                    'father'   => 'required',
                    'thana'   => 'required',
                    'dob'   => 'required',
                    'merchantalernativeNumber'   => 'required',
                    'merchantCityName'    => 'required',
                    'merchantPhoneNumber' => 'required|numeric|digits:10|unique:fingagents,merchantPhoneNumber',
                    'merchantAadhar'      => 'required|numeric|digits:12|unique:fingagents,merchantAadhar',
                    'userPan'         => 'required|unique:fingagents,userPan',
                    'merchantPinCode' => 'sometimes|numeric|digits:6',
                    'aadharPics'   => 'required|mimes:jpg,jpeg,pdf|max:1024',
                    'pancardPics'  => 'required|mimes:jpg,jpeg,pdf|max:1024',
                    'passports'  => 'required|mimes:jpg,jpeg,pdf|max:1024',
                    'shoppics'  => 'required|mimes:jpg,jpeg,pdf|max:1024',
                );
                break;

            case 'useronboarded':
                $rules = array(
                    'id'    => 'required',
                );
                break;
                
            case 'useronboardvalidate':
                $rules = array(
                    'transactionType'   => 'required',
                    'primaryKeyId'      => 'required',
                    'encodeFPTxnId'     => 'required',
                    'otp'    => 'required',
                );
                break;
                
            case 'useronboardotp':
                $rules = array(
                    'transactionType'    => 'required',
                );
                break;
                
            case 'useronboardekyc':
                $rules = array(
                    'transactionType' => 'required',
                    'primaryKeyId'    => 'required',
                    'encodeFPTxnId'   => 'required',
                    'biodata'         => 'required'
                );
                break;

            case 'BE':
            case 'MS':
                $post['transactionAmount'] = 0;
                $rules = array(
                    'transactionType' => 'required',
                    'mobileNumber'    => 'required|numeric|digits:10',
                    'adhaarNumber'    => 'required|numeric|digits:12',
                    'bankName1'       => 'required',
                    'txtPidData'      => 'required'
                );
                break;

            case 'CW':
                $rules = array(
                    'transactionType' => 'required',
                    'mobileNumber'    => 'required|numeric|digits:10',
                    'adhaarNumber'    => 'required|numeric|digits:12',
                    'bankName1'       => 'required',
                    'txtPidData'      => 'required',
                    'transactionAmount' => 'required|numeric|min:1|max:10000'
                );
                break;
                
            case 'M':
                $rules = array(
                    'transactionType' => 'required',
                    'mobileNumber'    => 'required|numeric|digits:10',
                    'adhaarNumber'    => 'required|numeric|digits:12',
                    'bankName2'       => 'required',
                    'txtPidData'      => 'required',
                    'transactionAmount' => 'required|numeric|min:1|max:10000'
                );
                break;
            
            default:
                return response()->json(['status' => "ERR", "message" => "Invalid Transaction Type"]);
                break;
        }
                
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(['status'=>'ERR', 'message'=> $error]);
        }

        switch ($post->transactionType) {
            case 'useronboard':
                do {
                    $post['merchantLoginId']  = "EPM".rand(1111111111, 9999999999);
                } while (Fingagent::where("merchantLoginId", "=", $post->merchantLoginId)->first() instanceof Fingagent);

                do {
                    $post['merchantLoginPin'] = "EPMP".rand(111111, 999999);
                } while (Fingagent::where("merchantLoginPin", "=", $post->merchantLoginPin)->first() instanceof Fingagent);

                try {
                    \Storage::deleteDirectory('kyc/useronboard'.$post->user_id);
                } catch (\Exception $e) {}
                if($post->hasFile('aadharPics')){
                    $post['aadharPic'] = $post->file('aadharPics')->store('kyc/useronboard'.$post->user_id);
                }
                if($post->hasFile('pancardPics')){
                    $post['pancardPic'] = $post->file('pancardPics')->store('kyc/useronboard'.$post->user_id);
                }
                if($post->hasFile('passports')){
                    $post['passport'] = $post->file('passports')->store('kyc/useronboard'.$post->user_id);
                }
                if($post->hasFile('shoppics')){
                    $post['shoppic'] = $post->file('shoppics')->store('kyc/useronboard'.$post->user_id);
                }
                $post['status'] = "pending";
                $agent = Fingagent::create($post->all());
                if($agent){
                    return response()->json([
                        'status' => 'TXN', 
                        'message'=>'User onboard request submitted, wait for approval',
                        'merchantLoginId'  => $post->merchantLoginId,
                        'merchantLoginPin' => $post->merchantLoginPin
                    ]);

                }else{
                    return response()->json(['status' => 'ERR', 'message'=>'Something went wrong']);
                }
                break;

            case 'useronboarded':
                $agent = Fingagent::where('id', $post->id)->first();
                if(!$agent){
                    return response()->json(['status' => 'ERR', 'message'=>'Invalid Merchant']);
                }

                if($agent->status != "pending"){
                    return response()->json(['status' => 'ERR', 'message'=>'Merchant Already Onboarded']);
                }

                $apidata = Api::where('code','fingcms')->first();
                $post['superMerchantId'] = $apidata['optional1'];

                $sessionkey = '';
                $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
                foreach ($mt_rand as $chr)
                {             
                    $sessionkey .= chr($chr);         
                }

                $iv =   '06f2f04cc530364f';
                $fp = fopen(base_path().'/fingpay_public_production.txt','r');
                $publickey =fread($fp,8192);         
                fclose($fp);         
                openssl_public_encrypt($sessionkey,$crypttext,$publickey);
                $gpsdata       =  geoip($post->ip());

                $json =  [
                    "username" => $apidata['username'],
                    "password" => md5($apidata['password']),
                    "latitude"       => $gpsdata->lat,
                    "longitude"      => $gpsdata->lon,
                    "supermerchantId"=> $post->superMerchantId,
                    "merchants"      => [[
                        "merchantLoginId"     => $agent->merchantLoginId, 
                        "merchantLoginPin"    => $agent->merchantLoginPin,
                        "merchantName"        => $agent->merchantName,
                        "merchantPhoneNumber" => $agent->merchantPhoneNumber,
                        "merchantPinCode"     => $agent->merchantPinCode,
                        "merchantCityName"    => $agent->merchantCityName,
                        "merchantAddress"=> [
                            "merchantAddress" => $agent->merchantAddress,
                            "merchantState"   => $agent->merchantState
                        ],
                        "kyc"=> [
                            "userPan" => $agent->userPan
                        ]
                    ]],
                ];

                $header = [         
                    'Content-Type: text/xml',             
                    'trnTimestamp:'.date('d/m/Y H:i:s'),         
                    'hash:'.base64_encode(hash("sha256",json_encode($json), True)),   
                    'eskey:'.base64_encode($crypttext)         
                ];

                $url = "https://aepspay.tapits.in/fpaepsweb/api/onboarding/merchant/creation/php/m1";
                $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
                $request = base64_encode($ciphertext_raw);
                $result = \Myhelper::curl($url, 'POST', $request, $header, "yes", 'Fingagent',$agent->merchantLoginId);
                //dd($result);
                if($result['response'] == ''){
                    return response()->json(['status' => 'TUP', 'message'=>'User onboard pending']);
                }else{
                    $response = json_decode($result['response']);
                    if(isset($response->status) && $response->status == "true"){
                        Fingagent::where('id', $post->id)->update(['status'   => "approved"]);
                        return response()->json(['status' => 'TXN', 'message' =>'User onboard successfully']);
                    }else{
                        return response()->json(['status' => 'ERR', 'message' => isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                }
                break;
                
            case 'useronboardotp':
                $agent = Fingagent::where('user_id', $post->user_id)->first();
                if(!$agent){
                    return response()->json(['status' => 'ERR', 'message'=>'Invalid Merchant']);
                }

                if($agent->everify != "pending"){
                    return response()->json(['status' => 'ERR', 'message'=>'Merchant Already Verified']);
                }
 
                $apidata = Api::where('code', 'fingcms')->first();
                $post['superMerchantId'] = $apidata['optional1'];
                $sessionkey = '';
                $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
                foreach ($mt_rand as $chr)
                {             
                    $sessionkey .= chr($chr);         
                }

                $iv =   '06f2f04cc530364f';
                $fp =fopen("fingpay_public_production.txt","r");
                $publickey =fread($fp,8192);         
                fclose($fp);         
                openssl_public_encrypt($sessionkey,$crypttext,$publickey);
                $gpsdata       =  geoip($post->ip());

                $json =  [
                    "latitude"        => $gpsdata->lat,
                    "longitude"       => $gpsdata->lon,
                    "superMerchantId" => $post->superMerchantId,
                    "merchantLoginId" => $agent->merchantLoginId, 
                    "aadharNumber"    => $agent->merchantAadhar,
                    "panNumber"       => $agent->userPan,
                    "mobileNumber"    => $agent->merchantPhoneNumber,
                    "matmSerialNumber"=> "",
                    "transactionType" => 'EKY'
                ];

                $header = [         
                    'Content-Type: text/xml',             
                    'trnTimestamp:'.date('d/m/Y H:i:s'),         
                    'hash:'.base64_encode(hash("sha256",json_encode($json), True)),   
                    'eskey:'.base64_encode($crypttext),
                    'deviceIMEI:352801082418919'
                ];

                $url = 'https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/sendotp';
                $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
                $request = base64_encode($ciphertext_raw);
                $result = \Myhelper::curl($url, 'POST', $request, $header, "yes", 'Fingagent',$post->merchantLoginId);
                if($result['response'] == ''){
                    return response()->json(['status' => 'ERR', 'message'=>'Something went wrong, try again']);
                }else{
                    $response = json_decode($result['response']);
                    if(isset($response->status) && $response->status == "true"){
                        return response()->json(['status' => 'TXNOTP', 'message' => 'Otp Sent Successfully', "primaryKeyId" => $response->data->primaryKeyId, "encodeFPTxnId" => $response->data->encodeFPTxnId]);
                    }else{
                        return response()->json(['status' => 'ERR', 'message' => isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                }
                
                break;
                
            case 'useronboardvalidate':
                $agent = Fingagent::where('user_id', $post->user_id)->first();
                if(!$agent){
                    return response()->json(['status' => 'ERR', 'message'=>'Invalid Merchant']);
                }

                if($agent->everify != "pending"){
                    return response()->json(['status' => 'ERR', 'message'=>'Merchant Already Verified']);
                }
 
                $apidata = Api::where('code', 'fingcms')->first();
                $post['superMerchantId'] = $apidata['optional1'];
                $sessionkey = '';
                $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
                foreach ($mt_rand as $chr)
                {             
                    $sessionkey .= chr($chr);         
                }

                $iv =   '06f2f04cc530364f';
                $fp =fopen("fingpay_public_production.txt","r");
                $publickey =fread($fp,8192);         
                fclose($fp);         
                openssl_public_encrypt($sessionkey,$crypttext,$publickey);
                $gpsdata       =  geoip($post->ip());
                $json =  [
                    "superMerchantId" => $post->superMerchantId,
                    "merchantLoginId" => $agent->merchantLoginId, 
                    "primaryKeyId"    => $post->primaryKeyId,
                    "encodeFPTxnId"   => $post->encodeFPTxnId,
                    "otp"   => $post->otp,
                ];

                $header = [         
                    'Content-Type: text/xml',             
                    'trnTimestamp:'.date('d/m/Y H:i:s'),         
                    'hash:'.base64_encode(hash("sha256",json_encode($json), True)),   
                    'eskey:'.base64_encode($crypttext),
                    'deviceIMEI:352801082418919'
                ];

                $url = 'https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/validateotp';
                $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
                $request = base64_encode($ciphertext_raw);
                $result = \Myhelper::curl($url, 'POST', $request, $header, "yes", 'Fingagent',$post->merchantLoginId);
                if($result['response'] == ''){
                    return response()->json(['status' => 'ERR', 'message'=>'Something went wrong, try again']);
                }else{
                    $response = json_decode($result['response']);
                    if(isset($response->status) && $response->status == "true"){
                        return response()->json(['status' => 'TXN', 'message'=> 'Otp Sent Successfully']);
                    }else{
                        return response()->json(['status' => 'ERR', 'message'=> isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                }
                break;
                
            case 'useronboardekyc':
                $agent = Fingagent::where('user_id', $post->user_id)->first();
                if(!$agent){
                    return response()->json(['status' => 'ERR', 'message'=>'Invalid Merchant']);
                }

                if($agent->everify != "pending"){
                    return response()->json(['status' => 'ERR', 'message'=>'Merchant Already Verified']);
                }
                
                $apidata = Api::where('code', 'fingcms')->first();
                $post['superMerchantId'] = $apidata['optional1'];
                $sessionkey = '';
                $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
                foreach ($mt_rand as $chr)
                {             
                    $sessionkey .= chr($chr);         
                }

                $iv =   '06f2f04cc530364f';
                $fp =fopen("fingpay_public_production.txt","r");
                $publickey =fread($fp,8192);         
                fclose($fp);         
                openssl_public_encrypt($sessionkey,$crypttext,$publickey);

                try {
                    $biodata       =  str_replace("&lt;","<",str_replace("&gt;",">",$post->biodata));
                    $xml           =  simplexml_load_string($biodata);
                    $skeyci        =  (string)$xml->Skey['ci'][0];
                    $headerarray   =  json_decode(json_encode((array)$xml), TRUE);
                    
                    //dd($headerarray);
                } catch (\Exception $e) {
                    return response()->json(['status' => "ERR", "message" => $e->getMessage()]);
                }
                
                $json =  [
                    "captureResponse" => [
                        "PidDatatype" =>  "X",
                        "Piddata"     =>  $headerarray['Data'],
                        "ci"          =>  $skeyci,
                        "dc"          =>  $headerarray['DeviceInfo']['@attributes']['dc'],
                        "dpID"        =>  $headerarray['DeviceInfo']['@attributes']['dpId'],
                        "errCode"     =>  $headerarray['Resp']['@attributes']['errCode'],
                        "errInfo"     =>  isset($headerarray['Resp']['@attributes']['errInfo'])?$headerarray['Resp']['@attributes']['errInfo']:'',
                        "fCount"      =>  $headerarray['Resp']['@attributes']['fCount'],
                        "fType"       =>  $headerarray['Resp']['@attributes']['fType'],
                        "hmac"        =>  $headerarray['Hmac'],
                        "iCount"      =>  "0",
                        "iType"       =>  null,
                        "mc"          =>  $headerarray['DeviceInfo']['@attributes']['mc'],
                        "mi"          =>  $headerarray['DeviceInfo']['@attributes']['mi'],
                        "nmPoints"    =>  $headerarray['Resp']['@attributes']['nmPoints'],
                        "pCount"      =>  "0",
                        "pType"       =>  "0",
                        "qScore"      =>  $headerarray['Resp']['@attributes']['qScore'],
                        "rdsID"       =>  $headerarray['DeviceInfo']['@attributes']['rdsId'],
                        "rdsVer"      =>  $headerarray['DeviceInfo']['@attributes']['rdsVer'],
                        "sessionKey"  =>  $headerarray['Skey']
                    ],

                    "cardnumberORUID"       => [
                        'adhaarNumber'      => $agent->merchantAadhar,
                        "indicatorforUID"   => "0",
                        "nationalBankIdentificationNumber" => null
                    ],
                    "superMerchantId" => $post->superMerchantId,
                    "merchantLoginId" => $agent->merchantLoginId, 
                    "primaryKeyId"    => $post->primaryKeyId,
                    "encodeFPTxnId"   => $post->encodeFPTxnId,
                    "requestRemarks"  => "kyc"
                ];

                $header = [         
                    'Content-Type: text/xml',             
                    'trnTimestamp:'.date('d/m/Y H:i:s'),         
                    'hash:'.base64_encode(hash("sha256",json_encode($json), True)),   
                    'eskey:'.base64_encode($crypttext),
                    'deviceIMEI:352801082418919'
                ];

                $url = 'https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/biometric';
                $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
                $request = base64_encode($ciphertext_raw);
                $result = \Myhelper::curl($url, 'POST', $request, $header, "yes", 'Fingagent',$post->merchantLoginId);
                //dd([$url,$header, $json ,$result]);
                if($result['response'] == ''){
                    return response()->json(['status' => 'ERR', 'message'=>'Something went wrong, try again']);
                }else{
                    $response = json_decode($result['response']);
                    if(isset($response->status) && $response->status == "true"){
                        Fingagent::where('user_id', $post->user_id)->update(['everify' => 'success']);
                        return response()->json(['status' => 'TXN', 'message'=> 'E-kyc Successfully Completed']);
                    }else{
                        return response()->json(['status' => 'ERR', 'message'=> isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                }
                break;
        }

        $apidata = Api::where('code', 'fingcms')->first();
        $post['superMerchantId'] = $apidata['optional1'];
        
        $agent = Fingagent::where('user_id', $post->user_id)->first();
        if(!$agent){
            return response()->json(['status' => "ERR", "message" => "User Not Onboarded"]);
        }
        
        do {
            $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
        } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport);
        
        try {
            $biodata       =  str_replace("&lt;","<",str_replace("&gt;",">",$post->txtPidData));
            $xml           =  simplexml_load_string($biodata);
            $skeyci        =  (string)$xml->Skey['ci'][0];
            $headerarray   =  json_decode(json_encode((array)$xml), TRUE);
        } catch (\Exception $e) {
            return response()->json(['status' => "ERR", "message" => $e->getMessage()]);
        }
        
        $sessionkey = '';
        $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
        foreach ($mt_rand as $chr){             
            $sessionkey .= chr($chr);         
        }
        
        $iv = '06f2f04cc530364f';
        $fp = fopen(base_path().'/fingpay_public_production.txt','r');
        
        $publickey =fread($fp,8192);       
        fclose($fp);         
        openssl_public_encrypt($sessionkey,$crypttext,$publickey);
        $gpsdata       =  geoip($post->ip());
        
        switch ($post->transactionType) {
            case 'CW':
            case 'BE':
            case 'MS':
                $bank  = \DB::table('fingaepsbanks')->where('iinno', $post->bankName1)->first();
                break;

            case 'M':
                $bank  = \DB::table('fingaadharpaybanks')->where('iinno', $post->bankName2)->first();
                break;
        }
        
        $json =  [
            "captureResponse" => [
                "PidDatatype" =>  "X",
                "Piddata"     =>  $headerarray['Data'],
                "ci"          =>  $skeyci,
                "dc"          =>  $headerarray['DeviceInfo']['@attributes']['dc'],
                "dpID"        =>  $headerarray['DeviceInfo']['@attributes']['dpId'],
                "errCode"     =>  $headerarray['Resp']['@attributes']['errCode'],
                "errInfo"     =>  $headerarray['Resp']['@attributes']['errInfo'],
                "fCount"      =>  $headerarray['Resp']['@attributes']['fCount'],
                "fType"       =>  $headerarray['Resp']['@attributes']['fType'],
                "hmac"        =>  $headerarray['Hmac'],
                "iCount"      =>  "0",
                "mc"          =>  $headerarray['DeviceInfo']['@attributes']['mc'],
                "mi"          =>  $headerarray['DeviceInfo']['@attributes']['mi'],
                "nmPoints"    =>  $headerarray['Resp']['@attributes']['nmPoints'],
                "pCount"      =>  "0",
                "pType"       =>  "0",
                "qScore"      =>  $headerarray['Resp']['@attributes']['qScore'],
                "rdsID"       =>  $headerarray['DeviceInfo']['@attributes']['rdsId'],
                "rdsVer"      =>  $headerarray['DeviceInfo']['@attributes']['rdsVer'],
                "sessionKey"  =>  $headerarray['Skey']
            ],

            "cardnumberORUID"       => [
                'adhaarNumber'      => $post->adhaarNumber,
                "indicatorforUID"   => "0",
                "nationalBankIdentificationNumber" => $bank->iinno
            ],
            
            "languageCode"   => "en",
            "latitude"       => $gpsdata->lat,
            "longitude"      => $gpsdata->lon,
            "mobileNumber"   => $post->mobileNumber,
            "paymentType"    => "B",
            "requestRemarks" => "Aeps", 
            "timestamp"      => Carbon::now()->format('d/m/Y H:i:s'),
            "transactionType"   => $post->transactionType,
            "merchantUserName"  => $agent->merchantLoginId,
            "merchantPin"       => md5($agent->merchantLoginPin),               
            "subMerchantId"     => "",
            'merchantTransactionId' => $post->txnid,
            'transactionAmount' => $post->amount
        ];
        
        switch ($post->transactionType) {
            case 'BE':
                $url = $apidata['url'].'fpaepsservice/api/balanceInquiry/merchant/php/getBalance';
                $json["merchantTransactionId"] = $post->txnid;
                $json['transactionAmount'] = 0;
                $json['superMerchantId']   = $post->superMerchantId;
                break;

            case 'MS':
                $url = $apidata['url'].'fpaepsservice/api/miniStatement/merchant/php/statement';
                $json["merchantTranId"] = $post->txnid;
                break;

            case 'CW':
                $url = $apidata['url'].'fpaepsservice/api/cashWithdrawal/merchant/php/withdrawal';
                $json["transactionAmount"] = $post->transactionAmount;
                $json["merchantTranId"]    = $post->txnid;
                $json['superMerchantId']   = $post->superMerchantId;
                break;
                
            case 'M':
                $url = $apidata['url'].'fpaepsservice/api/aadhaarPay/merchant/php/pay';
                $json["transactionAmount"] = $post->transactionAmount;
                $json["merchantTranId"]    = $post->txnid;
                $json['superMerchantId']   = $post->superMerchantId;
                break;
        }
        
        if($post->device == "MANTRA_PROTOBUF"){
            $header = [         
                'Content-Type: text/xml',             
                'trnTimestamp:'.date('d/m/Y H:i:s'),         
                'hash:'.base64_encode(hash("sha256",json_encode($json), True)),         
                'deviceIMEI:'.$headerarray['DeviceInfo']['additional_info']['Param'][0]['@attributes']['value'],         
                'eskey:'.base64_encode($crypttext)         
            ];
        }else{
            $header = [         
                'Content-Type: text/xml',             
                'trnTimestamp:'.date('d/m/Y H:i:s'),         
                'hash:'.base64_encode(hash("sha256",json_encode($json), True)),         
                'deviceIMEI:'.$headerarray['DeviceInfo']['additional_info']['Param']['@attributes']['value'],         
                'eskey:'.base64_encode($crypttext)         
            ];
        }
        
        $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
        $request = base64_encode($ciphertext_raw);
        
        if($post->transactionType == "CW" || $post->transactionType == "M" || $post->transactionType == "MS"){
            if($post->transactionType == "CW"){
               
                if($post->transactionAmount >=100 && $post->transactionAmount <=499){
                    $provider = Provider::where('recharge1', 'aeps1')->first();
                }elseif($post->transactionAmount >=500 && $post->transactionAmount <=1000){
                    $provider = Provider::where('recharge1', 'aeps2')->first();
                }elseif($post->transactionAmount >=1001 && $post->transactionAmount <=2000){
                    $provider = Provider::where('recharge1', 'aeps3')->first();
                }elseif($post->transactionAmount >=2001 && $post->transactionAmount <=3000){
                    $provider = Provider::where('recharge1', 'aeps4')->first();
                }
                elseif($post->transactionAmount >=3001 && $post->transactionAmount <=4000){
                    $provider = Provider::where('recharge1', 'aeps5')->first();
                }
                elseif($post->transactionAmount >=4001 && $post->transactionAmount <=5000){
                    $provider = Provider::where('recharge1', 'aeps6')->first();
                }
                elseif($post->transactionAmount >=5001 && $post->transactionAmount <=6000){
                    $provider = Provider::where('recharge1', 'aeps7')->first();
                }
                elseif($post->transactionAmount >=6001 && $post->transactionAmount <=7000){
                    $provider = Provider::where('recharge1', 'aeps8')->first();
                }
                elseif($post->transactionAmount >=7001 && $post->transactionAmount <=8000){
                    $provider = Provider::where('recharge1', 'aeps9')->first();
                }
                elseif($post->transactionAmount >=8001 && $post->transactionAmount <=9000){
                    $provider = Provider::where('recharge1', 'aeps10')->first();
                }
                elseif($post->transactionAmount >=9001 && $post->transactionAmount <=10000){
                    $provider = Provider::where('recharge1', 'aeps11')->first();
                }
                
                $post['provider_id'] = $provider->id;
            
                if($post->transactionAmount > 99){
                    $post['profit'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id, $post->provider_id, $user->role->slug);
                    //dd($post['profit']);
                    $post['tds']    = (5 * $post->profit) / 100;
                }else{
                    $post['profit'] = 0;
                    $post['tds']    = 0;
                }
            
            }elseif($post->transactionType == "M"){
                if($post->transactionAmount >1 && $post->transactionAmount <=10000){
                    $provider = Provider::where('recharge1', 'aadharpay')->first();
                }
                
                $post['provider_id'] = $provider->id;
            
                if($post->transactionAmount > 99){
                    $post['profit'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id, $post->provider_id, $user->role->slug);
                    
                    $post['tds']    = (5 * $post->profit) / 100;
                }else{
                    $post['profit'] = 0;
                    $post['tds']    = 0;
                }
            }else{
                $provider = Provider::where('recharge1', 'ministatement')->first();
                
                $post['provider_id'] = $provider->id;
                $post['profit'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id, $post->provider_id, $user->role->slug);
            }
            
            
            
            if($post->transactionType == "M"){
                $post['gst'] = $post->profit- (100 * $post->profit) / 118;
            }else{
                $post['gst'] = 0;
            }
            
            $insert = [
                "mobile" => $post->mobileNumber,
                "aadhar" => "XXXXXXXX".substr($post->adhaarNumber, -4),
                "txnid"  => $post->txnid,
                "amount" => $post->transactionAmount,
                "charge" => $post->profit,
                'gst'    => $post->gst,
                "bank"   => $bank->bankName,
                "user_id"=> $user->id,
                'aepstype'=> $post->transactionType,
                'authcode'=> $post->device,
                'status'  => 'pending',
                'credited_by' => $user->id,
                'type' => 'credit',
                'balance' => $user->aepsbalance,
                'provider_id' => $post->provider_id,
                'api_id'      => $apidata->id,
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
        
        $result = \Myhelper::curl($url,'POST',$request, $header, "yes", 'Fingagent',$post->txnid);
        if($result['response'] == ''){
            return response()->json([
                'status'   => 'pending', 
                'message'  => 'Transaction Under Process',
                'balance'  => isset($response->data->balanceAmount) ? $response->data->balanceAmount : '0',
                'rrn'      => isset($response->data->bankRRN) ? $response->data->bankRRN : 'pending',
                'errorMsg' => isset($response->data->errorMessage) ? $response->data->errorMessage : $response->message,
                "transactionType"   => $post->transactionType,
                "title"    => "Cash Withdrawal",
                'aadhar'   => "XXXXXXXX".substr($post->adhaarNumber, -4),
                'id'       => $post->txnid,
                'amount'   => $post->transactionAmount,
                'created_at'=> date('d M Y H:i'),
                'bank'     => $bank->bankName,
                'data'     => []
            ]);
        }

        $response = json_decode($result['response']);
        if(isset($response->status)){
            switch ($post->transactionType) {
                case 'BE':
                case 'MS':
                    if($response->status == true && isset($response->data) && in_array($response->data->errorCode, ['null', null])){
                        
                        if($post->transactionType == "MS"){
                            $balance = User::where('id', $user->id)->first(['aepsbalance']);
                            Aepsreport::where('id', $report->id)->update([
                                'status'  => 'success',
                                'refno'   => $response->data->bankRRN,
                                'balance' => $balance->aepsbalance
                            ]);
    
                            User::where('id', $user->id)->increment('aepsbalance', $post->profit);
                            \Myhelper::commission(Aepsreport::where('id', $report->id)->first());
                        }
                        
                        return response()->json([
                            'status'   => 'success', 
                            'message'  => 'Transaction Successfull',
                            'balance'  => $response->data->balanceAmount,
                            'aadhar'   => "XXXXXXXX".substr($post->adhaarNumber, -4),
                            'rrn'      => $response->data->bankRRN,
                            "transactionType"   => $post->transactionType,
                            "title"    => ($post->transactionType == "BE") ? "Balance Enquiry" : "Mini Statement",
                            'id'       => $post->txnid,
                            'amount'   => $post->transactionAmount,
                            'created_at'=> date('d M Y H:i'),
                            'bank'     => $bank->bankName,
                            "data"     => isset($response->data->miniStatementStructureModel)?$response->data->miniStatementStructureModel: []
                        ]);
                    }else{
                        return response()->json([
                            'status'   => 'failed', 
                            'message'  => isset($response->data->errorMessage) ? $response->data->errorMessage : $response->message,
                            'balance'  => isset($response->data->balanceAmount) ? $response->data->balanceAmount : '0',
                            'rrn'      => isset($response->data->bankRRN) ? $response->data->bankRRN : 'Failed',
                            "transactionType"   => $post->transactionType,
                            "title"    => "Mini Statement",
                            'aadhar'   => "XXXXXXXX".substr($post->adhaarNumber, -4),
                            'id'       => $post->txnid,
                            'created_at'=> date('d M Y H:i'),
                            'bank'     => $bank->bankName,
                            "data"     => isset($response->data->miniStatementStructureModel)?$response->data->miniStatementStructureModel: []
                        ]);
                    }
                    break;

                case 'CW':
                    if($response->status == true && isset($response->data) && in_array($response->data->errorCode, ['null', null])){
                        $balance = User::where('id', $user->id)->first(['aepsbalance']);
                        Aepsreport::where('id', $report->id)->update([
                            'status' => 'success',
                            'refno'  => $response->data->bankRRN,
                            'balance'=> $balance->aepsbalance
                        ]);

                        User::where('id', $user->id)->increment('aepsbalance', $post->transactionAmount + $post->profit);

                        if($post->transactionAmount > 99 && $user->role->slug != "apiuser"){
                            \Myhelper::commission(Aepsreport::where('id', $report->id)->first());
                        }

                        return response()->json([
                            'status'   => 'success', 
                            'message'  => 'Transaction Successfull',
                            'balance'  => $response->data->balanceAmount,
                            'rrn'      => $response->data->bankRRN,
                            "transactionType"   => $post->transactionType,
                            "title"    => "Cash Withdrawal",
                            'aadhar'   => "XXXXXXXX".substr($post->adhaarNumber, -4),
                            'id'       => $post->txnid,
                            'amount'   => $post->transactionAmount,
                            'created_at'=> date('d M Y H:i'),
                            'bank'     => $bank->bankName
                        ]);
                    }else{
                        Aepsreport::where('id', $report->id)->update([
                            'status' => 'failed',
                            'refno'  => isset($response->data->bankRRN) ? $response->data->bankRRN : $response->message,
                            'remark' => isset($response->data->errorMessage) ? $response->data->errorMessage : $response->message
                        ]);

                        return response()->json([
                            'status'   => 'failed', 
                            'message'  => isset($response->data->errorMessage) ? $response->data->errorMessage : $response->message,
                            'balance'  => isset($response->data->balanceAmount) ? $response->data->balanceAmount : '0',
                            'rrn'      => isset($response->data->bankRRN) ? $response->data->bankRRN : 'Failed',
                            "transactionType"   => $post->transactionType,
                            "title"    => ($post->transactionType == "BE") ? "Balance Enquiry" : "Cash Withdrawal",
                            'aadhar'   => "XXXXXXXX".substr($post->adhaarNumber, -4),
                            'id'       => $post->txnid,
                            'amount'   => $post->transactionAmount,
                            'created_at'=> date('d M Y H:i'),
                            'bank'     => $bank->bankName
                        ]);
                    }
                    break;

                case 'M':
                    if($response->status == true && isset($response->data) && in_array($response->data->errorCode, ['null', null])){
                        $balance = User::where('id', $user->id)->first(['aepsbalance']);
                        Aepsreport::where('id', $report->id)->update([
                            'status' => 'success',
                            'refno'  => $response->data->bankRRN,
                            'balance'=> $balance->aepsbalance
                        ]);

                        User::where('id', $post->user_id)->increment('aepsbalance', $post->transactionAmount - $post->profit);

                        if($post->transactionAmount > 99 && $user->role->slug != "apiuser"){
                           \Myhelper::commission(Aepsreport::where('id', $report->id)->first());
                        }

                        return response()->json([
                            'status'   => 'success', 
                            'message'  => 'Transaction Successfull',
                            'balance'  => $response->data->balanceAmount,
                            'rrn'      => $response->data->bankRRN,
                            "transactionType"   => $post->transactionType,
                            "title"    => (($post->transactionType == "BE") ? "Balance Enquiry" : (($post->transactionType == "CW") ? "Cash Withdrawal" : "Aadhar Pay")),
                            'aadhar'   => "XXXXXXXX".substr($post->adhaarNumber, -4),
                            'id'       => $post->txnid,
                            'amount'   => $post->transactionAmount,
                            'created_at'=> date('d M Y H:i'),
                            'bank'     => $bank->bankName
                        ]);
                    }else{
                        Aepsreport::where('id', $report->id)->update([
                            'status' => 'failed',
                            'refno'  => isset($response->data->bankRRN) ? $response->data->bankRRN : $response->message,
                            'remark' => isset($response->data->errorMessage) ? $response->data->errorMessage : $response->message
                        ]);

                        return response()->json([
                            'status'   => 'failed', 
                            'message'  => isset($response->data->errorMessage) ? $response->data->errorMessage : $response->message,
                            'balance'  => isset($response->data->balanceAmount) ? $response->data->balanceAmount : '0',
                            'rrn'      => isset($response->data->bankRRN) ? $response->data->bankRRN : 'Failed',
                            "transactionType"   => $post->transactionType,
                            "title"    => ($post->transactionType == "BE") ? "Balance Enquiry" : "Cash Withdrawal",
                            'aadhar'   => "XXXXXXXX".substr($post->adhaarNumber, -4),
                            'id'       => $post->txnid,
                            'amount'   => $post->transactionAmount,
                            'created_at'=> date('d M Y H:i'),
                            'bank'     => $bank->bankName
                        ]);
                    }
                    break;
            }
        }else{
            return response()->json([
                'status'   => 'pending', 
                'message'  => 'Transaction Under Process',
                'balance'  => isset($response->data->balanceAmount) ? $response->data->balanceAmount : '0',
                'rrn'      => isset($response->data->bankRRN) ? $response->data->bankRRN : 'pending',
                'errorMsg' => isset($response->data->errorMessage) ? $response->data->errorMessage : $response->message,
                "transactionType"   => $post->transactionType,
                "title"    => "Cash Withdrawal",
                'aadhar'   => "XXXXXXXX".substr($post->adhaarNumber, -4),
                'id'       => $post->txnid,
                'amount'   => $post->transactionAmount,
                'created_at'=> date('d M Y H:i'),
                'bank'     => $bank->bankName,
                'data'     => []
            ]);
        }
    }
    
    public function cashdeposit(Request $post)
    {
        $data['agent'] = Fingagent::where('user_id', \Auth::id())->first();
        $data['state'] = \DB::table('fingstate')->orderBy('state','asc')->get();
        $data['aadharbanks'] = \DB::table('fingaepscashbanks')->get();
        return view('service.cashdeposit')->with($data);
    }

    public function cashdepositbanklist(Request $post)
    {
        $result = \Myhelper::curl("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details", 'GET', "", [], "no");
        $banks  = json_decode($result['response']);

        foreach ($banks->data as $bank) {
            if($bank->iinno != "NULL"){
                $insert['activeFlag'] = $bank->activeFlag;
                $insert['bankName']   = $bank->bankName;
                $insert['iinno'] = $bank->iinno;

                $inserts[] = $insert;
            }
        }

        \DB::table('fingaepscashbanks')->insert($inserts);
    }

    public function cashdeposittransaction(Request $post)
    {
        $post['user_id'] = \Auth::id();
        $user     = User::where('id', $post->user_id)->first(); 
        $apidata  = Api::where('code', 'fingcms')->first(); 
        $post['superMerchantId'] = $apidata['optional1'];

        switch ($post->transactionType) {
            case 'sendotp':
                $rules = array(
                    'transactionType' => 'required',
                    'mobileNumber'    => 'required|numeric|digits:10',
                    'iinno'           => 'required',
                    'superMerchantId' => 'required',
                    'accountNumber'   => 'required'
                );
                break;

            case 'otpvalidate':
                $rules = array(
                    'transactionType' => 'required',
                    'mobileNumber'    => 'required|numeric|digits:10',
                    'iinno'           => 'required',
                    'superMerchantId' => 'required',
                    'accountNumber'   => 'required',
                    'otp'             => 'required',
                    'txnid'           => 'required'
                );
                break;

            case 'cashdeposit':
                $rules = array(
                    'transactionType'   => 'required',
                    'mobileNumber'      => 'required|numeric|digits:10',
                    'iinno'             => 'required',
                    'transactionAmount' => 'required',
                    'superMerchantId'   => 'required',
                    'accountNumber'     => 'required',
                    'otp'               => 'required',
                    'txnid'             => 'required'
                );
                break;
            
            default:
                return response()->json(['status' => "ERR", "message" => "Invalid Transaction Type"]);
                break;
        }

        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(['status'=>'ERR', 'message'=> $error]);
        }
        
        $agent = Fingagent::where('user_id', $post->user_id)->first();
        if(!$agent){
            return response()->json(['status' => "ERR", "message" => "User Not Onboarded"]);
        }

        if($agent->status != "approved" ){
            return response()->json(['status' => "ERR", "message" => "AePs KYC ".ucfirst($agent->status)]);
        }
        
        if($post->transactionAmount > $user->aepsbalance){
            return response()->json(['status' => "ERR", "message" => "Insufficient Wallet Balance"]);
        }
        
        $sessionkey = '';
        $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
        foreach ($mt_rand as $chr)
        {             
            $sessionkey .= chr($chr);         
        }

        $iv =   '06f2f04cc530364f';
        $fp =fopen(base_path().'/fingpay_public_production.txt',"r");
        $publickey =fread($fp,8192);         
        fclose($fp);         
        openssl_public_encrypt($sessionkey,$crypttext,$publickey);
                
        $gpsdata =  geoip($post->ip());
        // echo "hassan";
        // dd($post->transactionType);
        switch ($post->transactionType) {
            case 'sendotp':
                do {
                    $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport);

                // try {
                //     $biodata       =  str_replace("&lt;","<",str_replace("&gt;",">",$post->biodata));
                //     $xml           =  simplexml_load_string($biodata);
                //     $skeyci        =  (string)$xml->Skey['ci'][0];
                //     $headerarray   =  json_decode(json_encode((array)$xml), TRUE);
                // } catch (\Exception $e) {
                //     return response()->json(['status' => "ERR", "message" => $e->getMessage()]);
                // }
                // if($post->device == "MORPHO_PROTOBUF"){
                //     $deviceIMEI = $headerarray['DeviceInfo']['additional_info']['Param']['@attributes']['value'];
                // }else{
                //     $deviceIMEI = $headerarray['DeviceInfo']['additional_info']['Param'][0]['@attributes']['value']; 
                // }
                $deviceIMEI = "3452342342";//substr(exec('getmac'), 0, 17);
                break;

            case 'otpvalidate':
                $tempdata = Fingtempdata::where("merchantTranId", "=", $post->txnid)->first();

                if(!$tempdata){
                    return response()->json(['status' => "ERR", "message" => "Invalid Transaction"]);
                }
                $deviceIMEI = $tempdata->deviceIMEI;
                break;

            case 'cashdeposit':
                $tempdata = Fingtempdata::where("merchantTranId", "=", $post->txnid)->first();
                if(!$tempdata){
                    return response()->json(['status' => "ERR", "message" => "Invalid Transaction"]);
                }
                $deviceIMEI = $tempdata->deviceIMEI;
                break;
            
            default:
                return response()->json(['status' => "ERR", "message" => "Invalid Transaction Type"]);
                break;
        }
     //dd($post->iinno);
        $bank  = \DB::table('fingaepscashbanks')->where('iinno', $post->iinno)->first();
        $json = [
            "superMerchantId"  => $post->superMerchantId, 
            "merchantUserName" => $agent->merchantLoginId,
            "merchantPin"      => md5($agent->merchantLoginPin),
            "subMerchantId"    => "",
            "secretKey"        => $apidata['optional2'],
            "mobileNumber"     => $post->mobileNumber,
            "iin"              => $post->iinno,
            "transactionType"  => 'CDO',
            "latitude"         => $gpsdata->lat,
            "longitude"        => $gpsdata->lon,
            "requestRemarks"   => "Aeps",
            "merchantTranId"   => $post->txnid,
            "accountNumber"    => $post->accountNumber,
            "amount"           => $post->transactionAmount,
            "fingpayTransactionId" => "",
            "otp"            => "",
            "cdPkId"         => "0",
            "paymentType"    => "B"
        ];
     //dd($post->transactionType);
        switch ($post->transactionType) {
            case 'sendotp':
                try {
                    $tempdata = Fingtempdata::create([
                        'merchantTranId' => $post->txnid,
                        'mobileNumber'   => $post->mobileNumber, 
                        'accountNumber'  => $post->accountNumber,
                        'transactiontype'=> $post->transactiontype, 
                        'user_id'        => $post->user_id,
                        'deviceIMEI'     => $deviceIMEI
                    ]);

                } catch (\Exception $e) {
                    return response()->json(['status' => "ERR", "message" => "Technical Issue, Try Again"]);
                }
                $url = $apidata['url'].'fpaepsservice/api/CashDeposit/merchant/php/generate/otp';
               // dd($url);
                break;

            case 'otpvalidate':
                $url = $apidata['url'].'fpaepsservice/api/CashDeposit/merchant/php/validate/otp';
                $json["fingpayTransactionId"] = $tempdata->fingpayTransactionId;
                $json["otp"]    = $post->otp;
                $json["cdPkId"] = $tempdata->cdPkId;
                break;

            case 'cashdeposit':
                if($post->transactionAmount > 0 && $post->transactionAmount <= 3000){
                    $provider = Provider::where('recharge1', 'cashdeposit1')->first();
                }elseif($post->transactionAmount>3000 && $post->transactionAmount<=4000){
                    $provider = Provider::where('recharge1', 'cashdeposit2')->first();
                }elseif($post->transactionAmount>4000 && $post->transactionAmount<=5000){
                    $provider = Provider::where('recharge1', 'cashdeposit3')->first();
                }elseif($post->transactionAmount>5000 && $post->transactionAmount<=10000){
                    $provider = Provider::where('recharge1', 'cashdeposit4')->first();
                }
                
                $post['provider_id'] = $provider->id;
                if($post->transactionAmount > 99){
                    $post['charge'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id, $post->provider_id, $user->role->slug);
                }else{
                    $post['charge'] = 0;
                }

                $insert = [
                    "mobile" => $post->mobileNumber,
                    "aadhar" => $post->benename,
                    "txnid"  => $post->txnid,
                    "amount" => $post->transactionAmount,
                    "charge" => $post->charge,
                    "bank"   => $bank->bankName,
                    "user_id"=> $user->id,
                    'aepstype'=> "CDO",
                    'authcode'=> $post->device,
                    'status'  => 'pending',
                    'credited_by' => $user->id,
                    'type' => 'credit',
                    'balance' => $user->aepsbalance,
                    'provider_id' => $post->provider_id,
                    'api_id' => $apidata->id,
                    'product' => 'cashdeposit',
                    'authcode' => $gpsdata->lat."/".$gpsdata->lon
                ];

                //try {
                    $report = Aepsreport::create($insert);
                //} catch (\Exception $e) {
                  //  return response()->json(['status' => "ERR", "message" => "Technical Issue, Try Again"]);
                //}

                $url = $apidata['url'].'fpaepsservice/api/CashDeposit/merchant/php/transaction';
                $json["fingpayTransactionId"] = $tempdata->fingpayTransactionId;
                $json["otp"]    = $post->otp;
                $json["cdPkId"] = $tempdata->cdPkId;
                break;
        }

        if($post->device == "MORPHO_PROTOBUF"){
            $header = [         
                'Content-Type: text/xml',             
                'trnTimestamp:'.date('d/m/Y H:i:s'),         
                'hash:'.base64_encode(hash("sha256",(json_encode($json).$apidata['optional2']), True)),         
                'deviceIMEI:'.$deviceIMEI,         
                'eskey:'.base64_encode($crypttext)         
            ];
        }else{
            // echo "hello";
            // exit();
            $header = [         
                'Content-Type: text/xml',             
                'trnTimestamp:'.date('d/m/Y H:i:s'),         
                'hash:'.base64_encode(hash("sha256",(json_encode($json).$apidata['optional2']), True)),         
                'deviceIMEI:'.$deviceIMEI,         
                'eskey:'.base64_encode($crypttext)         
            ];
        }

        if(env('APP_ENV') == "local"){
            return response()->json([
                'status'   => 'TUP', 
                'message'  => 'Transaction Under Process',
                'balance'  => '0',
                'rrn'      => 'pending',
                'txnid'    => $post->txnid,
                "statement"=> [],
                'transactionType' => $post->transactionType,
                'title' => $title,
                "created_at" => date("Y-m-d H:i:s"),
                "amount" => $post->transactionAmount,
                "aadhar" => "XXXXXXXX".substr($post->adhaarNumber, -4),
                "id" => isset($report->id) ? $report->id : "0",
                "bank" => $bank->bankName
            ]);
        }

        $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
        $request = base64_encode($ciphertext_raw);
        // dd($post->txnid);
        $result = \Myhelper::curl($url, 'POST', $request, $header, "yes", 'CashDeposit', $post->txnid);
       //error from here/
       //  dd($result);
        if($result['response'] == ''){
            return response()->json([
                'status'   => 'TUP', 
                'message'  => 'Transaction Under Process',
                'rrn'      => 'pending',
                'txnid'    => $post->txnid,
                "bank" => $bank->bankName
            ]);
        }

        $response = json_decode($result['response']);
        if(isset($response->status)){
            switch ($post->transactionType) {
                case 'sendotp':
                   // dd($post->transactionType);
                    if($response->status == true && isset($response->data) && isset($response->data->cdPkId)){
                        Fingtempdata::where('id', $tempdata->id)->update([
                            'fingpayTransactionId' => $response->data->fingpayTransactionId,
                            'cdPkId'               => $response->data->cdPkId
                        ]);

                        return response()->json([
                            'status'   => 'TXN', 
                            'message'  => $response->message,
                            'txnid'    => $post->txnid
                        ]);

                    }else{
                        return response()->json([
                            'status'   => 'TXR', 
                            'message'  => $response->message
                        ]);
                    }
                    break;

                case 'otpvalidate':
                    if($response->status == true && isset($response->data) && isset($response->data->responseCode) && $response->data->responseCode == "00"){
                        return response()->json([
                            'status'   => 'TXN', 
                            'message'  => $response->message,
                            'benename' => $response->data->beneficiaryName,
                            'account'  => $post->accountNumber,
                            'amount'   => $post->transactionAmount,
                            'txnid'    => $post->txnid
                        ]);

                    }else{
                        return response()->json([
                            'status'   => 'TXR', 
                            'message'  => $response->message
                        ]);
                    }
                    break;

                case 'cashdeposit':
                    //dd($result);
                    if($response->status == true && isset($response->data) && in_array($response->data->responseCode, ['00'])){
                        $balance = User::where('id', $user->id)->first(['aepsbalance']);
                        Aepsreport::where('id', $report->id)->update([
                            'status' => 'success',
                            'refno'  => $response->data->bankRrn,
                            'balance'=> $balance->aepsbalance,
                            'payid'  => isset($response->data->fingpayTransactionId) ? $response->data->fingpayTransactionId : '',
                            'mytxnid'    => isset($response->data->fpRrn) ? $response->data->fpRrn : '',
                            'terminalid' => isset($response->data->stan) ? $response->data->stan : ''
                        ]);

                        User::where('id', $post->user_id)->decrement('aepsbalance', $post->transactionAmount + $post->charge);

                        if($post->transactionAmount > 99){
                            try {
                                \Myhelper::commission(Aepsreport::where('id', $report->id)->first());
                            } catch (\Exception $e) {}
                        }

                        return response()->json([
                            'status'   => 'TXN', 
                            'message'  => 'Transaction Successfull',
                            'rrn'      => $response->data->bankRrn,
                            'benename' => $post->benename,
                            'account'  => $post->accountNumber,
                            'amount'   => $post->transactionAmount,
                            'txnid'    => $post->txnid,
                            "bank"     => $bank->bankName,
                            'date'     => date('d-M-Y')
                        ]);
                    }else{
                        Aepsreport::where('id', $report->id)->update([
                            'status' => 'failed',
                            'refno'  => isset($response->data->bankRrn) ? $response->data->bankRrn : '',
                            'balance'=> $user->aepsbalance,
                            'payid'  => isset($response->data->fingpayTransactionId) ? $response->data->fingpayTransactionId : '',
                            'mytxnid'    => isset($response->data->fpRrn) ? $response->data->fpRrn : '',
                            'terminalid' => isset($response->data->stan) ? $response->data->stan : '',
                            'remark' => isset($response->message) ? $response->message : ''
                        ]);

                        return response()->json([
                            'status'   => 'TXR', 
                            'message'  => $response->message,
                            'rrn'      => isset($response->data->bankRrn) ? $response->data->bankRrn : '',
                            'benename' => $post->benename,
                            'account'  => $post->accountNumber,
                            'amount'   => $post->transactionAmount,
                            'txnid'    => $post->txnid,
                            "bank"     => $bank->bankName
                        ]);
                    }
                    break;
            }
        }else{
            return response()->json([
                'status'   => 'TUP', 
                'message'  => 'Transaction Under Process',
                'rrn'      => 'pending',
                'txnid'    => $post->txnid,
                "bank" => $bank->bankName
            ]);
        }
    }
}
        



                
