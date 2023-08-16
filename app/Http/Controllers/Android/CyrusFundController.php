<?php

namespace App\Http\Controllers\Android;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Aepsfundrequest;
use App\Models\Aepsreport;
use App\Models\Report;
use App\Models\Api;
use App\Models\Provider;
use Carbon\Carbon;


class CyrusFundController extends Controller
{
    public $fundapi, $admin, $runpaisafundapi;

    public function __construct()
    {
        $this->fundapi = Api::where('code', 'cyrusfund')->first();
        $this->runpaisafundapi = Api::where('code', 'runpaisafund')->first();
        $this->admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first();

    }

    public function transaction(Request $post)
    {
        if ($this->fundapi->status == "0") {
            return response()->json(['status' => "This function is down."],400);
        }
        $provide = Provider::where('recharge1', 'fund')->first();
        $post['provider_id'] = $provide->id;

        switch ($post->type) {

            case 'bank':

                if ($this->pinCheck($post) == "fail") {
                   // return response()->json(['status' => "Transaction Pin is incorrect"]);
                }
                $banksettlementtype = $this->banksettlementtype();
                $impschargeupto25 = $this->impschargeupto25();
                $impschargeabove25 = $this->impschargeabove25();

                if($banksettlementtype == "down"){
                    return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();
                $ifAcc = 0;
                if (!empty($post->account)) {
                    $user->account = $post->account;
                    $ifAcc = 1;
                }
                if (!empty($post->ifsc)) {
                    $user->ifsc = $post->ifsc;
                    $ifAcc = 1;
                }
                if (!empty($post->bank)) {
                    $user->bank = $post->bank;
                    $ifAcc = 1;
                }
                if ($ifAcc) {
                    $user->save(); 
                }
                if($user->account == '' || $user->ifsc == ''){ 
                    return response()->json(['status'=> "Bank Not added Please add bank"], 400);
                }

                $post['user_id'] = \Auth::id();
                 $rules = array(
                       
                        'amount'    => 'required|numeric|gt:1|max:200000',
                        'ifsc'   => 'sometimes|required|string|size:11',
                        'comments'   => 'sometimes|required'
                    );
                

                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                if (!empty($post->bankacccount)) {
                    $bankAcc = explode("-",$post->bankacccount);
                    $post['account'] = $bankAcc[0];
                    $post['bank']    = $bankAcc[2]; 
                    $post['ifsc']    = $bankAcc[1];
                } else {
                    $post['account'] = $user->account;
                    $post['bank']    = $user->bankname; 
                    $post['ifsc']    = $user->ifsc; 
                }
                
                 
                
        
                $settlerequest = Aepsfundrequest::where('user_id', \Auth::user()->id)->where('status', 'pending')->count();
                if($settlerequest > 0){
                    return response()->json(['status'=> "One request is already submitted"], 400);
                }

                $post['charge'] = 0;
                if($post->amount <= 25000){
                    $post['charge'] = $impschargeupto25;
                }

                if($post->amount > 25000){
                    $post['charge'] = $impschargeabove25;
                }
                
                if($user->aepsbalance < $post->amount + $post->charge){
                    return response()->json(['status'=>  "Low aeps balance to make this request."], 400);
                }

                if($banksettlementtype == "auto"){

                    $previousrecharge = Aepsfundrequest::where('account', $post->account)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subSeconds(30)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(30)->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge){
                        return response()->json(['status'=> "Transaction Allowed After 1 Min."]);
                    } 
                    
                    $api = Api::where('code', 'cyrusfund')->first();
                    do {
                        $post['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                    } while (Aepsfundrequest::where("payoutid", "=", $post->payoutid)->first() instanceof Aepsfundrequest);

                    $post['status']   = "pending";
                    $post['pay_type'] = "payout";
                    $post['mode'] = "IMPS";
                    $post['payoutid'] = $post->payoutid;
                    $post['payoutref']= $post->payoutid;
                    $post['create_time']= Carbon::now()->toDateTimeString();
                    try {
                        $aepsrequest = Aepsfundrequest::create($post->all());
                    } catch (\Exception $e) {
                        return response()->json(['status'=> "Duplicate Transaction Not Allowed, Please Check Transaction History"]);
                    }

                    /******************* Cyrus Post Data **********************/
                    $postData['MerchantID'] = $this->fundapi->username;
                    $postData['MerchantKey'] = $this->fundapi->optional1;
                    $postData['MethodName'] = "sendmoney";
                    $postData['TransferType']    = "IMPS"; 
                    $postData['Name']    = $user->name;
                    $postData['MobileNo']    = $user->mobile;
                    $postData['beneficiaryIFSC']    = $post->ifsc; 
                    $postData['beneficiaryAccount']    = $post->account;
                    $postData['comments']    = $post->comments; 
                    $postData['orderId']    = $post->payoutid; 
                    $postData['amount']    = $post->amount;
                    /*********************End Post Data ***********************/
                    $aepsreports['api_id'] = $api->id;
                    $aepsreports['payid']  = $aepsrequest->id;
                    $aepsreports['mobile'] = $user->mobile;
                    $aepsreports['refno']  = "success";
                    $aepsreports['aadhar'] = $post->account;
                    $aepsreports['amount'] = $post->amount;
                    $aepsreports['charge'] = $post->charge;
                    $aepsreports['bank']   = $post->bank."(".$post->ifsc.")";
                    $aepsreports['txnid']  = $post->payoutid;
                    $aepsreports['user_id']= $user->id;
                    $aepsreports['mode'] = "IMPS";
                    $aepsreports['credited_by'] = $this->admin->id;
                    $aepsreports['balance']     = $user->aepsbalance;
                    $aepsreports['type']        = "debit";
                    $aepsreports['transtype']   = 'fund';
                    $aepsreports['status'] = 'success';
                    $aepsreports['remark'] = "Bank Settlement";

                    User::where('id', $aepsreports['user_id'])->decrement('aepsbalance',$aepsreports['amount']+$aepsreports['charge']);
                    $myaepsreport = Aepsreport::create($aepsreports);

                    $url = $this->fundapi->url.'/api/PayoutAPI.aspx';
                    $header =  array(
                        'Content-Type: multipart/form-data',
                        
                        );
                        $query = http_build_query($postData, '', '&');
                    $result = \Myhelper::curl($url, "POST", $query, [], 'yes');

                     \DB::table('rp_log')->insert([
                        'ServiceName' => "Payout ",
                        'header' => json_encode([]),
                        'body' => $query,
                        'response' => json_encode($result),
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $response =  json_decode($result['response']);

                    if(isset($response->statuscode) && $response->statuscode == "DE_001"){
                        Aepsfundrequest::updateOrCreate(['id'=> $aepsrequest->id],
                         ['status' => "approved", "payoutref" => $response->data->rrn, 'apitxnid' => $response->data->cyrus_id]);
                        return response()->json(['status'=>"success"], 200);
                    }else if (isset($response->statuscode) && $response->statuscode == "ERR"){

                        User::where('id', $aepsreports['user_id'])->increment('aepsbalance', $aepsreports['amount']+$aepsreports['charge']);
                        Aepsreport::updateOrCreate(['id'=> $myaepsreport->id], ['status' => "failed", "refno" => isset($response->ackno) ? $response->ackno : $response->message]);

                        Aepsfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "rejected"]);
                        return response()->json(['status'=>'ERR', 'message' => $response->message], 400);
                    }
                    return response()->json(['status'=>"pending"], 200);
                }else{
                    $post['pay_type'] = "manual";
                    $request = Aepsfundrequest::create($post->all());
                }

                if($request){
                    return response()->json(['status'=>"success", 'message' => "Fund request successfully submitted"], 200);
                }else{
                    return response()->json(['status'=>"ERR", 'message' => "Something went wrong."], 400);
                }
                break;

            case 'wallet':
                    if ($this->pinCheck($post) == "fail") {
                      // return response()->json(['status' => "Transaction Pin is incorrect"]);
                   }
                   if(!\Myhelper::can('aeps_fund_request')){
                       return response()->json(['status' => "Permission not allowed"],400);
                   }
                   $settlementtype = $this->settlementtype();
   
                   if($settlementtype == "down"){
                       return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                   }
   
                   $rules = array(
                       'amount'    => 'required|numeric|min:1',
                   );
           
                   $validator = \Validator::make($post->all(), $rules);
                   if ($validator->fails()) {
                       return response()->json(['errors'=>$validator->errors()], 422);
                   }
   
                   $user = User::where('id',\Auth::user()->id)->first();
   
                   $request = Aepsfundrequest::where('user_id', \Auth::user()->id)->where('status', 'pending')->count();
                   if($request > 0){
                       return response()->json(['status'=> "One request is already submitted"], 400);
                   }
   
                   if(\Auth::user()->aepsbalance < $post->amount){
                       return response()->json(['status'=>  "Low aeps balance to make this request"], 400);
                   }
   
                   $post['user_id'] = \Auth::id();
   
                   if($settlementtype == "auto"){
                       $previousrecharge = Aepsfundrequest::where('type', $post->type)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                       if($previousrecharge > 0){
                           return response()->json(['status'=> "Transaction Allowed After 5 Min."]);
                       }
   
                       $post['status'] = "approved";
                       $load = Aepsfundrequest::create($post->all());
                       $payee = User::where('id', \Auth::id())->first();
                       User::where('id', $payee->id)->decrement('aepsbalance', $post->amount);
                       $inserts = [
                           "mobile"  => $payee->mobile,
                           "amount"  => $post->amount,
                           "bank"    => $payee->bank,
                           'txnid'   => date('ymdhis'),
                           'refno'   => $post->refno,
                           "user_id" => $payee->id,
                           "credited_by" => $user->id,
                           "balance"     => $payee->aepsbalance,
                           'type'        => "debit",
                           'transtype'   => 'fund',
                           'status'      => 'success',
                           'remark'      => "Move To Wallet Request",
                           'payid'       => "Wallet Transfer Request",
                           'aadhar'      => $payee->account
                       ];
   
                       Aepsreport::create($inserts);
   
                       if($post->type == "wallet"){
                           $provide = Provider::where('recharge1', 'aepsfund')->first();
                           User::where('id', $payee->id)->increment('mainwallet', $post->amount);
                           $insert = [
                               'number' => $payee->account,
                               'mobile' => $payee->mobile,
                               'provider_id' => $provide->id,
                               'api_id' => $this->fundapi->id,
                               'amount' => $post->amount,
                               'charge' => '0.00',
                               'profit' => '0.00',
                               'gst' => '0.00',
                               'tds' => '0.00',
                               'txnid' => $load->id,
                               'payid' => $load->id,
                               'refno' => $post->refno,
                               'description' =>  "Aeps Fund Recieved",
                               'remark' => $post->remark,
                               'option1' => $payee->name,
                               'status' => 'success',
                               'user_id' => $payee->id,
                               'credit_by' => $payee->id,
                               'rtype' => 'main',
                               'via' => 'portal',
                               'balance' => $payee->mainwallet,
                               'trans_type' => 'credit',
                               'product' => "fund request"
                           ];
   
                           Report::create($insert);
                       }
                   }else{
                       $load = Aepsfundrequest::create($post->all());
                   }
   
                   if($load){
                       return response()->json(['status' => "success"],200);
                   }else{
                       return response()->json(['status' => "fail"],200);
                   }
                   break;
   

            default:
                # code...
                break;
        }
    }

    public function transactionRunpaisa(Request $post)
    {
        if ($this->fundapi->status == "0") {
            return response()->json(['status' => "This function is down."],400);
        }
        $provide = Provider::where('recharge1', 'fund')->first();
        $post['provider_id'] = $provide->id;

        switch ($post->type) {

            case 'bank':

                if ($this->pinCheck($post) == "fail") {
                   // return response()->json(['status' => "Transaction Pin is incorrect"]);
                }
                $banksettlementtype = $this->banksettlementtype();
                $impschargeupto25 = $this->impschargeupto25();
                $impschargeabove25 = $this->impschargeabove25();

                if($banksettlementtype == "down"){
                    return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();
                $ifAcc = 0;
                if (!empty($post->account)) {
                    $user->account = $post->account;
                    $ifAcc = 1;
                }
                if (!empty($post->ifsc)) {
                    $user->ifsc = $post->ifsc;
                    $ifAcc = 1;
                }
                if (!empty($post->bank)) {
                    $user->bank = $post->bank;
                    $ifAcc = 1;
                }
                if ($ifAcc) {
                    $user->save(); 
                }
                if($user->account == '' || $user->ifsc == ''){ 
                    return response()->json(['status'=> "Bank Not added Please add bank"], 400);
                }

                $post['user_id'] = \Auth::id();
                 $rules = array(
                       
                        'amount'    => 'required|numeric|gt:1|max:200000',
                        'ifsc'   => 'sometimes|required|string|size:11',
                        'comments'   => 'sometimes|required'
                    );
                

                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                if (!empty($post->bankacccount)) {
                    $bankAcc = explode("-",$post->bankacccount);
                    $post['account'] = $bankAcc[0];
                    $post['bank']    = $bankAcc[2]; 
                    $post['ifsc']    = $bankAcc[1];
                } else {
                    $post['account'] = $user->account;
                    $post['bank']    = $user->bankname; 
                    $post['ifsc']    = $user->ifsc; 
                }
                
                 
                
        
                $settlerequest = Aepsfundrequest::where('user_id', \Auth::user()->id)->where('status', 'pending')->count();
                if($settlerequest > 0){
                    return response()->json(['status'=> "One request is already submitted"], 400);
                }

                $post['charge'] = 0;
                if($post->amount <= 25000){
                    $post['charge'] = $impschargeupto25;
                }

                if($post->amount > 25000){
                    $post['charge'] = $impschargeabove25;
                }
                
                if($user->aepsbalance < $post->amount + $post->charge){
                    return response()->json(['status'=>  "Low aeps balance to make this request."], 400);
                }

                if($banksettlementtype == "auto"){

                    $previousrecharge = Aepsfundrequest::where('account', $post->account)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subSeconds(30)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(30)->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge){
                        return response()->json(['status'=> "Transaction Allowed After 1 Min."]);
                    } 
                    
                    $api = Api::where('code', 'runpaisafund')->first();
                    do {
                        $post['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                    } while (Aepsfundrequest::where("payoutid", "=", $post->payoutid)->first() instanceof Aepsfundrequest);

                    $post['status']   = "pending";
                    $post['pay_type'] = "payout";
                    $post['mode'] = "IMPS";
                    $post['payoutid'] = $post->payoutid;
                    $post['payoutref']= $post->payoutid;
                    $post['create_time']= Carbon::now()->toDateTimeString();
                    try {
                        $aepsrequest = Aepsfundrequest::create($post->all());
                    } catch (\Exception $e) {
                        return response()->json(['status'=> "Duplicate Transaction Not Allowed, Please Check Transaction History"]);
                    }

                    /******************* Cyrus Post Data **********************/
                    $authToken = $this->getRunpaisaToken();  
                     
                         
                          $postData =[
                              "amount"         => $post->amount,
                              "orderId"     => $post->payoutid,
                              "paymentMode"   => "IMPS",
                              "callbackurl"        => url('').'/api/callback/update/runpaisa',
                              "beneficiaryName" => $user->name,
                              "beneficiaryAccountNumber" => $post->account,
                              "beneficiaryIfscCode" => $post->ifsc,
                            ];

                    /*********************End Post Data ***********************/
                    $aepsreports['api_id'] = $api->id;
                    $aepsreports['payid']  = $aepsrequest->id;
                    $aepsreports['mobile'] = $user->mobile;
                    $aepsreports['refno']  = "success";
                    $aepsreports['aadhar'] = $post->account;
                    $aepsreports['amount'] = $post->amount;
                    $aepsreports['charge'] = $post->charge;
                    $aepsreports['bank']   = $post->bank."(".$post->ifsc.")";
                    $aepsreports['txnid']  = $post->payoutid;
                    $aepsreports['user_id']= $user->id;
                    $aepsreports['mode'] = "IMPS";
                    $aepsreports['credited_by'] = $this->admin->id;
                    $aepsreports['balance']     = $user->aepsbalance;
                    $aepsreports['type']        = "debit";
                    $aepsreports['transtype']   = 'fund';
                    $aepsreports['status'] = 'success';
                    $aepsreports['remark'] = "Bank Settlement";

                    User::where('id', $aepsreports['user_id'])->decrement('aepsbalance',$aepsreports['amount']+$aepsreports['charge']);
                    $myaepsreport = Aepsreport::create($aepsreports);
                    $header = array(
                        'token: '.$authToken,
                        'client_id: '.$api->optional1,
                        'Content-Type: multipart/form-data'
                    );
                    $url = $this->runpaisafundapi->optional2."/payment";

                    $query = http_build_query($postData, '', '&');
                    $result = \Myhelper::curl($url, "POST", $postData, $header, 'yes', 'Runpaisa');

                     \DB::table('rp_log')->insert([
                        'ServiceName' => "Payout ",
                        'header' => json_encode($header),
                        'body' => $query,
                        'response' => json_encode($result),
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $response =  json_decode($result['response']);

                    if(isset($response->status) && $response->status == "ACCEPTED") {
                       /* Aepsfundrequest::updateOrCreate(['id'=> $aepsrequest->id],
                         ['status' => "approved"]); */
                        return response()->json(['status'=>"success"], 200);
                    }else if (isset($response->status) && ($response->status == "ERR" || $response->status == "FAIL")){

                        User::where('id', $aepsreports['user_id'])->increment('aepsbalance', $aepsreports['amount']+$aepsreports['charge']);
                        Aepsreport::updateOrCreate(['id'=> $myaepsreport->id], ['status' => "failed", "refno" => isset($response->ackno) ? $response->ackno : $response->message]);

                        Aepsfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "rejected"]);
                        return response()->json(['status'=>'ERR', 'message' => $response->message], 400);
                    }
                    return response()->json(['status'=>"pending"], 200);
                }else{
                    $post['pay_type'] = "manual";
                    $request = Aepsfundrequest::create($post->all());
                }

                if($request){
                    return response()->json(['status'=>"success", 'message' => "Fund request successfully submitted"], 200);
                }else{
                    return response()->json(['status'=>"ERR", 'message' => "Something went wrong."], 400);
                }
                break;

            case 'wallet':
                    if ($this->pinCheck($post) == "fail") {
                      // return response()->json(['status' => "Transaction Pin is incorrect"]);
                   }
                   if(!\Myhelper::can('aeps_fund_request')){
                       return response()->json(['status' => "Permission not allowed"],400);
                   }
                   $settlementtype = $this->settlementtype();
   
                   if($settlementtype == "down"){
                       return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                   }
   
                   $rules = array(
                       'amount'    => 'required|numeric|min:1',
                   );
           
                   $validator = \Validator::make($post->all(), $rules);
                   if ($validator->fails()) {
                       return response()->json(['errors'=>$validator->errors()], 422);
                   }
   
                   $user = User::where('id',\Auth::user()->id)->first();
   
                   $request = Aepsfundrequest::where('user_id', \Auth::user()->id)->where('status', 'pending')->count();
                   if($request > 0){
                       return response()->json(['status'=> "One request is already submitted"], 400);
                   }
   
                   if(\Auth::user()->aepsbalance < $post->amount){
                       return response()->json(['status'=>  "Low aeps balance to make this request"], 400);
                   }
   
                   $post['user_id'] = \Auth::id();
   
                   if($settlementtype == "auto"){
                       $previousrecharge = Aepsfundrequest::where('type', $post->type)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                       if($previousrecharge > 0){
                           return response()->json(['status'=> "Transaction Allowed After 5 Min."]);
                       }
   
                       $post['status'] = "approved";
                       $load = Aepsfundrequest::create($post->all());
                       $payee = User::where('id', \Auth::id())->first();
                       User::where('id', $payee->id)->decrement('aepsbalance', $post->amount);
                       $inserts = [
                           "mobile"  => $payee->mobile,
                           "amount"  => $post->amount,
                           "bank"    => $payee->bank,
                           'txnid'   => date('ymdhis'),
                           'refno'   => $post->refno,
                           "user_id" => $payee->id,
                           "credited_by" => $user->id,
                           "balance"     => $payee->aepsbalance,
                           'type'        => "debit",
                           'transtype'   => 'fund',
                           'status'      => 'success',
                           'remark'      => "Move To Wallet Request",
                           'payid'       => "Wallet Transfer Request",
                           'aadhar'      => $payee->account
                       ];
   
                       Aepsreport::create($inserts);
   
                       if($post->type == "wallet"){
                           $provide = Provider::where('recharge1', 'aepsfund')->first();
                           User::where('id', $payee->id)->increment('mainwallet', $post->amount);
                           $insert = [
                               'number' => $payee->account,
                               'mobile' => $payee->mobile,
                               'provider_id' => $provide->id,
                               'api_id' => $this->fundapi->id,
                               'amount' => $post->amount,
                               'charge' => '0.00',
                               'profit' => '0.00',
                               'gst' => '0.00',
                               'tds' => '0.00',
                               'txnid' => $load->id,
                               'payid' => $load->id,
                               'refno' => $post->refno,
                               'description' =>  "Aeps Fund Recieved",
                               'remark' => $post->remark,
                               'option1' => $payee->name,
                               'status' => 'success',
                               'user_id' => $payee->id,
                               'credit_by' => $payee->id,
                               'rtype' => 'main',
                               'via' => 'portal',
                               'balance' => $payee->mainwallet,
                               'trans_type' => 'credit',
                               'product' => "fund request"
                           ];
   
                           Report::create($insert);
                       }
                   }else{
                       $load = Aepsfundrequest::create($post->all());
                   }
   
                   if($load){
                       return response()->json(['status' => "success"],200);
                   }else{
                       return response()->json(['status' => "fail"],200);
                   }
                   break;
   

            default:
                # code...
                break;
        }
    }


    public function getRunpaisaToken(){
	     

        $request = [];
        $header = array(
            'client_id: ' . $this->runpaisafundapi->optional1,
            'username: ' . $this->runpaisafundapi->username,    
            'password: '.$this->runpaisafundapi->password, 
            'Content-Type: application/json', 
        );
     
        $url = $this->runpaisafundapi->url."/token" ;  
        $result = \Myhelper::curl($url, "POST", json_encode($request), $header, 'yes', 1, 'runpaisa', 'Runpaisa');
        $response['data'] = json_decode($result['response']);
        if (isset($response['data']->status) && $response['data']->status == 'SUCCESS') {
            return $response['data']->data->token;
        }
        return "";
	}
	

}
