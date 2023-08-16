<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Utiid;
use App\Models\Aepsfundrequest;
use App\Models\Aepsreport;
use App\User;
use App\Models\Aepsuser;
use App\Models\Provider;
use App\Models\Fingagent;
use App\Models\Api;
use App\Models\Fundreport;

class CallbackController extends Controller
{
    public function callback(Request $post, $api)
    {     \DB::table('microlog')->insert(["product" => $api, 'response' => json_encode($post->all())]);
        switch ($api) {
       
            case 'payout':
                $fundreport = Aepsfundrequest::where('payoutid', $post->txnid)->first();
                if($fundreport && in_array($fundreport->status , ['pending', 'approved'])){
                    if(strtolower($post->status) == "success"){
                        $update['status'] = "approved";
                        $update['payoutref'] = $post->refno;
                    }elseif (strtolower($post->status) == "reversed") {
                        $update['status'] = "rejected";
                        $update['payoutref'] = $post->refno;
                    }else{
                        $update['status'] = "pending";
                    }
                    
                    if($update['status'] != "pending"){
                        $action = Aepsfundrequest::where('id', $fundreport->id)->update($update);
                        if ($action) {
                            if($update['status'] == "rejected"){
                                $report = Aepsreport::where('txnid', $fundreport->payoutid)->update(['status' => "reversed"]);
                                $report = Aepsreport::where('txnid', $fundreport->payoutid)->first();
                                $aepsreports['api_id'] = $report->api_id;
                                $aepsreports['payid']  = $report->payid;
                                $aepsreports['mobile'] = $report->mobile;
                                $aepsreports['refno']  = $report->refno;
                                $aepsreports['aadhar'] = $report->aadhar;
                                $aepsreports['amount'] = $report->amount;
                                $aepsreports['charge'] = $report->charge;
                                $aepsreports['bank']   = $report->bank;
                                $aepsreports['txnid']  = $report->id;
                                $aepsreports['user_id']= $report->user_id;
                                $aepsreports['credited_by'] = $report->credited_by;
                                $aepsreports['balance']     = $report->user->aepsbalance;
                                $aepsreports['type']        = "credit";
                                $aepsreports['transtype']   = 'fund';
                                $aepsreports['status'] = 'refunded';
                                $aepsreports['remark'] = "Bank Settlement";
                                Aepsreport::create($aepsreports);
                                User::where('id', $aepsreports['user_id'])->increment('aepsbalance',$aepsreports['amount']+$aepsreports['charge']);
                            }
                        }
                    }
                }
                break;
            
            	case 'runpaisa':
                    $txnid =  isset($post['ORDERID']) ? $post['ORDERID'] : "";
                    $event = isset($post['STATUS_CODE']) ? $post['STATUS_CODE'] : "";

                    if (in_array($event, ['RPP000'])) {
                        $fundreport = Aepsfundrequest::where('payoutid', $txnid)->first();
                        if($fundreport && in_array($fundreport->status , ['pending', 'approved'])){
                           
                            if($event == "RPP000"){
                                $update['status'] = "approved";
                                $update['payoutref'] = @$post['UTRNO'];
                            }elseif (in_array($event, ['RP006', 'RP007', 'RP008', 'RP009', 'RP010', 'RP011', 'RP012', 'RP013', 'RP017'])) {
                                $update['status'] = "rejected";
                                $update['payoutref'] = @$post['UTRNO'];
                            }else{
                                $update['status'] = "pending";
                            }
                            
                            if($update['status'] != "pending"){
                                $action = Aepsfundrequest::where('id', $fundreport->id)->update($update);
                                if ($action) {
                                    if($update['status'] == "rejected"){
                                        $report = Aepsreport::where('txnid', $fundreport->payoutid)
                                        ->update(['status' => "reversed"]);
                                        $report = Aepsreport::where('txnid', $fundreport->payoutid)->first();
                                        $aepsreports['api_id'] = $report->api_id;
                                        $aepsreports['payid']  = $report->payid;
                                        $aepsreports['mobile'] = $report->mobile;
                                        $aepsreports['refno']  = $report->refno;
                                        $aepsreports['aadhar'] = $report->aadhar;
                                        $aepsreports['amount'] = $report->amount;
                                        $aepsreports['charge'] = $report->charge;
                                        $aepsreports['bank']   = $report->bank;
                                        $aepsreports['txnid']  = $report->id;
                                        $aepsreports['user_id']= $report->user_id;
                                        $aepsreports['credited_by'] = $report->credited_by;
                                        $aepsreports['balance']     = $report->user->aepsbalance;
                                        $aepsreports['type']        = "credit";
                                        $aepsreports['transtype']   = 'fund';
                                        $aepsreports['status'] = 'refunded';
                                        $aepsreports['remark'] = "Bank Settlement";
                                        Aepsreport::create($aepsreports);
                                        User::where('id', $aepsreports['user_id'])->increment('aepsbalance',$aepsreports['amount']+$aepsreports['charge']);
                                    }
                                }
                            }
                        }
                    }
                   
                    break;
            case 'cyruspayout':
                \DB::table('microlog')->insert(["product" => "cyruspayout", 'response' => json_encode($post->all())]);
                    $postData = json_decode($post->data);

                $fundreport = Aepsfundrequest::where('payoutid', $postData->orderId)->first();
                    if($fundreport && in_array($fundreport->status , ['pending', 'approved'])){
                        if(strtolower($post->status) == "SUCCESS"){
                            $update['status'] = "approved";
                            $update['payoutref'] = $postData->rrn;
                        }elseif (strtolower($post->status) == "FAILURE") {
                            $update['status'] = "rejected";
                            $update['payoutref'] =  @$postData->rrn;
                        }else{
                            $update['status'] = "pending";
                        }
                        
                        if($update['status'] != "pending"){
                            $action = Aepsfundrequest::where('id', $fundreport->id)->update($update);
                            if ($action) {
                                if($update['status'] == "rejected"){
                                    $report = Aepsreport::where('txnid', $fundreport->payoutid)->update(['status' => "reversed"]);
                                    $report = Aepsreport::where('txnid', $fundreport->payoutid)->first();
                                    $aepsreports['api_id'] = $report->api_id;
                                    $aepsreports['payid']  = $report->payid;
                                    $aepsreports['mobile'] = $report->mobile;
                                    $aepsreports['refno']  = $report->refno;
                                    $aepsreports['aadhar'] = $report->aadhar;
                                    $aepsreports['amount'] = $report->amount;
                                    $aepsreports['charge'] = $report->charge;
                                    $aepsreports['bank']   = $report->bank;
                                    $aepsreports['txnid']  = $report->id;
                                    $aepsreports['user_id']= $report->user_id;
                                    $aepsreports['credited_by'] = $report->credited_by;
                                    $aepsreports['balance']     = $report->user->aepsbalance;
                                    $aepsreports['type']        = "credit";
                                    $aepsreports['transtype']   = 'fund';
                                    $aepsreports['status'] = 'refunded';
                                    $aepsreports['remark'] = "Bank Settlement";
                                    Aepsreport::create($aepsreports);
                                    User::where('id', $aepsreports['user_id'])->increment('aepsbalance',$aepsreports['amount']+$aepsreports['charge']);
                                }
                            }
                        }
                    }
                    break;
             case 'cmsdebit':
                \DB::table('paytmlogs')->insert(['txnid' => "Fingpay cms", "response" => 'CMSCALLBACK'.json_encode($post->all())]);
                $apidata  = Api::where('code', 'fingcms')->first();
                //dd($post->header('Hash'));
                /*$hash = base64_encode(hash("sha256",(json_encode([$post->all()]).$apidata->username.$apidata->optional2), True));
                //dd($hash);
                if($hash != $post->header('Hash')){
                    return response()->json(['status'=>false,"errorMessage"=>"Hash Not Matched"]);
                }*/
                 $merchantTransactionId = $this->transcode().rand(1111111111, 9999999999);
                 if(isset($post->bcLoginId)){
                    $agent = Fingagent::where('merchantLoginId', $post->bcLoginId)->first();
                    if(!$agent){
                         return response()->json(['status'=>false,"errorMessage"=>"Agent not exist."]);
                    }
                    
                     $user = User::where('id', $agent->user_id)->first();
                     
                     if($user->mainwallet < $post->amount){
                         return response()->json(['status'=>false,"errorMessage"=>"Insufficient wallet balance"]);
                     }
                    
                        //transaction initiate
                        if(isset($post->transactionStatus)){
                            if($post->transactionStatus == 'I'){
                                
                                $provider = Provider::where('recharge1','cmsservice')->first();
                                $post['provider_id'] = $provider->id;
                                if($post->amount >= 500){ // according to client
                                    $post['profit'] = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
                                }else{
                                    $post['profit'] = "0";
                                }
                                
                                    $insert = [
                                        'number' => $user->mobile,
                                        'mobile' => $user->mobile,
                                        'provider_id' => $post->provider_id,
                                        'api_id' => $provider->api_id,
                                        'amount' => $post->amount,
                                        'charge' => '0.00',
                                        'profit' => $post->profit,
                                        'gst' => '0.00',
                                        'tds' => '0.00',
                                        'apitxnid' => $post->fpTransactionId,
                                        'txnid' => $merchantTransactionId,
                                        'payid' => NULL,
                                        'refno' => NULL,
                                        'description' => "Transaction Initiated",
                                        'remark' => $post->errorMessage,
                                        'option1' => NULL,
                                        'option3' => NULL,
                                        'option4' => NULL,
                                        'status' => 'pending',
                                        'user_id' => $user->id,
                                        'credit_by' => $user->id,
                                        'rtype' => 'main',
                                        'via' => 'portal',
                                        'balance' => $user->mainwallet,
                                        'trans_type' => 'debit',
                                        'product' => "cms"
                                    ];
                                    $action = Report::create($insert);
            
                                    if($action){
                                         User::where('id', $user->id)->decrement('mainwallet', $post->amount - $post->profit);
                                       
                                    }
                                    
                                  return response()->json(['status'=>true,"errorMessage"=>"Transction Initiated","merchantTransactionId"=>$merchantTransactionId]);  
                                
                            }
                        }
                    
                        //update transaction
                        $report = Report::where('txnid', $post->merchantTransactionId)->first();
                        if(!$report){
                            return response()->json(['status'=>false,"errorMessage"=>"Unauthorised transaction"]);
                        }
                         
                        if(isset($post->transactionStatus)){
                            if($report->status == 'pending'){
                                if($post->transactionStatus == 'S'){
                                    $update['status'] = "success";
                                    $update['description'] = isset($post->errorMessage) ? $post->errorMessage : '';
                                    $update['remark'] = isset($post->remarks) ? $post->remarks : '';
                                    $update['refno'] = $post->fpTransactionId;
                                    
                                }else{
                                     User::where('id', $report->user_id)->increment('mainwallet', $report->amount - $report->profit);
                                    $update['status'] = "failed";
                                    $update['description'] = 'Transaction failed';
                                    $update['remark'] = isset($post->remarks) ? $post->remarks : 'failed';
                                    $update['refno'] = isset($post->fpTransactionId) ? $post->fpTransactionId : 'failed';
                                    
                                }
                                
                                $action = Report::where('id', $report->id)->update($update);
                            }
    
                        }
                    
                    
                }
                 
                break;
            default:
                return response('');
                break;
        }
    }
    
     public function paysprintcallback(Request $post){
        \DB::table('microlog')->insert(["product" => "", 'response' => json_encode($post->all())]);
        \DB::table('microlog')->insert(["product" => $post->event, 'response' => json_encode($post->all())]);

        switch ($post->event) {
            case 'MERCHANT_ONBOARDING':
                if(isset($post->param['merchant_id'])){
                    $action = Aepsuser::where('merchantLoginId', $post->param['merchant_id'])->update([
                        'merchantLoginPin' => $post->param['request_id'],
                        'status' => 'pending'
                    ]);
                }
                break;

            case 'MERCHANT_STATUS_ONBOARD':
                if($post->has("data")){
                      $rapi = Api::where('code', 'raeps')->first();
                    $claims = JWT::decode($post->data, $rapi->password, array('HS256'));
                    if(isset($claims->status) && $claims->status == 1){
                        $action = Aepsuser::where('merchantLoginId', $claims->merchantcode)->update([
                            'status' => 'approved'
                        ]);
                        if($action){
                            return response()->json(["status"=> 200,"message"=>"Transaction completed successfully"]);
                        }else{
                            return response()->json(['status' => 400, 'message' => "Something went wrong"]);
                        }
                    }
                }
                break;

            case 'RECHARGE_SUCCESS':
            case 'BILLPAY_SUCCESS' :
                if(isset($post->param['referenceid'])){
                    $action = Report::where('txnid', $post->param['referenceid'])->whereIn('status', ['success', 'pending'])->update([
                        'refno'  => $post->param['operatorid'],
                        'status' => 'success'
                    ]);
                }
                break;

            case 'RECHARGE_FAILURE':
            case 'BILLPAY_FAILURE':
                if(isset($post->param['referenceid'])){
                    $report = Report::where('txnid', $post->param['referenceid'])->whereIn('status', ['success', 'pending'])->first();
                    if($report){
                        $action = Report::where('id', $report->id)->update([
                            'refno'  => $post->param['message'],
                            'status' => 'reversed'
                        ]);

                        if($action){
                            \Myhelper::transactionRefund($report->id);
                        }
                    }
                }
                break;

            case 'DMT':
                if(isset($post->param['referenceid'])){
                    $report = Report::where('txnid', $post->param['referenceid'])->whereIn('status', ['success', 'pending'])->first();
                    if($report){
                        switch ($post->param['txn_status']) {
                            case 'FAILED':
                                $action = Report::where('id', $report->id)->update([
                                    'refno'  => $post->param['message'],
                                    'status' => 'reversed'
                                ]);
                                if($action){
                                    \Myhelper::transactionRefund($report->id);
                                }
                                break;
                            
                            default:
                                $action = Report::where('txnid', $post->param['referenceid'])->whereIn('status', ['success', 'pending'])->update([
                                    'refno'  => $post->param['operatorid'],
                                    'status' => 'success'
                                ]);
                                break;
                        }
                    }
                }
                break;

            case 'PAYOUT_SETTLEMENT':
                if($post->has("param_inc")){
                    $claims = JWT::decode($post->param_inc, $this->api->password, array('HS256'));
                    
                    if(isset($claims->status) && $claims->status == 1){
                        $action = Aepsfundrequest::where('payoutid', $claims->refid)->update([
                            'status'    => 'approved',
                            'payoutref' => $claims->utr
                        ]);
                        
                        if($action){
                            return response()->json(["status"=> 200,"message"=>"Transaction completed successfully"]);
                        }else{
                            return response()->json(['status' => 400, 'message' => "Something went wrong"]);
                        }
                    }
                }
                break;
               case 'UPIQR_SUCCESS'  :
                    if($post->has("param")){
                        
                      $prereport = Report::where('txnid', $post->param['upi_txn_id'])->where('status','success')->first(); 
                      if(!$prereport ){
                              $merchent = \DB::table('upimerchants')->where('merchent',$post->param['refid'])->first();
                              if(!$merchent){
                                    return response()->json(['status' => 400, 'message' => "Invalid Merchent"]);
                              }
                          if($post->param['txnstatus'] == 1){
                              $user = User::where('id',$merchent->user_id)->first();
                              $userwalletincrment = User::where('id',$merchent->user_id)->increment('mainwallet',$post->param['amount']);
                              $provide = Provider::where('recharge1', 'dynamicqr')->first();
                              $insert = 
                              [
                                'number' => $post->param['customer_virtual_address'],
                                'mobile' => $user->mobile,
                                'provider_id' =>$provide->id,
                                'api_id' => $provide->api_id,
                                'amount' => $post->param['amount'],
                                'charge' => '0.00',
                                'profit' => '0.00',
                                'gst' => '0.00',
                                'tds' => '0.00',
                                'apitxnid' => NULL,
                                'txnid' => $post->param['upi_txn_id'],
                                'payid' => $post->param['upi_txn_id'],
                                'refno' => $post->param['upi_txn_id'],
                                'description' => null,
                                'remark' => $post->param['message'],
                                'option1' => $post->param['txn_completion_date'],
                                'option2' =>$post->param['customer_mobile_number'],
                                'option3' =>$post->param['customer_account_name'],
                                'option4' => NULL,
                                'status' => 'success',
                                'user_id' => $user->id,
                                'credit_by' =>1,
                                'rtype' => 'main',
                                 'via' => 'portal',
                                 'adminprofit' => '0.00',
                                 'balance' => $user->mainwallet,
                                 'trans_type' => "credit",
                                 'product' => "fund request"
                              ];       
                              $usercredit = Report::create($insert);    
                          }
                       }    
                    }    
                break ;  
              case 'Pan Transaction':
                    $order  = \DB::table('uti_orders')->where('txnid', $post->param['UTITSLTransID'])->first();
                    
                    if(!$order){
                        return response()->json(['status' => 400, 'message' => "Transaction failed"]);
                    }
                    
                    $user = \App\User::find($order->user_id);
                    if($user->mainwallet < $post->param['transAmt']){
                        return response()->json(['status' => 400, 'message' => "Transaction failed"]);
                    }
                   if($post->param['amount'] > 0 && $post->param['transAmt'] <= 100000){
                       $providers = Provider::where('recharge1', 'cms1')->first();
                       $post['provider_id']=$providers->id;
                   } 
                    else{
                    $providers = Provider::where('recharge1', 'utipancard')->first();
                    $post['provider_id']=$providers->id;
                  } 
                    $post['profit'] = \Myhelper::getCommission($post->param['cms1'], $user->scheme_id, $provider->type, $post->provider_id);
                   
                    $post['debitAmount'] = $post->param['transAmt'] - ($post->profit );
                    $insert = [
                        'api_id'      => $provider->api->id,
                        'provider_id' => $post->provider_id,
                        'mobile'  => $user->mobile,
                        'number'  => $post->param['applicationNo'],
                        'txnid'   => $post->param['UTITSLTransID'],
                        'payid'   => $post->param['UTITSLTransID'],
                        'amount'  => $post->param['transAmt'],
                        'option1' => $post->param['PANCardType'],
                        'profit'  => $post->profit,
                        'status'  => 'pending',
                        'user_id' => $user->id,
                        'credit_by'   => $user->id,
                        'product'     => 'utipancard',
                        'balance'     => $user->mainwallet,
                        'via'         => 'api',
                        'trans_type'  => 'debit'
                    ];
    
                    $transaction = User::where('id', $user->id)->decrement('mainwallet', $post->debitAmount);
    
                    if($transaction){
                        $report = Report::create($insert);
                        return response()->json(["status"=> 200,"message"=>"Transaction Successfull"]);
                    }else{
                        return response()->json(['status' => 400, 'message' => "Transaction failed"]);
                    }
                    break;  
                    
                case 'Pan Transaction Reversal':
                    if(isset($post->param['referenceid'])){
                      $action = Report::where('txnid',$post->param['referenceid'])->whereIn('status', ['pending'])->update([
                            'payid'   => $post->param['TransactionID'],  
                            'refno'   => $post->param['ackno'],
                            'option1' => $post->param['network'],
                            'option2' => $post->param['uniqueid'],
                            'status'  => 'success'
                        ]);
                        if($action)
                        {
                             $report = Report::where('txnid',$post->param['referenceid'])->first();    
                            \Myhelper::commission($report);    
                           return response()->json(["status"=> 200,"message"=>"Transaction Successfull"]);      
                        }
                         return response()->json(['status' => 400, 'message' => "Transaction failed"]);
                    }
                    break;
                  case 'Pan Transaction Refund':
                    if(isset($post->param['refund_reference'])){
                        $report = Report::where('txnid',$post->param['refund_reference'])->whereIn('status', ['pending'])->first();
                        if($report){
                            $action = Report::where('id', $report->id)->update([
                                'refno'  => $post->param['refund_reason'],
                                'payid'  => $post->param['utiitsl_id'],
                                'status' => 'reversed'
                            ]);
    
                            if($action){
                                \Myhelper::transactionRefund($report->id);
                            }
                            return response()->json(["status"=> 200,"message"=>"Transaction Successfull"]);
                        }
                    }   
                  break;    
            
            default:
                break;
        }
        return response()->json(["status"=> 200,"message"=>"Transaction completed successfully"]);
    }
    
    public function cmsCallback(Request $post){
         \DB::table('paytmlogs')->insert(['txnid' => "razor", "response" => 'CMSCALLBACK'.json_encode($post->all())]);
    }
    public function debit(Request $post){
         \DB::table('paytmlogs')->insert(['txnid' => "razor", "response" => 'CMSCALLBACK'.json_encode($post->all())]);
    }
    public function walletcheck(Request $post){
         \DB::table('paytmlogs')->insert(['txnid' => "razor", "response" => 'CMSCALLBACK'.json_encode($post->all())]);
    }


    public function runpaisaPg(Request $post){ 
	    
        \DB::table('microlog')->insert(['product'=>'mainRunpaisawebhook','response'=>json_encode($post->all())]); 
       if (empty($post->STATUS)) {
              return json_encode(['status' => false, 'message' => 'no data found']);
       }
      
     //    return json_encode(['status' => true, 'message' => 'record added']);
        $jsonstring=json_encode($post->all());
             $data=json_decode($jsonstring);
           $fstatus='rejected'; 
            if($data->STATUS == "SUCCESS"){
                $status='success';
                $fstatus='approved';
            }
            elseif($data->STATUS == "FAILED"){
                $status='failed'; 
                $fstatus='rejected'; 
            }
            
            $alreadydata= Report::where('txnid',$data->ORDER_ID)->first();
            $string = $data->ORDER_ID;
            $userid= strtok($string, "_");
           
          $provider = Provider::where('recharge1', 'rnupicharge')->first();
           $user = User::where('id',$userid)->first();
           $paymode = strtolower($data->TXN_MODE);
            if($paymode == "credit_card"){
                  $provider = Provider::where('recharge1', 'rncreditcard')->first();
                  $method= $data->CARD_TYPE;
                  $number= $data->CARD_NUMBER;
                  $bank= $data->BANK_CODE;
                 
              }
           if($paymode  == "debit_card"){
                  $provider = Provider::where('recharge1', 'rndebitcard')->first();
                  $method= $data->CARD_TYPE;
                  $number= $data->CARD_NUMBER;
                  $bank= $data->BANK_CODE;
                 
              }
              
           if($paymode == "wallet"){
                  $provider = Provider::where('recharge1', 'rnwallet')->first();
                  $method=$data->CARD_TYPE;
                  $number=  $data->CUSTOMER_PHONE;
                  $bank= "";
                 
              }   

          if($paymode == "upi"){
              
                  $provider = Provider::where('recharge1', 'rnupicharge')->first();
                  $method= @$data->PG_PARTNER;
                  $number=  $data->CUSTOMER_PHONE;
                  $bank= @$data->BANK_TXNID;
                 
              }
           if($paymode == "net_banking"){
              
               $provider = Provider::where('recharge1', 'rnnetbank')->first();
               $method= '' ;
               $number= '';
               $bank=  '' ;
           }
           if($provider){
              $post['provider_id'] = $provider->id;
           }else{
               $post['provider_id']=0; 
           }
              
              $usercommission = \Myhelper::getCommission($data->TXN_AMOUNT, $user->scheme_id, $post->provider_id, $user->role->slug);
              $post['gst'] = 0;
              if(!$alreadydata){
              
                 $insert = [
                           'type' => 'request',
                           'paymode' =>  $paymode,
                           'amount'  =>  $data->TXN_AMOUNT,
                           'ref_no'  =>  @$data->BANK_TXNID,
                           'paydate' =>  $data->TXN_DATE,
                           'status'  =>$fstatus,
                           'user_id' =>  $user->id,
                           'credited_by' => $user->id,
                           'remark'    =>"PG Fund Recieved via Paymentgateway Runpaisa",
                           'fundbank_id' => $provider->api->id ?? 0,
                           ];
                       
                   $report = Fundreport::create($insert);
                   
                       $insertpg = [
                           'number' =>  $data->CUSTOMER_PHONE,
                           'mobile' => $data->CUSTOMER_PHONE,
                           'provider_id' => $post->provider_id,
                           'api_id' =>  "23",
                           'amount' => $data->TXN_AMOUNT,
                           'charge' => $usercommission,
                           'profit' => '0.00',
                           'gst' =>  $post->gst,
                           'tds' => '0.00',
                           'txnid' => $data->ORDER_ID,
                           'payid' => $data->BANK_TXNID ?? '',
                           'refno' => $data->BANK_TXNID ?? ' ',
                           'description' =>  "PG Fund Recieved via Runpaisa",
                           'remark' =>  "",
                           'option1' => $user->id,
                           'option2' =>  $method,
                           "option3"     => $bank,
                           'status' => $status,
                           'user_id' => $user->id,
                           'credit_by' => $user->id,
                           'rtype' => 'main',
                           'via' => 'portal',
                           'balance' => $user->mainwallet,
                           'trans_type' => 'credit',
                           'product' => "fund loadwallet"
                       ];
                       Report::create($insertpg);
                   if($data->STATUS == "SUCCESS"){
                       User::where('id', $user->id)->increment('mainwallet',  $data->TXN_AMOUNT - $usercommission);
                      return json_encode(['status' => true, 'message' => 'record added']);
                   }
              }
              
              return json_encode(['status' => false, 'message' => 'some error']);
   }

   public function ambikarechargeupdate(Request $doc)
   {

       \DB::table('microlog')->insert(['product' => 'ambika-recharge', 'response' => json_encode($doc->all())]);


       if (isset($doc->AGENTID)) {
           $report = Report::where(['txnid' => $doc->AGENTID, 'product' => 'recharge'])->first();
        //   dd($report,$doc);
           if ($report->status == 'pending') {
               if (isset($doc->STATUS)) {
               
                   if($doc->STATUS == "2"){
                    $update['status'] = "success";
                    $update['payid'] = (isset($doc->RPID))?$doc->RPID:$doc->TRANID;
                    $update['refno'] = (isset($doc->OPID))?$doc->OPID:$doc->AGENTID;
                    $update['description'] = "Recharge Accepted";
                }elseif($doc->STATUS == "3"){
                    \Myhelper::transactionRefund($report->id);
                    $update['status'] = "reversed";
                    $update['payid'] = (isset($doc->RPID))?$doc->RPID:$doc->TRANID;
                    $update['refno'] = (isset($doc->OPID))?$doc->OPID:$doc->AGENTID;
                    $update['description'] = (isset($doc->MSG)) ? $doc->MSG : "Failed";
                }elseif($doc->STATUS == "1"){
                    $update['status'] = "pending";
                    $update['payid'] = (isset($doc->RPID))?$doc->RPID:$doc->TRANID;
                    $update['refno'] = (isset($doc->OPID))?$doc->OPID:$doc->AGENTID;
                    $update['description'] = (isset($doc->MSG)) ? $doc->MSG : "Pending";
                }

                   $update = Report::where(['id' => $report->id])->update($update);
                   return response()->json(['statuscode' => 'TXN', 'message' => 'success']);
               }
           }
       } else{
        return response()->json(['statuscode' => 'TXN', 'message' => 'success']);
       }
   }

   
    
}