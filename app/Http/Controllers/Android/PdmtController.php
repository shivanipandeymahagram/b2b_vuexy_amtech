<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Provider;
use App\Models\Mahabank;
use App\Models\Report;
use App\Models\Commission;
use App\Models\Packagecommission;
use App\User;
use Carbon\Carbon;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class PdmtController extends Controller
{
    protected $api,$edmt,$pdmt,$cashverify;
    public function __construct()
    {
         $this->pdmt = Api::where('code', 'pdmt')->first();
    }
    
      public function getPToken($uniqueid)
    {
        $payload =  [
            "timestamp" => time(),
            "partnerId" => $this->pdmt->username,
            "reqid"     => $uniqueid
        ];
        
        $key = $this->pdmt->password;
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }
    
  

    public function payment(Request $post)
    {
     //   if (\Myhelper::hasRole('admin') || (!\Myhelper::can('pdmt_service') && $post->type != 'getdistrict')) {
         //   return \Response::json(['statuscode' => 'ERR', 'message' => "Permission not allowed"]);
       // }

       if(!$this->pdmt || $this->pdmt->status == 0){
                return response()->json(['statuscode' => 'ERR', 'message' => "Money Transfer Service Currently Down."]);
        }
        
        
        $user = User :: where('id',$post->user_id)->first();
        $userdata = User::where('id', $post->user_id)->first();

        if($post->type == "transfer"){
            $codes = ['dmt1', 'dmt2', 'dmt3', 'dmt4', 'dmt5'];
            $providerids = [];
            foreach ($codes as $value) {
                $providerids[] = Provider::where('recharge1', $value)->first(['id'])->id;
            }
            $commission = Commission::where('scheme_id', $userdata->scheme_id)->whereIn('slab', $providerids)->get();
            if(!$commission || sizeof($commission) < 5){
                //return response()->json(['statuscode' => 'ERR', 'message' => "Money Transfer charges not set, contact administrator."]);
            }
        }
        
        switch ($post->type) {
            case 'verification':
            case 'outletotp':
            case 'getbeneficiary':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric');
            break;

            case 'outletregister':
                $rules = array('user_id' => 'required|numeric','merchantPhoneNumber' => 'required|numeric','merchantName' => 'required','merchantState' => 'required','merchantEmail' => 'required', 'userPan' => 'required', 'merchantAddress' => 'required', 'otp' => 'required');
            break;

            case 'mobilechange':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric','newmobile' => 'required|numeric');
            break;

            case 'mobilechangeverify':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric','newmobile' => 'required|numeric','otp' => 'required|numeric','newotp' => 'required|numeric');
            break;

            case 'benedelete':
                $rules = array('rid' => 'required|numeric', 'bid' => 'required|numeric');
            break;

            case 'benedeletevalidate':
                $rules = array('transid' => 'required', 'otp' => 'required|numeric');
            break;
            
            case 'registration':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric|digits:10','stateresp'=>'required' ,'fname' => 'required|regex:/^[\pL\s\-]+$/u', 'lname' => 'required|regex:/^[\pL\s\-]+$/u');
            break;

            case 'registrationValidate':
                $rules = array('rid' => 'required', 'otp' => 'required|numeric');
            break;

            case 'addbeneficiary':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20", "benename" => "required|regex:/^[\pL\s\-]+$/u");
            break;

            case 'beneverify':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric|digits:10','beneaccount' => "required|numeric|digits_between:6,20", "benemobile" => 'required|numeric|digits:10', "otp" => 'required|numeric');
            break;

            case 'accountverification':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20", "benename" => "required|regex:/^[\pL\s\-]+$/u");
            break;

            case 'transfer':
                $rules = array('user_id' => 'required|numeric','name' => 'required','mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20","benename" => "required","beneid" => "required|numeric","mode" => "required",'amount' => 'required|numeric|min:100|max:25000');
            break;
            
            case 'refundotp':
                $rules = array('user_id' => 'required|numeric', 'id' => 'required|numeric');
            break;

            case 'getrefund':
                $rules = array('user_id' => 'required|numeric', 'otp' => 'required|numeric');
            break;
             case 'getbank':
                $rules = array('user_id' => 'required|numeric', 'apptoken' => 'required');
            break;

            default:
                return ['statuscode'=>'BPR', 'message'=> "Invalid request format"];
            break;
        }

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
        $header = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Token: ".$token['token'],
            "Authorisedkey: ".$this->pdmt->optional1
        );

        switch ($post->type) {
            case 'getbank':
             $banks =  \DB::table('dmtbanks')->get();  
            return response()->json(['statuscode' => "TXN", "message" => "Bank details fetched", 'data' => $banks]); 
            break;    
            case 'verification':
                $url = $this->pdmt->url."remitter/queryremitter";
              
                $parameters = [
                    "bank3_flag" => "no",
                    "mobile"     => $post->mobile
                ];

                break;
                
            case 'getbeneficiary':
                $url = $this->pdmt->url."beneficiary/registerbeneficiary/fetchbeneficiary";
                $parameters = [
                    "mobile"     => $post->mobile
                ];

                break;

            case "registration":
                $url = $this->pdmt->url."remitter/registerremitter";
                $parameters = [
                    "bank3_flag" => "no",
                    "mobile"     => $post->mobile,
                    "firstname"  => $post->fname,
                    "lastname"   => $post->lname,
                    "address"    => $user->address,
                    "otp"        => $post->otp,
                    "pincode"    => $user->pincode,
                    "stateresp"  => $post->stateresp,
                    "dob"        =>  date("d-m")."-".rand(1980, 2000),
                    "gst_state"  => "33",//$post->gst_state,
                ];
                break;

            case "registrationValidate":
                $parameters['remitterid'] = $post->rid;
                $parameters['otp'] = $post->otp;
                break;
            
            case "addbeneficiary":
                $url = $this->pdmt->url."beneficiary/registerbeneficiary";
                $parameters = [
                    "mobile"     => $post->mobile,
                    "benename"   => $post->benename,
                    "bankid"     => $post->benebank,
                    "accno"      => $post->beneaccount,
                    "ifsccode"   => $post->beneifsc,
                    "pincode"    => $post->pincode,
                    "verified"   => "1",
                    "dob"        => $post->dob,
                    "gst_state"  => $post->gst_state,
                ];
                break;
                
            case 'benedelete':
                $url = $this->pdmt->url."beneficiary/registerbeneficiary/deletebeneficiary";
                $parameters = [
                    "mobile" => $post->rid,
                    "bene_id"=> $post->bid
                ];
                break;

            case 'beneverify':
                $url = $this->pdmt->url."AIRTEL/verifybeneotp";
                $parameter["custno"] = $post->mobile;
                $parameter["otp"]    = $post->otp;
                $parameter["beneaccno"] = $post->beneaccount;
                $parameter["benemobile"]= $post->benemobile;
                break;
            
            case 'accountverification':
            
                        $token = $this->getPToken($post->user_id.Carbon::now()->timestamp);
                        $header = array(
                            "Cache-Control: no-cache",
                            "Content-Type: application/json",
                            "Token: ".$token['token'],
                            "Authorisedkey: ".$this->pdmt->optional1
                        );
                        
                        $post['amount'] = 1;
                        $provider = Provider::where('recharge1', 'dmt1accverify')->first();
                        $post['charge'] = \Myhelper::getCommission($post->amount, $userdata->scheme_id, $provider->id, $userdata->role->slug);
                        $post['provider_id'] = $provider->id;
                        if($userdata->mainwallet < $post->amount + $post->charge){
                            return response()->json(["statuscode" => "IWB",  'message' => 'Low balance, kindly recharge your wallet.']);
                        }
                        
                        do {
                            $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);
                        
                        $url = $this->pdmt->url."beneficiary/registerbeneficiary/benenameverify";
        
                        $parameters = [
                            "mobile"     => $post->mobile,
                            "benename"   => $post->benename,
                            "bankid"     => $post->benebank,
                            "accno"      => $post->beneaccount,
                            "ifsccode"   => $post->beneifsc,
                            "address"    => "bankhedi",
                            "pincode"    => "461990",
                            "gst_state"  =>  "07",
                            "referenceid"=> $post->txnid,
                            "dob"        => "1991-12-23",
                            'bene_id'    => $post->beneid
                        ];
                break;
            
            case 'transfer':
                if ($this->pinCheck($post) == "fail") {
                    return response()->json(["statuscode" => "ERR", 'message' => "Transaction Pin is incorrect"]);
                }
                return $this->transfer($post);
                break;
                
            case 'refundotp':
                $report = Report::where('id', $post->id)->first();
                if($report && $report->status != "refund"){
                    return response()->json(["statuscode" => "ERR", 'message'=>'Money Refund Not Allowed']);
                }

                $url = $this->pdmt->url."refund/refund/resendotp";
                $parameters['ackno'] = $report->payid;
                $parameters['referenceid'] = str_replace("P", "", $report->txnid);
                break;

            case 'getrefund':
                $report = Report::where('id', $post->transid)->first();
                if($report && $report->status != "refund"){
                    return response()->json(["statuscode" => "ERR", 'message'=>'Money Refund Not Allowed']);
                }

                $url = $this->pdmt->url."refund/refund";
                $parameters['ackno'] = $report->payid;
                $parameters['referenceid'] = str_replace("P", "", $report->txnid);
                $parameters['otp'] = $post->otp;
                break;
            
            default:
                return response()->json(['statuscode'=> 'BPR', 'message'=> 'Bad Parameter Request']);
                break;
        }        

        if($post->type != "accountverification"){
            $result = \Myhelper::curl($url, "POST", json_encode($parameters), $header, "no", 'App\Models\Report', '0');
            
        \DB::table('rp_log')->insert([
            'ServiceName' => $post->type,
            'header' => json_encode($header),
            'body' => json_encode($parameters),
            'response' => $result['response'],
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        }else{
            
        $result = \Myhelper::curl($url, "POST", json_encode($parameters), $header, "yes", 'App\Models\Report', $post->txnid); 
                    
        \DB::table('rp_log')->insert([
            'ServiceName' => $post->type,
            'header' => json_encode($header),
            'body' => json_encode($parameters),
            'response' => $result['response'],
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s')
        ]);
                     
        }
         
       

        //dd($url, json_encode($parameters), $header, $result);
        
        if ($result['error'] && $result['response'] == "") {
            if($post->type == "accountverification"){
                $response = [
                    "message"=>"Success",
                    "statuscode"=>"001",
                    "availlimit"=>"0",
                    "total_limit"=>"0",
                    "used_limit"=>"0",
                    "Data"=>[["fesessionid"=>"CP1801861S131436",
                    "tranid"=>"pending",
                    "rrn"=>"pending",
                    "externalrefno"=>"MH357381218131436",
                    "amount"=>"0",
                    "responsetimestamp"=>"0",
                    "benename"=>"",
                    "messagetext"=>"Success",
                    "code"=>"1",
                    "errorcode"=>"1114",
                    "mahatxnfee"=>"10.00"
                    ]]
                ];

                return $this->output($post, json_encode($response), $userdata);
            }

            return response()->json(["statuscode" => "ERR", 'message'=>'System Error'], 200);
        }

        return $this->output($post, $result['response'] , $userdata);
    }

    public function myvalidate($post)
    {
        $validate = "yes";
        switch ($post->type) {
            case 'getdistrict':
                $rules = array('stateid' => 'required|numeric');
            break;

            case 'verification':
            case 'outletotp':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric');
            break;

            case 'outletregister':
                $rules = array('user_id' => 'required|numeric','merchantPhoneNumber' => 'required|numeric','merchantName' => 'required','merchantState' => 'required','merchantEmail' => 'required', 'userPan' => 'required', 'merchantAddress' => 'required', 'otp' => 'required');
            break;

            case 'mobilechange':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric','newmobile' => 'required|numeric');
            break;

            case 'mobilechangeverify':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric','newmobile' => 'required|numeric','otp' => 'required|numeric','newotp' => 'required|numeric');
            break;

            case 'benedelete':
                $rules = array('rid' => 'required|numeric', 'bid' => 'required|numeric');
            break;

            case 'benedeletevalidate':
                $rules = array('transid' => 'required', 'otp' => 'required|numeric');
            break;
            
            case 'registration':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric|digits:10', 'firstname' => 'required|regex:/^[\pL\s\-]+$/u', 'lastname' => 'required|regex:/^[\pL\s\-]+$/u', 'pincode' => "required|numeric|digits:6");
            break;

            case 'registrationValidate':
                $rules = array('rid' => 'required', 'otp' => 'required|numeric');
            break;

            case 'addbeneficiary':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20", "benename" => "required|regex:/^[\pL\s\-]+$/u");
            break;

            case 'beneverify':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric|digits:10','beneaccount' => "required|numeric|digits_between:6,20", "benemobile" => 'required|numeric|digits:10', "otp" => 'required|numeric');
            break;

            case 'accountverification':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20", "benename" => "required|regex:/^[\pL\s\-]+$/u");
            break;

            case 'transfer':
                $rules = array('user_id' => 'required|numeric','name' => 'required','mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20","benename" => "required",'amount' => 'required|numeric|min:10|max:25000');
            break;

            default:
                return ['statuscode'=>'BPR', 'message'=> "Invalid request format"];
            break;
        }

        if($validate == "yes"){
            $validator = \Validator::make($post->all(), $rules);
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $key => $value) {
                    $error = $value[0];
                }
                $data = ['statuscode'=>'BPR', 'message'=> $error];
            }else{
                $data = ['status'=>'NV'];
            }
        }else{
            $data = ['status'=>'NV'];
        }
        return $data;
    }

    public function transfer($post)
    {
        $url = $url = $this->pdmt->url."transact/transact";
        $user =  User::where("id",$post->user_id)->first();
        $parameters = array(
            'token' => $this->pdmt->username,
            'mobile'=> $post->mobile,
            "pipe"  => $post->pipe ?? "bank1",
            'bene_id'  => $post->beneid,
            'txntype'  => $post->mode,
            "pincode"    => isset($user->pincode)?$user->pincode:"222222",
            "address"    => isset($user->address)?$user->address:"TYtyty",
            "dob"        => "01-01-1990",
            "gst_state"  => "24",
        );

        $amount = $post->amount;
        for ($i=1; $i < 6; $i++) { 
            if(5000*($i-1) <= $amount  && $amount <= 5000*$i){
                if($amount == 5000*$i){
                    $n = $i;
                }else{
                    $n = $i-1;
                    $x = $amount - $n*5000;
                }
                break;
            }
        }

        $amounts = array_fill(0,$n,5000);
        if(isset($x)){
            array_push($amounts , $x);
        }

        foreach ($amounts as $amount) {
            $outputs['statuscode'] = "TXN";
            $post['amount'] = $amount;
            $user = User::where('id', $post->user_id)->first();
            $post['charge'] = $this->getCharge($post->amount);
           
            if($user->mainwallet < $post->amount + $post->charge){
                $outputs['data'][] = array(
                    'amount' => $amount,
                    'status' => 'TXF',
                    'data'   => [
                        "statuscode" => "TXF",
                       // "status" => "Insufficient Wallet Balance",
                        "message" => "Insufficient Wallet Balance",
                    ]
                );
            }else{
                $post['amount'] = $amount;
                
                do {
                    $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

                if($post->amount >= 100 && $post->amount <= 1000){
                    $provider = Provider::where('recharge1', 'dmt1')->first();
                }elseif($amount>1000 && $amount<=2000){
                    $provider = Provider::where('recharge1', 'dmt2')->first();
                }elseif($amount>2000 && $amount<=3000){
                    $provider = Provider::where('recharge1', 'dmt3')->first();
                }elseif($amount>3000 && $amount<=4000){
                    $provider = Provider::where('recharge1', 'dmt4')->first();
                }else{
                    $provider = Provider::where('recharge1', 'dmt5')->first();
                }
                
                $post['provider_id'] = $provider->id;
                $post['service'] = $provider->type;
                $insert = [
                    'api_id' => $this->pdmt->id,
                    'provider_id' => $post->provider_id,
                    'option1' => $post->name,
                    'mobile' => $post->mobile,
                    'number' => $post->beneaccount,
                    'option2' => $post->benename,
                    'option3' => $post->benebank,
                    'transtype'=>'mainwallet',
                    'option4' => $post->beneifsc,
                    'txnid' => $post->txnid,
                    'amount' => $post->amount,
                    'charge' => $post->charge,
                    'status' => 'success',
                    'user_id' => $user->id,
                    'credit_by' => $user->id,
                    'product' => 'dmt',
                     "via"    => "app",
                    'balance' => $user->mainwallet,
                    'description' => $post->benemobile,
                    'trans_type' => 'debit'
                ];
                
                
                //dd($insert);
                
                
                $previousrecharge = Report::where('number', $post->beneaccount)->where('amount', $post->amount)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subSeconds(5)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(1)->format('Y-m-d H:i:s')])->count();
                if($previousrecharge >= 0){
                    $transaction = User::where('id', $user->id)->decrement('mainwallet', $post->amount + $post->charge);
                    if(!$transaction){
                        $outputs['data'][] = array(
                            'amount' => $amount,
                            'status' => 'TXF',
                            'data' => [
                                "statuscode" => "TXF",
                                "status" => "Transaction Failed",
                            ]
                        );
                    }else{
                        
                        //try {
                            $report = Report::create($insert);
                            $post['reportid'] = $report->id;
                            $parameters['amount'] = $post->amount;
                            $parameters['referenceid']= $post->txnid;
                            $parameters['txntype']= 'IMPS';
                            
                            $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                            $header = array(
                                "Cache-Control: no-cache",
                                "Content-Type: application/json",
                                "Token: ".$token['token'],
                                "Authorisedkey: ".$this->pdmt->optional1
                            );
    
                            if (env('APP_ENV') == "server") {
                                $result = \Myhelper::curl($url, "POST", json_encode($parameters), $header, "yes", 'App\Models\Report', $post->txnid);
                            }else{
                                $result = [
                                    'error' => true,
                                    'response' => '' 
                                ];
                            }
                            
                            \DB::table('rp_log')->insert([
                                'ServiceName' => $post->type,
                                'header' => json_encode($header),
                                'body' => json_encode($parameters),
                                'response' => $result['response'],
                                'url' => $url,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
    
                            if(env('APP_ENV') == "local" || $result['error'] || $result['response'] == ''){
                                $result['response'] = json_encode([
                                    "message"=>"Pending",
                                    "statuscode"=>"001",
                                    "availlimit"=>"0",
                                    "total_limit"=>"0",
                                    "used_limit"=>"0",
                                    "Data"=>[
                                        ["fesessionid"=>"CP1801861S131436",
                                            "tranid"=>"pending",
                                            "rrn"=>"pending",
                                            "externalrefno"=>"MH357381218131436",
                                            "amount"=>"0",
                                            "responsetimestamp"=>"0",
                                            "benename"=>"",
                                            "messagetext"=>"Success",
                                            "code"=>"1",
                                            "errorcode"=>"1114",
                                            "mahatxnfee"=>"10.00"
                                        ]
                                    ]
                                ]);
                            }
    
                            $outputs['data'][] = array(
                                'amount' => $amount,
                                'status' => 'TXN',
                                'data' => $this->output($post, $result['response'], $user)
                            );
                        // } catch (\Exception $e) {
                        //     if(isset($report)){
                        //         $charge = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
                        //         $post['gst'] = 0;
                        //         User::where('id', $post->user_id)->increment('mainwallet', $report->charge - $post->gst - $charge);
                        //         Report::where('id', $post->reportid)->update([
                        //             'status'=> "pending",
                        //             'payid' => "Pedning" ,
                        //             'refno' => "Pedning",
                        //             'remark'=> "Pedning",
                        //             'gst'   => $post->gst,
                        //             'profit'=> $report->charge - $post->gst - $charge
                        //         ]);
                        //         \Myhelper::commission($report);
                        //         $outputs['data'][] = array(
                        //             'amount' => $amount,
                        //             'status' => 'TXN',
                        //             'data' => ['statuscode'=> 'TUP', 'status'=> 'Transaction Under Process','message'=> "Transaction Under Process", 'rrn' => "pedning", 'payid' => $post->reportid]
                        //         );
                        //     }else{
                        //         User::where('id', $user->id)->increment('mainwallet', $post->amount + $post->charge);
                        //         $outputs['data'][] = array(
                        //             'amount' => $amount,
                        //             'status' => 'TXF',
                        //             'data' => [
                        //                 "statuscode" => "TXF",
                        //                 "status" => "Same Transaction Repeat2",
                        //                 "message" => "Same Transaction Repeat",
                        //             ]
                        //         );
                        //     }
                        // }
                    }
                }else{
                    $outputs['data'][] = array(
                        'amount' => $amount,
                        'status' => 'TXF',
                        'data' => [
                            "statuscode" => "TXF",
                           // "status" => "Same Transaction Repeat1",
                            "message" => "Same Transaction Repeat",
                        ]
                    );
                }
            }
            sleep(1);
        }
        return response()->json($outputs, 200);
    }

    public function output($post, $response, $userdata)
    {
        $response = json_decode($response);
        switch ($post->type) {
            case 'verification':
                if(isset($response->response_code) && $response->response_code == "1"){
                    
                    $post['type'] = "getbeneficiary";
                    $benedata = $this->payment($post);
                    return response()->json(['statuscode'=> 'TXN', 'message'=> 'Transaction Successfull','data'=> $response->data, "benedata" => $benedata]);
                }elseif(isset($response->response_code) && $response->response_code == "0"){
                    return response()->json(['statuscode'=> 'RNF', 'message'=> 'Transaction Successfull', "data" => $response]);
                }else{
                    return response()->json(['statuscode'=> 'ERR', 'message'=> $response->message]);
                }
                break;
                
            case 'registration':
            case 'addbeneficiary':
            case 'benedelete':
            case 'refundotp':
                if(isset($response->response_code) && $response->response_code == "1"){
                    return response()->json(['statuscode'=> 'TXN', 'message'=> 'Transaction Successfull']);
                }else{
                    return response()->json(['statuscode'=> 'ERR', 'message'=> $response->message]);
                }
                break;
                
            case 'getrefund':
                if(isset($response->response_code) && $response->response_code == "1"){
                    Report::where('id', $post->transid)->update(['status' => "reversed"]);
                    \Myhelper::transactionRefund($post->transid);
                    return response()->json(['statuscode'=> 'TXN', 'message'=> 'Transaction Successfull']);
                }else{
                    return response()->json(['statuscode'=> 'ERR', 'message'=> $response->message]);
                }
                break;
                
            case 'getbeneficiary':
                if(isset($response->response_code) && $response->response_code == "1"){
                    return $response->data;
                }else{
                    return [];
                }
                break;
                
            case 'benedelete':
            case 'benedeletevalidate':
            case 'registration':
            case 'registrationValidate':
            case 'addbeneficiary':
            case 'outletotp':
            case 'mobilechange':
            case 'mobilechangeverify':
                return response()->json($response);
                break;

            case 'outletregister':
                $post['merchantLoginId'] = $data->outletid;
                $count = Fingagent::where('merchantLoginId', $post->merchantLoginId)->count();
                if($count == 0){
                    Fingagent::create($post->all());
                }
                return response()->json([
                    'status'=> 'TXN', 
                    'message'=> 'Transaction Successfull'
                ]);
            break;


            case 'accountverification':
               
                     if(isset($response->response_code) && $response->response_code == "1"){
                    
                    $balance = User::where('id', $userdata->id)->first(['mainwallet']);
                    $insert = [
                        'api_id' => $this->pdmt->id,
                        'provider_id' => $post->provider_id,
                        'option1' => $post->name,
                        'mobile' => $post->mobile,
                        'number' => $post->beneaccount,
                        'option2' => isset($response->data->name) ? $response->data->name : $post->benename,
                        'option3' => $post->benebank,
                        'option4' => $post->beneifsc,
                        'txnid' => $post->txnid,
                        'refno' => isset($response->data->utr) ? $response->data->utr : "none",
                        'amount' => $post->amount,
                        'charge' => $post->charge,
                        'transtype'=>'mainwallet',
                        'remark' => "Money Transfer",
                        'status' => 'success',
                        'user_id' => $userdata->id,
                        'credit_by' => $userdata->id,
                        'product' => 'dmt',
                        "via"    => "app",
                        'balance' => $balance->mainwallet,
                        'description' => $post->benemobile,
                        'trans_type'  => 'debit',
                    ];

                    User::where('id', $post->user_id)->decrement('mainwallet', $post->charge + $post->amount);
                    $report = Report::create($insert);
                    return response()->json(['statuscode'=> 'TXN', 'benename'=> $response->benename , 'message'=> "Beneficiary Name Fetched Successfully"]);
                }else{
                    return response()->json(['statuscode'=> 'TXR', 'message'=> $response->message]);
                }
                
                break;
            
            case 'transfer':
                
                $report = Report::where('id', $post->reportid)->first();
               // dd($report);
               // dd([($report->profit*$report->api->tds)/100,$report->api->tds,$report->profit]);
                //$user = \Auth::id(); 
                if(isset($response->response_code) && $response->response_code == "1"){
                    if(in_array($response->txn_status, ["0", "5"])){
                        
                        User::where('id', $post->user_id)->increment('mainwallet', $report->charge + $report->amount);
                        Report::where('id', $post->reportid)->update([
                            'status'=> 'failed',
                            'refno' => (isset($response->message))? $response->message : 'failed'
                        ]);
                        \Myhelper::commission($report);
                        return ['statuscode'=> 'TXF', 'message'=> 'Transaction Failed', "rrn" => (isset($response->message))? $response->message : 'failed', 'payid' => $post->reportid];
                    }elseif(isset($response->response_code) && $response->response_code == "11"){
                        User::where('id', $post->user_id)->increment('mainwallet', $report->charge + $report->amount);
                        Report::where('id', $post->reportid)->update([
                            'status'=> 'failed',
                            'refno' => (isset($response->message))? $response->message : 'failed'
                        ]);
                        return ['statuscode'=> 'TXF' , 'message'=> 'Transaction Failed', "rrn" => (isset($response->message))? $response->message : 'failed', 'payid' => $post->reportid];
                    }else{
                        $charge = \Myhelper::getCommission($report->amount, $userdata->scheme_id, $report->provider_id, $userdata->role->slug);
                          $post['gst'] = $this->getGst($report->charge  - $charge);
                        Report::where('id', $post->reportid)->update([
                            'status'=> "success",
                            'payid' => (isset($response->ackno))? $response->ackno : 'success',
                            'refno' => (isset($response->utr))? $response->utr : 'success',
                             'gst'   => $post->gst,
                            'profit'=> $report->charge - $post->gst - $charge
                        ]);
                        User::where('id', $post->user_id)->increment('mainwallet', $report->charge - $post->gst - $charge); 
                        \Myhelper::commission($report);
                          $msgn = 'Dear '.$report->option1.' Amt Rs '.$report->amount.' transfer to account '.$report->number.' at '.date("g:i:s A").' on '.date("d-m-Y").' processed. Powered By Paypark';
                          $sms     = \Myhelper::sms($report->mobile, $msgn);
                        // $post['debitAmount'] = ($report->profit*$report->api->tds)/100;
                        
                        //User::where('id', \Aurh::id())->decrement('mainwallet', $post->debitAmount);
                        
                        //dd([$post->amount, $userdata->scheme_id, $post->provider_id, $userdata->role->slug,$charge]);
                        
                        return ['statuscode'=> 'TXN','message'=> "Transaction Under Process", 'rrn' => (isset($response->utr))? $response->utr : $report->txnid, 'payid' => $post->reportid];
                    }
                }elseif(isset($response->response_code) && in_array($response->response_code, ["11", "13","3","4","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25"])){
                      User::where('id', $post->user_id)->increment('mainwallet', $report->charge + $report->amount);
                        Report::where('id', $post->reportid)->update([
                            'status'=> 'failed',
                            'refno' => (isset($response->message))? $response->message : 'failed'
                        ]);
                        return ['statuscode'=> 'TXF', 'message'=> 'Transaction Failed', "rrn" => (isset($response->message))? $response->message : 'failed', 'payid' => $post->reportid];
                }else{
                     $charge = \Myhelper::getCommission($report->amount, $userdata->scheme_id, $report->provider_id, $userdata->role->slug);
                        
                       $charge = \Myhelper::getCommission($post->amount, $userdata->scheme_id, $post->provider_id, $userdata->role->slug);
                        $post['gst'] = 0;
                        User::where('id', $post->user_id)->increment('mainwallet', $report->charge - $post->gst - $charge);
                        Report::where('id', $post->reportid)->update([
                             'status'=> "pending",
                            'payid' => (isset($response->ackno))? $response->ackno : 'success',
                            'refno' => (isset($response->utr))? $response->utr : 'success',
                            'gst'   => $post->gst,
                            'profit'=> $report->charge - $post->gst - $charge
                        ]);
                        \Myhelper::commission($report);
                        return ['statuscode'=> 'TUP','message'=> "Transaction Under Process", 'rrn' => (isset($response->utr))? $response->utr : $report->txnid, 'payid' => $post->reportid];
                }
               
                break;

            default:
                return response()->json(['statuscode'=> 'BPR', 'message'=> "Bad Parameter Request"]);
                break;
        }
    }
   public function getbank()
   {
       $data['banks'] = \DB::table('dmtbanks')->get();
        $data['state'] = \DB::table('circles')->get();
       return response()->json(['statuscode'=> 'TXN', 'message'=> "Data Fetched Successfully",'data'=>$data]);    
   }
    public function getCharge($amount)
    {
        if($amount < 1000){
            return 10;
        }else{
            return $amount*1/100;
        }
    }

    public function getGst($amount)
    {
        return $amount*18/100;
    }

    public function getTds($amount)
    {
        return $amount*5/100;
    }
    
    public function getToken($uniqueid)
    {
        $payload =  [
            "timestamp" => time(),
            "partnerId" => $this->pdmt->username,
            "reqid"     => $uniqueid
        ];
        
        $key = $this->pdmt->password;
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }
}
