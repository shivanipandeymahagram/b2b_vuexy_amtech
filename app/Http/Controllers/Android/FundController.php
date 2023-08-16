<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Aepsfundrequest;
use App\Models\Fundreport;
use App\Models\Fundbank;
use App\Models\Paymode;
use App\Models\PortalSetting;
use App\Models\Provider;
use App\Models\Aepsreport;
use App\Models\Report;
use App\Models\Api;
use Carbon\Carbon;
use App\Models\Microatmfundrequest;
use App\Models\Microatmreport;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;
use App\Models\Aepsuser;


class FundController extends Controller
{
    public $fundapi, $admin ,$sprintapi;

    public function __construct()
    {
        $this->fundapi = Api::where('code', 'fund')->first();
        $this->admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first();
       $this->sprintapi = Api::where('code', 'sprintpayout')->first(); 

    }
    
    public function transaction(Request $request)
    {
    	$rules = array(
            'apptoken' => 'required',
            'type' 	   => 'required',
            'user_id'  => 'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $request);
        if($validate != "no"){
        	return $validate;
        }

        $user = User::where('id', $request->user_id)->first();

        if(!$user){
            $output['statuscode'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        switch ($request->type) {
            
            
             case 'getbanklist' :
                 $banklist = \DB::table('dmtbanklist')->get() ;
                  return response()->json(['statuscode'=>'TXN','data'=>$banklist,'message'=>$response->message]);  
                break ;
            
            case 'bank':
                
                if ($this->pinCheck($request) == "fail") {
                    return response()->json(['statuscode' => "ERR", "message" => "Transaction Pin is incorrect"]);
                }
                
                $banksettlementtype = $this->banksettlementtype();

                if($banksettlementtype == "down"){
                    return response()->json(['statuscode' => "ERR", 'message' => "Aeps Settlement Down For Sometime"],200);
                }

                $user = User::where('id', $request->user_id)->first();
                
                if($user->id != "539"){
                  //  return response()->json(['statuscode' => "ERR", 'message' => "Aeps Settlement Down For Sometime"],200);
                }
                
                if(!\Myhelper::can('aeps_fund_request', $user->id)){
                  //  return response()->json(['statuscode' => "ERR", 'message' => "Permission not allowed"],200);
                }

                if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                    $rules = array(
                        'amount'    => 'required|numeric|min:10',
                        'bene_id'   => 'sometimes|required',
                     
                    );  
                }else{
                    $rules = array(
                        'amount'    => 'required|numeric|min:10'
                    );

                    $request['account'] = $user->account;
                    $request['bank']    = $user->bank;
                    $request['ifsc']    = $user->ifsc;
                }
                
                 $checkaccount = \DB::table('sprintpayoutusers')->where('bene_id',$request->bene_id)->first();              
                 if(!$checkaccount){
                    return response()->json(['statuscode' => "ERR", 'message'  => "Beneficiary Not Found"],400);
                }
                     $request['account'] = $checkaccount->account;
                     $request['bank']    = $checkaccount->bankname; 
                     $request['ifsc']    = $checkaccount->ifsc; 
                   
                  $validate = \Myhelper::FormValidator($rules,$request);
                if($validate != "no"){
                    return $validate;
                }

                if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                    User::where('id',$user->id)->update(['account' => $request->account, 'bank' => $request->bank, 'ifsc'=>$request->ifsc]);
                }

                $settlerequest = Aepsfundrequest::where('user_id', $user->id)->where('status', 'pending')->count();
                if($settlerequest > 0){
                    return response()->json(['statuscode' => "ERR", 'message' => "One request is already submitted"], 200);
                }

                $request['charge'] = 0;
                if($request->amount <= 25000){
                    $request['charge'] = $this->impschargeupto25();
                }

                if($request->amount > 25000){
                    $request['charge'] = $this->impschargeabove25();
                }

                if($user->aepsbalance < $request->amount + $request->charge){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Low aeps balance to make this request"],200);
                }

                if($banksettlementtype == "auto"){

                    $previousrecharge = Aepsfundrequest::where('account', $request->account)->where('amount', $request->amount)->where('user_id', $request->user_id)->whereBetween('created_at', [Carbon::now()->subSeconds(30)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(30)->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge){
                        return response()->json(['statuscode'=>"ERR", 'message' => "Transaction Allowed After 1 Min."]);
                    } 

                    $api = Api::where('code', 'sprintpayout')->first();
                    if(!$api && $api->status == 0)
                    {
                         return response()->json(['statuscode' => "ERR", 'message' => "Aeps Settlement Down For Sometime"],200);
                    }

                    do {
                        $request['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                    } while (Aepsfundrequest::where("payoutid", "=", $request->payoutid)->first() instanceof Aepsfundrequest);

                    $request['status']   = "pending";
                    $request['pay_type'] = "payout";
                    $request['payoutid'] = $request->payoutid;
                    $request['payoutref']= $request->payoutid;
                    $request['create_time']= Carbon::now()->toDateTimeString();
                    try {
                        $aepsrequest = Aepsfundrequest::create($request->all());
                    } catch (\Exception $e) {
                        return response()->json(['statuscode' => "ERR", 'message' => "Duplicate Transaction Not Allowed, Please Check Transaction History"]);
                    }
                    
                    $aepsreports['api_id'] = $api->id;
                    $aepsreports['payid']  = $aepsrequest->id;
                    $aepsreports['mobile'] = $user->mobile;
                    $aepsreports['refno']  = "success";
                    $aepsreports['aadhar'] = $request->account;
                    $aepsreports['amount'] = $request->amount;
                    $aepsreports['charge'] = $request->charge;
                    $aepsreports['bank']   = $request->bank."(".$request->ifsc.")";
                    $aepsreports['txnid']  = $request->payoutid;
                    $aepsreports['user_id']= $user->id;
                    $aepsreports['credited_by'] = $this->admin->id;
                    $aepsreports['balance']     = $user->aepsbalance;
                    $aepsreports['type']        = "debit";
                    $aepsreports['transtype']   = 'fund';
                    $aepsreports['status'] = 'success';
                    $aepsreports['remark'] = "Bank Settlement";

                    User::where('id', $aepsreports['user_id'])->decrement('aepsbalance',$aepsreports['amount']+$aepsreports['charge']);
                    $myaepsreport = Aepsreport::create($aepsreports);
                     $token = $this->getpayoutToken($request->user_id.Carbon::now()->timestamp);
                    $url=$this->sprintapi->url."payout/dotransaction"; //"https://paysprint.in/service-api/api/v1/service/payout/payout/dotransaction";
                    //dd($user);
                    $parameter = [
                            "bene_id" => $request->bene_id,
                            "amount" => $request->amount,
                            "refid" => $request->payoutid, //$post->txnid, 
                            "mode" => "IMPS",//$post->mode
                        ];
                    $query= json_encode($parameter);
                    //dd($request);
                    $header = array(
                    "Token: ".$token['token'],
                    "Authorisedkey:".$this->sprintapi->optional3,
                    'Content-Type' => 'application/json',
                    'Cookie: ci_session=bddf38ca34b061168405554bbce2e29439432868'
                    );
                    
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>$query,
                    CURLOPT_HTTPHEADER => array(
                    'Token: '.$token['token'],
                    'Authorisedkey:'.$this->sprintapi->optional3,
                    'Content-Type: application/json',
                    'Cookie: ci_session=bddf38ca34b061168405554bbce2e29439432868'
                    ),
                    ));
                    
                    $result = curl_exec($curl);
                    curl_close($curl);
                    $res = json_decode($result);
                    $this->createFile('sm_bankdotransction_', ['payload' => $query, 'url' => $url, 'response' => $res]);
                    //dd($res);
                    

                    $response = $res;
                   // dd($response);
                    if(isset($response->response_code) && $response->response_code == "1"){
                        Aepsfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "approved", "payoutref" => $response->ackno]);
                      return response()->json(['statuscode' => "TXN", "message" => "Aeps fund request submitted successfully  3", "txnid" => $aepsrequest->id],200);
                    }else{

                        User::where('id', $aepsreports['user_id'])->increment('aepsbalance', $aepsreports['amount']+$aepsreports['charge']);
                        Aepsreport::updateOrCreate(['id'=> $myaepsreport->id], ['status' => "failed", "refno" => isset($response->ackno) ? $response->ackno : $response->message]);

                        Aepsfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "rejected"]);
                        return response()->json(['statuscode' => "TXF", "message" => $response->message], 200);
                    }
              
                }else{
                    $request['pay_type'] = "manual";
                    $aepsrequest = Aepsfundrequest::create($request->all());
                }

                if($aepsrequest){
                    return response()->json(['statuscode' => "TXN", "message" => "Aeps fund request submitted successfully 33", "txnid" => $aepsrequest->id],200);
                }else{
                    return response()->json(['statuscode'=>"ERR", 'message' => "Something went wrong."]);
                }
                break;
                
              case 'listaccount' :
                $account  = \DB::table('sprintpayoutusers')->where('user_id',$request->user_id)->get() ;
                 if($account){
                     return response()->json(['statuscode'=>'TXN','message'=> "Account Fetched Successfully",'data'=>$account]);  
                 }
                return response()->json(['statuscode'=>'TXF','message'=> "Account Not Found"]);  
                break ;    
                
                

            case 'wallet':
                
                if ($this->pinCheck($request) == "fail") {
                    return response()->json(['statuscode' => "ERR", "message" => "Transaction Pin is incorrect"]);
                }
                
                $settlementtype = $this->settlementtype();

                if($settlementtype == "down"){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Aeps Settlement Down For Sometime"],200);
                }

                $rules = array(
                    'amount'    => 'required|numeric|min:1',
                );
        
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $user = User::where('id',$request->user_id)->first();

                if(!\Myhelper::can('aeps_fund_request', $user->id)){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Permission not allowed"],200);
                }
                
                $myrequest = Aepsfundrequest::where('user_id', $user->id)->where('status', 'pending')->count();
                if($myrequest > 0){
                    return response()->json(['statuscode'=>"ERR", 'message' => "One request is already submitted"], 200);
                }

                if($user->aepsbalance < $request->amount){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Low aeps balance to make this request"], 200);
                }

                if($settlementtype == "auto"){
                    $previousrecharge = Aepsfundrequest::where('type', $request->type)->where('amount', $request->amount)->where('user_id', $request->user_id)->whereBetween('created_at', [Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge > 0){
                        return response()->json(['statuscode'=>"ERR", 'message' => "Transaction Allowed After 5 Min."]);
                    }

                    $request['status'] = "approved";
                    $load = Aepsfundrequest::create($request->all());
                    $payee = User::where('id', $user->id)->first();
                    User::where('id', $payee->id)->decrement('aepsbalance', $request->amount);
                    $inserts = [
                        "mobile"  => $payee->mobile,
                        "amount"  => $request->amount,
                        "bank"    => $payee->bank,
                        'txnid'   => date('ymdhis'),
                        'refno'   => $request->refno,
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

                    if($request->type == "wallet"){
                        $provide = Provider::where('recharge1', 'aepsfund')->first();
                        User::where('id', $payee->id)->increment('mainwallet', $request->amount);
                        $insert = [
                            'number' => $payee->mobile,
                            'mobile' => $payee->mobile,
                            'provider_id' => $provide->id,
                            'api_id' => $this->fundapi->id,
                            'amount' => $request->amount,
                            'charge' => '0.00',
                            'profit' => '0.00',
                            'gst' => '0.00',
                            'tds' => '0.00',
                            'txnid' => $load->id,
                            'payid' => $load->id,
                            'refno' => $request->refno,
                            'description' =>  "Aeps Fund Recieved",
                            'remark' => $request->remark,
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
                    $load = Aepsfundrequest::create($request->all());
                }

                if($load){
                    return response()->json(['statuscode' => "TXN", "message" => "Aeps fund request submitted successfully", "txnid" => $load->id],200);
                }else{
                    return response()->json(['statuscode' => "ERR", 'message' => "Transaction Failed"]);
                }
                break;
                
             case 'matmbank':
                $banksettlementtype = $this->banksettlementtype();

                if($banksettlementtype == "down"){
                    return response()->json(['statuscode' => "ERR", 'message' => "MATM Settlement Down For Sometime"],200);
                }

                $user = User::where('id', $request->user_id)->first();
                
                if($user->id != "2"){
                    //return response()->json(['statuscode' => "ERR", 'message' => "Aeps Settlement Down For Sometime"],200);
                }
                
                if(!\Myhelper::can('aeps_fund_request', $user->id)){
                    return response()->json(['statuscode' => "ERR", 'message' => "Permission not allowed"],200);
                }

                if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                    $rules = array(
                        'amount'    => 'required|numeric|min:10',
                        'account'   => 'sometimes|required',
                        'bank'      => 'sometimes|required',
                        'ifsc'      => 'sometimes|required'
                    );
                }else{
                    $rules = array(
                        'amount'    => 'required|numeric|min:10'
                    );

                    $request['account'] = $user->account;
                    $request['bank']    = $user->bank;
                    $request['ifsc']    = $user->ifsc;
                }

                $validate = \Myhelper::FormValidator($rules,$request);
                if($validate != "no"){
                    return $validate;
                }

                if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                    User::where('id',$user->id)->update(['account' => $request->account, 'bank' => $request->bank, 'ifsc'=>$request->ifsc]);
                }

                $settlerequest = Microatmfundrequest::where('user_id', $user->id)->where('status', 'pending')->count();
                if($settlerequest > 0){
                    return response()->json(['statuscode' => "ERR", 'message' => "One request is already submitted"], 200);
                }

                $request['charge'] = 0;
                if($request->mode == "IMPS" && $request->amount <= 25000){
                    $request['charge'] = $this->impschargeupto25();
                }

                if($request->mode == "IMPS" && $request->amount > 25000){
                    $request['charge'] = $this->impschargeabove25();
                }

                if($request->mode == "NEFT"){
                    $request['charge'] = $this->neftcharge();
                }

                if($user->aepsbalance -$this->aepslocked() < $request->amount + $request->charge){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Low matm balance to make this request"]);
                }

                if($request->mode == "IMPS" && $banksettlementtype == "auto"){

                    $previousrecharge = Microatmfundrequest::where('account', $request->account)->where('amount', $request->amount)->where('user_id', $request->user_id)->whereBetween('created_at', [Carbon::now()->subSeconds(30)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(30)->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge){
                        return response()->json(['statuscode'=>"ERR", 'message' => "Transaction Allowed After 1 Min."]);
                    } 

                    $api = Api::where('code', 'psettlement')->first();

                    do {
                        $request['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                    } while (Microatmfundrequest::where("payoutid", "=", $request->payoutid)->first() instanceof Microatmfundrequest);

                    $request['status']   = "pending";
                    $request['pay_type'] = "payout";
                    $request['payoutid'] = $request->payoutid;
                    $request['payoutref']= $request->payoutid;
                    $request['create_time']= Carbon::now()->toDateTimeString();
                    try {
                        $aepsrequest = Microatmfundrequest::create($request->all());
                    } catch (\Exception $e) {
                        return response()->json(['statuscode' => "ERR", 'message' => "Duplicate Transaction Not Allowed, Please Check Transaction History"]);
                    }
                    
                    $aepsreports['api_id'] = $api->id;
                    $aepsreports['payid']  = $aepsrequest->id;
                    $aepsreports['mobile'] = $user->mobile;
                    $aepsreports['refno']  = "success";
                    $aepsreports['aadhar'] = $request->account;
                    $aepsreports['amount'] = $request->amount;
                    $aepsreports['charge'] = $request->charge;
                    $aepsreports['bank']   = $request->bank."(".$request->ifsc.")";
                    $aepsreports['txnid']  = $request->payoutid;
                    $aepsreports['user_id']= $user->id;
                    $aepsreports['credited_by'] = $this->admin->id;
                    $aepsreports['balance']     = $user->aepsbalance;
                    $aepsreports['type']        = "debit";
                    $aepsreports['transtype']   = 'fund';
                    $aepsreports['status'] = 'success';
                    $aepsreports['remark'] = "Bank Settlement";

                    User::where('id', $aepsreports['user_id'])->decrement('aepsbalance',$aepsreports['amount']+$aepsreports['charge']);
                    $myaepsreport = Microatmreport::create($aepsreports);
                    $url = $api->url;

                    $parameter = [
                        "apitxnid" => $request->payoutid,
                        "amount"   => $request->amount, 
                        "account"  => $request->account,
                        "name"     => $user->name,
                        "bank"     => $request->bank,
                        "ifsc"     => $request->ifsc,
                        "ip"       => $request->ip(),
                        "token"    => $api->username,
                        'callback' => url('api/callback/update/payout')
                    ];
                    $header = array("Content-Type: application/json");

                    if(env('APP_ENV') != "local"){
                        $result = \Myhelper::curl($url, 'POST', json_encode($parameter), $header, 'yes', '\App\Models\Aepsfundrequest', $request->payoutid);
                    }else{
                        $result = [
                            'error'    => true,
                            'response' => ''
                        ];
                    }

                    if($result['response'] == ''){
                        return response()->json(['status'=> "success"]);
                    }

                    $response = json_decode($result['response']);
                    if(isset($response->status) && in_array($response->status, ['TXN', 'TUP'])){
                        Microatmfundrequest::where('id', $aepsrequest->id)->update(['status' => "approved", "payoutref" => $response->rrn]);
                        return response()->json(['statuscode' => "TXN", "message" => "Aeps fund request submitted successfully", "txnid" => $aepsrequest->id],200);
                    }elseif(isset($response->status) && in_array($response->status, ['ERR', 'TXF'])){
                        User::where('id', $aepsreports['user_id'])->increment('aepsbalance', $aepsreports['amount']+$aepsreports['charge']);
                        Microatmreport::where('id', $myaepsreport->id)->update(['status' => "failed", "refno" => isset($response->rrn) ? $response->rrn : $response->message]);

                        Microatmfundrequest::where('id', $aepsrequest->id)->update(['status' => "rejected"]);
                        return response()->json(['statuscode' => "TXF", "message" => $response->message], 200);
                    }else{
                        Microatmfundrequest::where('id', $aepsrequest->id)->update(['status' => "pending"]);
                        return response()->json(['statuscode' => "TUP", "message" => "Transaction Under Pending"]);
                    }
                }else{
                    $request['pay_type'] = "manual";
                    $aepsrequest = Microatmfundrequest::create($request->all());
                }

                if($aepsrequest){
                    return response()->json(['statuscode' => "TXN", "message" => "Aeps fund request submitted successfully", "txnid" => $aepsrequest->id],200);
                }else{
                    return response()->json(['statuscode'=>"ERR", 'message' => "Something went wrong."]);
                }
                break;

            case 'matmwallet':
                $settlementtype = $this->settlementtype();

                if($settlementtype == "down"){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Aeps Settlement Down For Sometime"],200);
                }

                $rules = array(
                    'amount'    => 'required|numeric|min:1',
                );
        
                $validate = \Myhelper::FormValidator($rules, $request);
                if($validate != "no"){
                    return $validate;
                }

                $user = User::where('id',$request->user_id)->first();

                if(!\Myhelper::can('aeps_fund_request', $user->id)){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Permission not allowed"]);
                }
                
                $myrequest = Microatmfundrequest::where('user_id', $user->id)->where('status', 'pending')->count();
                if($myrequest > 0){
                    return response()->json(['statuscode'=>"ERR", 'message' => "One request is already submitted"]);
                }

                if($user->aepsbalance - $this->aepslocked() < $request->amount){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Low aeps balance to make this request"]);
                }

                if($settlementtype == "auto"){
                    $previousrecharge = Microatmfundrequest::where('type', $request->type)->where('amount', $request->amount)->where('user_id', $request->user_id)->whereBetween('created_at', [Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge > 0){
                        return response()->json(['statuscode'=>"ERR", 'message' => "Transaction Allowed After 5 Min."]);
                    }

                    $request['status'] = "approved";
                    $load = Microatmfundrequest::create($request->all());
                    $payee = User::where('id', $user->id)->first();
                    User::where('id', $payee->id)->decrement('aepsbalance', $request->amount);
                    $inserts = [
                        "mobile"  => $payee->mobile,
                        "amount"  => $request->amount,
                        "bank"    => $payee->bank,
                        'txnid'   => date('ymdhis'),
                        'refno'   => $request->refno,
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

                    Microatmreport::create($inserts);

                    if($request->type == "wallet"){
                        $provide = Provider::where('recharge1', 'aepsfund')->first();
                        User::where('id', $payee->id)->increment('mainwallet', $request->amount);
                        $insert = [
                            'number' => $payee->mobile,
                            'mobile' => $payee->mobile,
                            'provider_id' => $provide->id,
                            'api_id' => $this->fundapi->id,
                            'amount' => $request->amount,
                            'charge' => '0.00',
                            'profit' => '0.00',
                            'gst' => '0.00',
                            'tds' => '0.00',
                            'txnid' => $load->id,
                            'payid' => $load->id,
                            'refno' => $request->refno,
                            'description' =>  "Aeps Fund Recieved",
                            'remark' => $request->remark,
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
                    $load = Microatmfundrequest::create($request->all());
                }

                if($load){
                    return response()->json(['statuscode' => "TXN", "message" => "Aeps fund request submitted successfully", "txnid" => $load->id],200);
                }else{
                    return response()->json(['statuscode' => "ERR", 'message' => "Transaction Failed"]);
                }
                break;
    

            case 'request':
                if(!\Myhelper::can('fund_request', $request->user_id)){
                    return response()->json(['statuscode' => "ERR", "message" => "Permission not allowed"]);
                }

                $rules = array(
                    'fundbank_id'    => 'required|numeric',
                    'paymode'    => 'required',
                    'amount'    => 'required|numeric|min:100',
                    'ref_no'    => 'required|unique:fundreports,ref_no',
                    'paydate'    => 'required',
                    'apptoken'    => 'required'
                );
        
                $validate = \Myhelper::FormValidator($rules, $request);
                if($validate != "no"){
                    return $validate;
                }
                $user = User::where('id', $request->user_id)->first();

                $request['user_id'] = $user->id;
                $request['credited_by'] = $user->parent_id;
                if(!\Myhelper::can('setup_bank', $user->parent_id)){
                    $admin = User::whereHas('role', function ($q){
                        $q->where('slug', 'whitelable');
                    })->where('company_id', $user->company_id)->first(['id']);

                    if($admin && \Myhelper::can('setup_bank', $admin->id)){
                        $request['credited_by'] = $admin->id;
                    }else{
                        $admin = User::whereHas('role', function ($q){
                            $q->where('slug', 'admin');
                        })->first(['id']);
                        $request['credited_by'] = $admin->id;
                    }
                }

                $request['status'] = "pending";
                $action = Fundreport::create($request->all());
                if($action){
                    return response()->json(['statuscode' => "TXN", "message" => "Fund request send successfully", "txnid" => $action->id]);
                }else{
                    return response()->json(['statuscode' => "ERR", "message" => "Something went wrong, please try again."]);
                }
                break;

            case 'getfundbank':
                $rules = array(
                    'apptoken' => 'required',
                    'user_id'  => 'required|numeric'
                );
        
                $validate = \Myhelper::FormValidator($rules, $request);
                if($validate != "no"){
                    return $validate;
                }
                $user = User::where('id', $request->user_id)->first();
                $data['banks'] = Fundbank::where('user_id', $user->parent_id)->where('status', '1')->get();
                if(!\Myhelper::can('setup_bank', $user->parent_id)){
                    $admin = User::whereHas('role', function ($q){
                        $q->where('slug', 'whitelable');
                    })->where('company_id', $user->company_id)->first(['id']);

                    if($admin && \Myhelper::can('setup_bank', $admin->id)){
                        $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();
                        if(! $data['banks']){
                            $admin = User::whereHas('role', function ($q){
                            $q->where('slug', 'admin');
                                })->first(['id']);
                                $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();        
                        }
                    }else{
                        $admin = User::whereHas('role', function ($q){
                            $q->where('slug', 'admin');
                        })->first(['id']);
                        $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();
                    }
                }else{
                     $admin = User::whereHas('role', function ($q){
                            $q->where('slug', 'admin');
                        })->first(['id']);
                        $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();  
                }
                $data['paymodes'] = Paymode::where('status', '1')->get();
                return response()->json(['statuscode' => "TXN", "message" => "Get successfully", "data" => $data]);
                break;
            
            case 'transfer' :
            case 'return'   :
                if($request->type == "transfer" && !\Myhelper::can('fund_transfer', $request->user_id)){
                    return response()->json(['statuscode' => "ERR", "message" => "Permission not allowed"]);
                }

                if($request->type == "return" && !\Myhelper::can('fund_return', $request->user_id)){
                    return response()->json(['statuscode' => "ERR", "message" => "Permission not allowed1"]);
                }
                
                $provide = Provider::where('recharge1', 'fund')->first();
                $request['provider_id'] = $provide->id;
        
                $rules = array(
                    'amount' => 'required|numeric|min:1',
                    'id'     => 'required' 
                );
        
                $validate = \Myhelper::FormValidator($rules, $request);
		        if($validate != "no"){
		        	return $validate;
		        }
                
                $user  = User::where('id', $request->user_id)->first();
                $payee = User::where('id', $request->id)->first();
                
                if($request->type == "transfer"){
                    if($user->mainwallet < $request->amount){
                        return response()->json(['statuscode' => "ERR", "message" => "Insufficient wallet balance."]);
                    }
                }else{
                    if($payee->mainwallet - $payee->lockedamount < $request->amount){
                        return response()->json(['statuscode' => "ERR", "message" => "Insufficient balance in user wallet."]);
                    }
                }
                $request['txnid']   = 0;
                $request['option1'] = 0;
                $request['option2'] = 0;
                $request['option3'] = 0;
                $request['refno']   = date('ymdhis');
                return $this->paymentAction($request);
                break;
                
              case 'dynamicqr':
                  $url = 'https://paysprint.in/service-api/api/v1/service/upi/upi/dynamic_qr';
                  $token = $this->getToken($request->user_id.Carbon::now()->timestamp);
                  $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".$token['token'],
                    "Authorisedkey: NjJlNGEwZDBlNzJmOTU1NmVlNWU1NTI0ZmYxYTQ0MzI="
                  );
                 $user=User::where('id', $request->user_id)->first();
                 $merchentid = \DB::table('upimerchants')->where('user_id',$user->id)->first();
                 if(!$merchentid){
                    return response()->json(['statuscode' => "ERR",'message'=> 'Merchant Not Onboarded '], 200);     
                 }
                 $parameter['amount'] = $request->amount ;
                 $parameter['merchant_code']  = $merchentid->merchent;
                 $result = \Myhelper::curl($url, "POST", json_encode($parameter), $header, 'no');
                // dd($result) ;
                  \DB::table('rp_log')->insert([
                       'ServiceName' => "Create_Qr_dynamic",
                        'header' => json_encode($header),
                       'body' => json_encode([$parameter]),
                       'response' => $result['response'],
                       'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                   ]);
                 $response = json_decode($result['response']);
                 if(isset($response->response_code) && $response->response_code == "1"){
                       return response()->json(['statuscode' => "TXN",'data'=> $response ,'qrLink'=>$response->qr_link ?? '', 'message'=>$response->message ??'Somethis went wrong'], 200);
                 }else{
                       return response()->json(['statuscode' => "ERR",'message'=>$response->message ??'Somethis went wrong'], 200);
                 }     
                break;     
                
            case 'addaccount' : 
             
                  
                  $rules = array(
                    'account' => 'required|numeric|min:1',
                    'account_type'     => 'required' 
                );
        
                $validate = \Myhelper::FormValidator($rules, $request);
		        if($validate != "no"){
		        	return $validate;
		        }
                $token = $this->getpayoutToken($request->user_id.Carbon::now()->timestamp);
                $agent = Aepsuser::where('user_id',$request->user_id)->first();
                if(!$agent){
                  return response()->json(['statuscode'=>'TXF','message'=>'Merchant is not onboarded']);    
                }
                $user = User::where('id',$request->user_id)->first();
                if(!$user){
                  return response()->json(['statuscode'=>'ERR','message'=>'Bank Already Added For This User']);  
                }
                if($agent->status=="pending"){
                  return response()->json(['statuscode'=>'TXF','message'=>'Merchant is not active']);    
                }
                $header =  array(
                'Token: '.$token['token'],
                'Authorisedkey:'.$this->sprintapi->optional3,
                'Content-Type: application/json',
                );
                $parameter = [
                           "bankid"        => $request->bankid,
                           "merchant_code" => $agent->merchantLoginId,
                           "account"       => $request->account,
                           "ifsc"          => $request->ifsc,
                           "name"          => $request->name,
                           "account_type"  => $request->account_type ?? "PRIMARY"
                           
                           ];
                $query = json_encode($parameter);           
                $url=$this->sprintapi->url."payout/add";
        
                $bankname = \DB::table('dmtbanklist')->where('bankid',$request->bankid)->first();   
              
                $result = \Myhelper::curl($url, "POST",$query, $header, "yes");
                 \DB::table('rp_log')->insert([
                        'ServiceName' => "Add Account",
                        'header' => json_encode($header),
                        'body' => json_encode($query),
                        'response' => $result['response'],
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                //dd($result,$url,$request,$header);
                    
                if(isset($result['response']) && $result['response'] != ''){
                    $response = json_decode($result['response']);
                    
                    if($response->status == true && $response->response_code == 2){
                       
                     $action = \DB::table('sprintpayoutusers')->insert([
                            'user_id'=> $request->user_id,
                            'account'=>$request->account,
                            'ifsc'=>$request->ifsc,
                            'name'=>$request->name,
                            'bene_id'=>$response->bene_id,
                            'remark'=>$response->message,
                            'doc_upload'=>'pending',
                            'status'=>'success',
                            'bankname'  => $bankname->name ?? " " ,
                            "bankid"    => $request->bankid,
                            ]);
                     
                 //   User::where('id',$request->user_id)->update(['account' => $request->account, 'bank' => $bankname, 'ifsc'=>$request->ifsc,'bene_id1'=>$response->bene_id]);
                  //  return response()->json(['statuscode'=>'TXN','message'=>$response->message]);     
                   return response()->json(['statuscode'=>'TXN','message'=> "Account Added Successfully"]);   
                    }
                    elseif($response->status == TRUE && $response->response_code == 1){
                        
                      $action = \DB::table('sprintpayoutusers')->insert([
                            'user_id'=> $request->user_id,
                            'account'=>$request->account,
                            'ifsc'=>$request->ifsc,
                            'name'=>$request->name,
                            'bene_id'=>$response->bene_id,
                            'remark'=>$response->message,
                            'bankname'  => $bankname->name ?? " " ,
                            'doc_upload'=>'success',
                            'status'=>'pending',
                             "bankid"    => $request->bankid,
                            ]);
                  //  User::where('id',$request->user_id)->update(['account' => $request->account, 'bank' => $bankname, 'ifsc'=>$request->ifsc,'bene_id1'=>$response->bene_id]);        
                      
                    return response()->json(['statuscode'=>'TXN','message'=> "Account Added Successfully"]);   
                    }
                    elseif($response->status == false && isset($response->response_code) && $response->response_code == 4){
                        
                    return response()->json(['statuscode'=>'TXF','message'=>$response->message]);      
                    }
                    else{
                     
                    return response()->json(['statuscode'=>'TXF','message'=>$response->message ?? "Please contect to administrator"]);  
                    }
                    
                }    
             break;  
            case 'docupload' :
                  //'upload' => 'required|file|max:8192',
                   
                   $agent = Aepsuser::where('user_id', $request->user_id)->first();
                    if(!$agent){
                      return response()->json(['statuscode'=>'TXF','message'=>'Merchant is not onboarded']);    
                    }
                    if($request->hasFile('panimage')){
                        $pancardpics ='panimage'.$request->user_id.date('ymdhis').".".$request->file('panimage')->guessExtension();
                        $request->file('panimage')->move(public_path('kyc/'), $pancardpics);
                        
                        $panpicpath = public_path('kyc/').$pancardpics;
                
                    }
                    if($request->hasFile('aadharfront')){
                        $adharcardfrontpics ='aadharfront'.$request->user_id.date('ymdhis').".".$request->file('aadharfront')->guessExtension();
                        $request->file('aadharfront')->move(public_path('kyc/'), $adharcardfrontpics);
                        
                        $adharfrontpicpath = public_path('kyc/').$adharcardfrontpics;
                
                    }
                    if($request->hasFile('aadharback')){
                        $adharbacardpics ='aadckharback'.$request->user_id.date('ymdhis').".".$request->file('aadharback')->guessExtension();
                        $request->file('aadharback')->move(public_path('kyc/'), $adharbacardpics);
                        
                        $adharbackpicpath = public_path('kyc/').$adharbacardpics;
                
                    }
                    if($request->hasFile('passbook')){
                        $passbookpics ='passbook'.$request->user_id.date('ymdhis').".".$request->file('passbook')->guessExtension();
                        $request->file('passbook')->move(public_path('kyc/'), $passbookpics);
                        
                        $passbookpicpath = public_path('kyc/').$passbookpics;
                        
                    }
                    if (function_exists('curl_file_create')) { 
                        if($passbookpicpath){
                        $passbook = curl_file_create($passbookpicpath);
                      }
                     if($request->doctype == "PAN"){   
                     if($panpicpath){
                          $panimage = curl_file_create($panpicpath);
                     }
                    
                     }else{
                      if($adharfrontpicpath){
                      $adharfrontimage = curl_file_create($adharfrontpicpath);
                     }
                      if($adharbackpicpath){
                     $adharbackimage = curl_file_create($adharbackpicpath);
                     }
                     }
                    
                      
                    } else { // 
                    //   $passbook = '@' . realpath($passbookpicpath);
                    //   $panimage = '@' . realpath($panpicpath);
                      if($passbookpicpath){
                       $passbook = '@' . realpath($passbookpicpath);
                     }
                     
                    
                     if($request->doctype == "PAN"){   
                     if($panpicpath){
                          $panimage = curl_file_create($panpicpath);
                     }
                     }else{
                           if($adharfrontpicpath){
                      $adharfrontimage  = '@' . realpath($adharfrontpicpath);
                     }
                      if($adharbackpicpath){
                     $adharbackimage  = '@' . realpath($adharbackpicpath);
                     }
                     }
                    }
                        $user = User::where('id',$request->user_id)->first();
                       /* $agent =\DB::table('sprintpayoutusers')->where('bene_id',$post->bene_id)->update([
                            'remark'=>$response->message,
                            'doc_upload'=>'uploaded',
                            'status'=>'pending',
                            ]);*/
                       //  $url = 'https://paysprint.in/service-api/api/v1/service/payout/payout/uploaddocument';
                         $url = 'https://api.paysprint.in/api/v1/service/payout/payout/uploaddocument';
                        $payload['merchant_code'] =  $agent->merchantLoginId;
                        $payload['doctype'] = $request->doctype;
                        if($request->doctype == "PAN"){
                          $payload['panimage'] = $panimage;    
                        }else{
                           $payload['front_image'] = $adharfrontimage; 
                           $payload['back_image'] = $adharbackimage; 
                        }
                        $payload['passbook'] = $passbook;
                       
                        $payload['bene_id'] = $request->bene_id;
                        $res = JwtController::callApi($payload, $url);
                        
                        $this->createFile('sm_bankdocupload_', ['payload' => $payload, 'url' => $url, 'response' => $res]);
                         $response = json_decode($res);
                       // dd($res) ;
                        //   \DB::table('rp_log')->insert([ 
                        //         'ServiceName' => "Uploade Doc",
                        //         'header' => json_encode($header),
                        //         'body' => $request,
                        //         'response' => $result['response'],
                        //         'url' => $url,
                        //         'created_at' => date('Y-m-d H:i:s')
                        //     ]);
               
                    
                    if($response->status == true && $response->response_code == 1){
                       
                        $action = \DB::table('sprintpayoutusers')->where('bene_id',$request->bene_id)->update([
                                'remark'=>$response->message,
                                'doc_upload'=>'uploaded',
                                'status'=>'pending',
                                ]);
                               
                        return response()->json(['statuscode'=>'TXN','message'=>$response->message]);        
                    }
                    
                    elseif($response->status == false && $response->response_code == 1){
                        
                        $action = \DB::table('sprintpayoutusers')->where('bene_id',$request->bene_id)->update([
                            'remark'=>$response->message,
                            'doc_upload'=>'uploaded',
                            'status'=>'pending',
                            ]);
                        
                        return response()->json(['statuscode'=>'TXN','message'=>"Successfully uploaded"]);   
                    }
                       
                    else{
                     
                        $action = \DB::table('sprintpayoutusers')->where('bene_id',$request->bene_id)->update([
                            'remark'=>$response->message,
                            'doc_upload'=>'pending',
                            'status'=>'pending',
                            ]);
                       
                        return response()->json(['statuscode'=>'TXF','message'=>$response->message]);  
                    }
                
                   return response()->json(['statuscode'=>'TXN','message'=>"Successfully uploaded"]);   
             break;      
             break;   
             
            default :
                return response()->json(['statuscode' => "ERR", 'message' => "Bad Parameter Request"]);
            break;
        }
    }
 public function paymentAction($post)
    {
        $user = User::where('id', $post->id)->first();

        if($post->type == "transfer" || $post->type == "request"){
            $action = User::where('id', $post->id)->increment('mainwallet', $post->amount);
        }else{
            $action = User::where('id', $post->id)->decrement('mainwallet', $post->amount);
        }

        if($action){
            if($post->type == "transfer" || $post->type == "request"){
                $post['trans_type'] = "credit";
            }else{
                $post['trans_type'] = "debit";
            }

            $insert = [
                'number' => $user->mobile,
                'mobile' => $user->mobile,
                'provider_id' => $post->provider_id,
                'api_id' => $this->fundapi->id,
                'amount' => $post->amount,
                'charge' => '0.00',
                'profit' => '0.00',
                'gst' => '0.00',
                'tds' => '0.00',
                'apitxnid' => NULL,
                'txnid' => $post->txnid,
                'payid' => NULL,
                'refno' => $post->refno,
                'description' => NULL,
                'remark' => $post->remark,
                'option1' => $post->option1,
                'option2' => $post->option2,
                'option3' => $post->option3,
                'option4' => NULL,
                'status' => 'success',
                'user_id' => $user->id,
                'credit_by' => $post->user_id,
                'rtype' => 'main',
                'via' => 'portal',
                'adminprofit' => '0.00',
                'balance' => $user->mainwallet,
                'trans_type' => $post->trans_type,
                'product' => "fund ".$post->type
            ];
            $action = Report::create($insert);
            if($action){
                return $this->paymentActionCreditor($post);
            }else{
                return response()->json(['statuscode' => "ERR", "message" => "Technical error, please contact your service provider before doing transaction."]);
            }
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Fund transfer failed, please try again."]);
        }
    }

    public function paymentActionCreditor($post)
    {
        $payee = $post->id;
        $user = User::where('id', $post->user_id)->first();
        if($post->type == "transfer" || $post->type == "request"){
            $action = User::where('id', $user->id)->decrement('mainwallet', $post->amount);
        }else{
            $action = User::where('id', $user->id)->increment('mainwallet', $post->amount);
        }

        if($action){
            if($post->type == "transfer" || $post->type == "request"){
                $post['trans_type'] = "debit";
            }else{
                $post['trans_type'] = "credit";
            }

            $insert = [
                'number' => $user->mobile,
                'mobile' => $user->mobile,
                'provider_id' => $post->provider_id,
                'api_id' => $this->fundapi->id,
                'amount' => $post->amount,
                'charge' => '0.00',
                'profit' => '0.00',
                'gst' => '0.00',
                'tds' => '0.00',
                'apitxnid' => NULL,
                'txnid' => $post->txnid,
                'payid' => NULL,
                'refno' => $post->refno,
                'description' => NULL,
                'remark' => $post->remark,
                'option1' => $post->option1,
                'option2' => $post->option2,
                'option3' => $post->option3,
                'option4' => NULL,
                'status' => 'success',
                'user_id' => $user->id,
                'credit_by' => $payee,
                'rtype' => 'main',
                'via' => 'portal',
                'adminprofit' => '0.00',
                'balance' => $user->mainwallet,
                'trans_type' => $post->trans_type,
                'product' => "fund ".$post->type
            ];

            $action = Report::create($insert);
            if($action){
                return response()->json(['statuscode' => "TXN", "message" =>  "Transaction Successfull"]);
            }else{
                return response()->json(['statuscode' => "ERR", "message" => "Technical error, please contact your service provider before doing transaction."]);
            }
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Technical error, please contact your service provider before doing transaction."]);
        }
    }
    
   public function createvpa(Request $post){
      $rules = array(
         'mobile'    => 'required',
         'pan'       => 'required',
         'vpa'      => 'required',
         'user_id'   => 'required',
         'apptoken'   => 'required',
       );
      $validate = \Myhelper::FormValidator($rules, $post);
       if($validate != "no"){
          return $validate;
       }    
      $user=User::where('id', $post->user_id)->first();
      $merchentid = \DB::table('upimerchants')->where('user_id',$user->id)->first();
       if($merchentid){
          return response()->json(['status' => "ERR",'message'=> 'Merchant Onboarded '], 200);     
        }
        
      $url = 'https://paysprint.in/service-api/api/v1/service/upi/upi/createvpa';
      $token = $this->getToken($request->user_id.Carbon::now()->timestamp);
      $header = array(
        "Cache-Control: no-cache",
        "Content-Type: application/json",
        "Token: ".$token['token'],
        "Authorisedkey: NjJlNGEwZDBlNzJmOTU1NmVlNWU1NTI0ZmYxYTQ0MzI="
         );
      $request['refid'] = $this->transcode().rand(11, 99).Carbon::now()->timestamp;;
      $request['acc_no'] = "3333333333";
      $request['ifsccode'] = "SBIN0006201";
      $request['mobile'] =  $post->mobile ;
      $request['address']  = $user->address;
      $request['state'] = $user->state;
      $request['city'] = $user->city;
      $request['pincode'] = $user->pincode;
      $request['merchant_name'] = $user->name;
      $request['pan'] =  $post->pan ;
      $request['vpa']  = $post->vpa; 
      $result = \Myhelper::curl($url, "POST", json_encode($request), $header, 'no');
       \DB::table('rp_log')->insert([
           'ServiceName' => "Create_VPA",
            'header' => json_encode($header),
           'body' => json_encode([$request]),
           'response' => $result['response'],
           'url' => $url,
            'created_at' => date('Y-m-d H:i:s')
       ]);
       $response = json_decode($result['response']);
       if(isset($response->response_code) && $response->response_code == "1"){
             $data["merchent"] = $response->merchant_code;
             $data["user_id"] = $user->id;
             $user = \DB::table('upimerchants')->insert($data);
             return response()->json(['status'=>"success",'message' =>$response->message ?? "Agent Onboarding Successful"],200);    
      }else{
             return response()->json(['status'=>"failed" ,'message' => $response->message ?? "Something went wrong failed, please try again."],200);
      }
        
    }
    
      public function getToken($uniqueid)
    {
          $api =  Api::where('code', 'dynamicqr')->first();
        $payload =  [
            "timestamp" => time(),
            "partnerId" => $api->username,
            "reqid"     => $uniqueid
        ];
        
        $key = $api->password;
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }
    
  public function getpayoutToken($uniqueid)
    {
         $api = Api::where('code', 'dynamicqr')->first();
        $payload =  [
            "timestamp" => time(),
            "partnerId" => $this->sprintapi->username,
            "reqid"     => $uniqueid
        ];
        
        $key =  $this->sprintapi->password;
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }    
    
    
      public function createFile($file, $data){
        $data = json_encode($data);
        $file = 'aeps_'.$file.'_file.txt';
        $destinationPath=public_path()."/aeps_logs/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        \File::put($destinationPath.$file,$data);
        return $destinationPath.$file;
    }
}
