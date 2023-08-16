<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Fundreport;
use App\Models\Aepsfundrequest;
use App\Models\Aepsreport;
use App\Models\Microatmfundrequest;
use App\Models\Microatmreport;
use App\Models\Report;
use App\Models\Fundbank;
use App\Models\Paymode;
use App\Models\Api;
use App\Models\Provider;
use App\Models\Aepsuser;
use App\Models\PortalSetting;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class FundController extends Controller
{
    public $fundapi, $admin;

    public function __construct()
    {
        $this->fundapi = Api::where('code', 'fund')->first();
        $this->sprintapi = Api::where('code', 'sprintpayout')->first(); 
        $this->admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first();

    }

    public function index($type, $action="none")
    {
        $data = [];
        switch ($type) {
            case 'tr':
                $permission = ['fund_transfer', 'fund_return'];
                break;
             case 'upi':
            case 'request':
               $data['merchent'] = \DB::table('upimerchants')->where('user_id',\Auth::user()->id);
                $permission = 'fund_request';
                break;
            case 'runpaisapg':   
                    $permission = 'runpaisa_service';
                    break;
            case 'requestview':
                $permission = 'setup_bank';
                break;
                
                
              case 'addaccount':
                $permission = 'aeps_fund_request';
                $data['payoutuser']= \DB::table('sprintpayoutusers')->where('user_id', \Auth::id())->first();
                $data['payoutusercount']= \DB::table('sprintpayoutusers')->where('user_id', \Auth::id())->count();
                $data['bankName'] = \DB::table('dmtbanklist')->get();
                break;     
            
            case 'statement':
            case 'requestviewall':
                $permission = 'fund_report';
                break;

            case 'aeps':
                $permission = 'aeps_fund_request';
                $data['payoutusers']= \DB::table('sprintpayoutusers')->where('user_id', \Auth::id())->get();
                $data['userbanks'][0]= ['account' => Auth::user()->account, 'ifsc' => Auth::user()->ifsc, 'bank' => Auth::user()->bank];
                $data['userbanks'][1]=  ['account' => Auth::user()->account2, 'ifsc' => Auth::user()->ifsc2, 'bank' => Auth::user()->bank2];
                $data['userbanks'][2]=  ['account' => Auth::user()->account3, 'ifsc' => Auth::user()->ifsc3, 'bank' => Auth::user()->bank3];
            

                $data['aepstiming']=\DB::table('portal_settings')->where('code','aepsslabtime')->first();
                $data['settlementcharge']=\DB::table('portal_settings')->where('code','settlementcharge')->first();
                $data['impschargeupto25']=\DB::table('portal_settings')->where('code','impschargeupto25')->first();
                $data['impschargeabove25']=\DB::table('portal_settings')->where('code','impschargeabove25')->first();
                break;
            
            case 'aepsrequest':
            case 'payoutrequest':
                $permission = 'aeps_fund_view';
                break;

            case 'aepsfund':
            case 'aepsrequestall':
                $permission = 'aeps_fund_report';
                break;

            case 'microatm':
                $permission = 'microatm_fund_request';
                break;
            
            case 'microatmrequest':
                $permission = 'microatm_fund_view';
                break;

            case 'microatmfund':
            case 'microatmrequestall':
                $permission = 'microatm_fund_report';
                break;
           
                
            default:
                abort(404);
                break;
        }

        if (!\Myhelper::can($permission)) {
            abort(403);
        }

        if ($this->fundapi->status == "0") {
            abort(503);
        }

        switch ($type) {
            case 'request':
                $data['banks'] = Fundbank::where('user_id', \Auth::user()->parent_id)->where('status', '1')->get();
                $data['payoutusers']= \DB::table('sprintpayoutusers')->where('user_id', \Auth::id())->get(); 
                if(!\Myhelper::can('setup_bank', \Auth::user()->parent_id)){
                    $admin = User::whereHas('role', function ($q){
                        $q->where('slug', 'whitelable');
                    })->where('company_id', \Auth::user()->company_id)->first(['id']);

                    if($admin && \Myhelper::can('setup_bank', $admin->id)){
                        $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();
                    }else{
                        $admin = User::whereHas('role', function ($q){
                            $q->where('slug', 'admin');
                        })->first(['id']);
                        $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();
                    }
                }
                $data['paymodes'] = Paymode::where('status', '1')->get();
                break;
        }
//dd($data);
        return view('fund.'.$type)->with($data);
    }

    public function transaction(Request $post)
    {
        if ($this->fundapi->status == "0") {
            return response()->json(['status' => "This function is down."],400);
        }
        $provide = Provider::where('recharge1', 'fund')->first();
        $post['provider_id'] = $provide->id;

        switch ($post->type) {
            case 'transfer':
            case 'return':
                if($post->type == "transfer" && !\Myhelper::can('fund_transfer')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                if($post->type == "return" && !\Myhelper::can('fund_return')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $rules = array(
                    'amount'    => 'required|numeric|min:1',
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                if($post->type == "transfer"){
                    if(\Auth::user()->mainwallet < $post->amount){
                        return response()->json(['status' => "Insufficient wallet balance."],400);
                    }
                }else{
                    $user = User::where('id', $post->user_id)->first();
                    if($user->mainwallet < $post->amount){
                        return response()->json(['status' => "Insufficient balance in user wallet."],400);
                    }
                }
                $post['txnid'] = 0;
                $post['option1'] = 0;
                $post['option2'] = 0;
                $post['option3'] = 0;
                $post['refno'] = date('ymdhis');
                return $this->paymentAction($post);

                break;
                
              
            case 'addaccount' :
                $token = $this->getpayoutToken(\Auth::id().Carbon::now()->timestamp);
                $agent = Aepsuser::where('user_id', \Auth::id())->first();
                if(!$agent){
                  return response()->json(['statuscode'=>'TXF','status'=>'failed','message'=>'Merchant is not onboarded']);    
                }
                $user = User::where('id',\Auth::id())->first();
                if($user->bank){
                 // return response()->json(['statuscode'=>'ERR','status'=>'failed','message'=>'Bank Already Added For This User']);  
                }
                if($agent->status=="pending"){
                  return response()->json(['statuscode'=>'TXF','status'=>'failed','message'=>'Merchant is not active']);    
                }
                $header =  array(
                'Token: '.$token['token'],
                'Authorisedkey:'.$this->sprintapi->optional3,
                'Content-Type: application/json',
                
                );
                $parameter = [
                           "bankid"        => $post->bankid,
                           "merchant_code" => $agent->merchantLoginId,
                           "account"       => $post->account,
                           "ifsc"          => $post->ifsc,
                           "name"          => $post->name,
                           "account_type"  =>$post->acctype
                           ];
                $request = json_encode($parameter);           
                $url=$this->sprintapi->url."payout/add";
                $bankname = \DB::table('dmtbanklist')->where('bankid',$post->bankid)->first();   
                $result = \Myhelper::curl($url, "POST",$request, $header, "yes");
                 \DB::table('rp_log')->insert([
                        'ServiceName' => "Add Account",
                        'header' => json_encode($header),
                        'body' => json_encode($request),
                        'response' => $result['response'],
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                //dd($result,$url,$request,$header);
                    
                if(isset($result['response']) && $result['response'] != ''){
                    $response = json_decode($result['response']);
                    
                    if(isset( $response->response_code) && $response->status == true && $response->response_code == 2){
                       
                     $action = \DB::table('sprintpayoutusers')->insert([
                            'user_id'=>\Auth::id(),
                            'account'=>$post->account,
                            'ifsc'=>$post->ifsc,
                            'name'=>$post->name,
                            'bene_id'=>$response->bene_id,
                            'remark'=>$response->message,
                            'doc_upload'=>'pending',
                            'status'=>'success',
                             'bankname'  => $bankname->bankname ??  '' ,
                            'bankid'    => $post->bankid ?? 0,
                            ]);
                     
                    User::where('id',\Auth::user()->id)->update(['account' => $post->account, 'bank' => $bankname->bankname, 'ifsc'=>$post->ifsc,'bene_id1'=>$response->bene_id]);
                    return response()->json(['statuscode'=>'TXN','status'=>'success','message'=>$response->message]);        
                    }
                    elseif(isset($response->response_code) && $response->status == TRUE && $response->response_code == 1){
                        
                      $action = \DB::table('sprintpayoutusers')->insert([
                            'user_id'=>\Auth::id(),
                            'account'=>$post->account,
                            'ifsc'=>$post->ifsc,
                            'name'=>$post->name,
                            'bene_id'=>$response->bene_id,
                            'remark'=>$response->message,
                            'doc_upload'=>'success',
                            'status'=>'pending',
                             'bankname'  => $bankname->bankname ?? " " ,
                             'bankid'    => $post->bankid ?? 0, 
                            ]);
                    User::where('id',\Auth::user()->id)->update(['account' => $post->account, 'bank' => $bankname->bankname, 'ifsc'=>$post->ifsc,'bene_id1'=>$response->bene_id]);        
                      
                    return response()->json(['statuscode'=>'TXN','status'=>'success','message'=>$response->message]);   
                    }
                    elseif(isset($response->response_code) && $response->status == false && $response->response_code == 4){
                         
                    return response()->json(['statuscode'=>'TXF','status'=>'failed','message'=>$response->message]);      
                    }
                    else{
                     
                    return response()->json(['statuscode'=>'TXF','status'=>'failed','message'=>$response->message]);  
                    }
                    
                }    
             break;   
            
            case 'docupload' :
               
                  //'upload' => 'required|file|max:8192',
                   $agent = Aepsuser::where('user_id', \Auth::id())->first();
                    if(!$agent){
                      return response()->json(['statuscode'=>'TXF','status'=>'failed','message'=>'Merchant is not onboarded']);    
                    }
                    if($post->hasFile('panimage')){
                        $pancardpics ='panimage'.\Auth::id().date('ymdhis').".".$post->file('panimage')->guessExtension();
                        $post->file('panimage')->move(public_path('kyc/'), $pancardpics);
                        
                        $panpicpath = public_path('kyc/').$pancardpics;
                
                    }
                    if($post->hasFile('passbook')){
                        $passbookpics ='passbook'.\Auth::id().date('ymdhis').".".$post->file('passbook')->guessExtension();
                        $post->file('passbook')->move(public_path('kyc/'), $passbookpics);
                        
                        $passbookpicpath = public_path('kyc/').$passbookpics;
                        
                    }
                    
                     if($post->hasFile('front_image')){
                        $adharcardfrontpics ='aadharfront'.$post->user_id.date('ymdhis').".".$post->file('front_image')->guessExtension();
                        $post->file('front_image')->move(public_path('kyc/'), $adharcardfrontpics);
                        
                        $adharfrontpicpath = public_path('kyc/').$adharcardfrontpics;
                
                    }
                    if($post->hasFile('back_image')){
                        $adharbacardpics ='aadckharback'.$post->user_id.date('ymdhis').".".$post->file('back_image')->guessExtension();
                        $post->file('back_image')->move(public_path('kyc/'), $adharbacardpics);
                        
                        $adharbackpicpath = public_path('kyc/').$adharbacardpics; 
                
                    }
                   if (function_exists('curl_file_create')) { 
                     if($passbookpicpath){
                      $passbook = curl_file_create($passbookpicpath);
                     }
                    if($post->doctype == "PAN"){
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
                        $user = User::where('id',\Auth::user()->id)->first();
                       /* $agent =\DB::table('sprintpayoutusers')->where('bene_id',$post->bene_id)->update([
                            'remark'=>$response->message,
                            'doc_upload'=>'uploaded',
                            'status'=>'pending',
                            ]);*/
                       //  $url = 'https://paysprint.in/service-api/api/v1/service/payout/payout/uploaddocument';
                         $url = 'https://api.paysprint.in/api/v1/service/payout/payout/uploaddocument';
                        $payload['merchant_code'] =  $agent->merchantLoginId;
                        $payload['doctype'] = $post->doctype;
                        $payload['passbook'] = $passbook;
                     if($post->doctype == "PAN"){
                        $payload['panimage'] = $panimage;
                     }else{
                          $payload['front_image'] = $adharfrontimage; 
                           $payload['back_image'] = $adharbackimage; 
                      }    
                        $payload['bene_id'] = $post->bene_id;
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
                       
                        $action = \DB::table('sprintpayoutusers')->where('bene_id',$post->bene_id)->update([
                                'remark'=>$response->message,
                                'doc_upload'=>'uploaded',
                                'status'=>'pending',
                                ]);
                        return response()->json(['statuscode'=>'TXN','status'=>'success','message'=>$response->message]);        
                    }
                    
                    elseif($response->status == false && $response->response_code == 1){
                        
                        $action = \DB::table('sprintpayoutusers')->where('bene_id',$post->bene_id)->update([
                            'remark'=>$response->message,
                            'doc_upload'=>'uploaded',
                            'status'=>'pending',
                            ]);
                        
                        return response()->json(['statuscode'=>'TXN','status'=>'success','message'=>"Successfully uploaded"]);   
                    }
                       
                    else{
                     
                        $action = \DB::table('sprintpayoutusers')->where('bene_id',$post->bene_id)->update([
                            'remark'=>$response->message,
                            'doc_upload'=>'pending',
                            'status'=>'pending',
                            ]);
                      
                        return response()->json(['statuscode'=>'TXF','status'=>'failed','message'=>$response->message]);  
                    }
                
                   return response()->json(['statuscode'=>'TXN','status'=>'success','message'=>"Successfully uploaded"]);   
             break;  
             
            case 'requestview':
                if(!\Myhelper::can('setup_bank')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $fundreport = Fundreport::where('id', $post->id)->first();
                
                if($fundreport->status != "pending"){
                    return response()->json(['status' => "Request already approved"],400);
                }
                $post['charge'] = 0 ;
                $post['amount'] = $fundreport->amount;
                $post['type'] = "request";
                $post['user_id'] = $fundreport->user_id;
                if($fundreport->mode == "CASH"){
                   if($fundreport->amount >= 100000){    
                      $lakh =  round($fundreport->amount / 100000);
                   }else{
                       $lakh = 1 ;
                   }
                    $fundbank  = Fundbank::where('id', $fundreport->fundbank_id)->first();
                  if(($fundbank) && $fundbank->charge > 0){
                      $post['charge'] = $fundbank->charge * $lakh ;
                  }
                }
                if ($post->status == "approved") {
                    if(\Auth::user()->mainwallet < $post->amount){
                        return response()->json(['status' => "Insufficient wallet balance."],200);
                    }
                    $action = Fundreport::updateOrCreate(['id'=> $post->id], [
                        "status" => $post->status,
                        "remark" => $post->remark
                    ]);
                   
                    $post['txnid'] = $fundreport->id;
                    $post['option1'] = $fundreport->fundbank_id;
                    $post['option2'] = $fundreport->paymode;
                    $post['option3'] = $fundreport->paydate;
                    $post['refno'] = $fundreport->ref_no;
                    return $this->paymentAction($post);
                }else{
                    $action = Fundreport::updateOrCreate(['id'=> $post->id], [
                        "status" => $post->status,
                        "status" => $post->status,
                        "remark" => $post->remark
                    ]);

                    if($action){
                        return response()->json(['status' => "success"],200);
                    }else{
                        return response()->json(['status' => "Something went wrong, please try again."],200);
                    }
                }
                
                return $this->paymentAction($post);
                break;

            case 'request':
                if(!\Myhelper::can('fund_request')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $rules = array(
                    'fundbank_id'    => 'required|numeric',
                    'paymode'    => 'required',
                    'amount'    => 'required|numeric|min:100',
                    'ref_no'    => 'required|unique:fundreports,ref_no',
                    'paydate'    => 'required'
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $post['user_id'] = \Auth::id();
                $post['credited_by'] = \Auth::user()->parent_id;
                if(!\Myhelper::can('setup_bank', \Auth::user()->parent_id)){
                    $admin = User::whereHas('role', function ($q){
                        $q->where('slug', 'whitelable');
                    })->where('company_id', \Auth::user()->company_id)->first(['id']);

                    if($admin && \Myhelper::can('setup_bank', $admin->id)){
                        $post['credited_by'] = $admin->id;
                    }else{
                        $admin = User::whereHas('role', function ($q){
                            $q->where('slug', 'admin');
                        })->first(['id']);
                        $post['credited_by'] = $admin->id;
                    }
                }
                
                $post['status'] = "pending";
                if($post->hasFile('payslips')){
                    $filename ='payslip'.\Auth::id().date('ymdhis').".".$post->file('payslips')->guessExtension();
                    $post->file('payslips')->move(public_path('deposit_slip/'), $filename);
                    $post['payslip'] = $filename;
                }
                $action = Fundreport::create($post->all());
                if($action){
                    return response()->json(['status' => "success"],200);
                }else{
                    return response()->json(['status' => "Something went wrong, please try again."],200);
                }
                break;

            case 'bank':
                if ($this->pinCheck($post) == "fail") {
                    return response()->json(['status' => "Transaction Pin is incorrect"]);
                }
                $banksettlementtype = $this->banksettlementtype();
                $impschargeupto25 = $this->impschargeupto25();
                $impschargeabove25 = $this->impschargeabove25();
                $neftcharge = $this->neftcharge(); 

                if($banksettlementtype == "down"){
                    return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();

                $post['user_id'] = \Auth::id();
                 $rules = array(
                        'amount'    => 'required|numeric|min:10',
                        'account'   => 'sometimes|required',
                        'bank'   => 'sometimes|required',
                        'ifsc'   => 'sometimes|required',
                        'beniid'   => 'sometimes|required'
                    );
                    
                // if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                   
                // }else{
                //     $rules = array(
                //         'amount'    => 'required|numeric|min:10'
                //     );

                //     $post['account'] = $user->account;
                //     $post['bank']    = $user->bank;
                //     $post['ifsc']    = $user->ifsc;
                // }

                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                 $checkaccount = \DB::table('sprintpayoutusers')->where('bene_id',$post->beniid)->first();              
                 if(!$checkaccount){
                    return response()->json(['status' => "Beneficiary Not Found"],400);
                }
                     $post['account'] = $checkaccount->account;
                     $post['bank']    = $checkaccount->bankname; 
                     $post['ifsc']    = $checkaccount->ifsc; 
                
                // if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                //     User::updateOrCreate(['id' => \Auth::user()->id], ['account' => $post->account, 'bank' => $post->bank, 'ifsc'=>$post->ifsc]);
                // }
                 
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
                    
                    $api = Api::where('code', 'sprintpayout')->first();
                    //dd($api);
                    do {
                        $post['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                    } while (Aepsfundrequest::where("payoutid", "=", $post->payoutid)->first() instanceof Aepsfundrequest);

                    $post['status']   = "pending";
                    $post['pay_type'] = "payout";
                    $post['payoutid'] = $post->payoutid;
                    $post['payoutref']= $post->payoutid;
                    $post['create_time']= Carbon::now()->toDateTimeString();
                    try {
                        $aepsrequest = Aepsfundrequest::create($post->all());
                    } catch (\Exception $e) {
                        return response()->json(['status'=> "Duplicate Transaction Not Allowed, Please Check Transaction History"]);
                    }

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
                    $aepsreports['credited_by'] = $this->admin->id;
                    $aepsreports['balance']     = $user->aepsbalance;
                    $aepsreports['type']        = "debit";
                    $aepsreports['transtype']   = 'fund';
                    $aepsreports['status'] = 'success';
                    $aepsreports['remark'] = "Bank Settlement";

                    User::where('id', $aepsreports['user_id'])->decrement('aepsbalance',$aepsreports['amount']+$aepsreports['charge']);
                    $myaepsreport = Aepsreport::create($aepsreports);

                    
                    $token = $this->getpayoutToken(\Auth::id().Carbon::now()->timestamp);
                    $url=$this->sprintapi->url."payout/dotransaction"; //"https://paysprint.in/service-api/api/v1/service/payout/payout/dotransaction";
                    //dd($user);
                    $parameter = [
                            "bene_id" => $post->beniid, //$user->bene_id2,
                            "amount" => $post->amount,
                            "refid" => $post->payoutid, //$post->txnid, 
                            "mode" => "IMPS",//$post->mode
                        ];
                    $request= json_encode($parameter);
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
                    CURLOPT_POSTFIELDS =>$request,
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
                    $this->createFile('sm_bankdotransction_', ['payload' => $request, 'url' => $url, 'response' => $res]);
                    //dd($res);
                     \DB::table('rp_log')->insert([
                        'ServiceName' => "Payout ",
                        'header' => json_encode($header),
                        'body' => json_encode($request),
                        'response' => json_encode($result),
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $response = $res;
                   // dd($response);
                    if(isset($response->response_code) && $response->response_code == 1){
                        Aepsfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "approved", "payoutref" => $response->ackno]);
                        return response()->json(['status'=>"success"], 200);
                    }else{

                        User::where('id', $aepsreports['user_id'])->increment('aepsbalance', $aepsreports['amount']+$aepsreports['charge']);
                        Aepsreport::updateOrCreate(['id'=> $myaepsreport->id], ['status' => "failed", "refno" => isset($response->ackno) ? $response->ackno : $response->message]);

                        Aepsfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "rejected"]);
                        return response()->json(['status'=>'ERR', 'message' => $response->message], 400);
                    }
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
                    return response()->json(['status' => "Transaction Pin is incorrect"]);
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

            case 'matmbank':
                $banksettlementtype = $this->banksettlementtype();
                $impschargeupto25 = $this->impschargeupto25();
                $impschargeabove25 = $this->impschargeabove25();
                $neftcharge = $this->neftcharge(); 

                if($banksettlementtype == "down"){
                    return response()->json(['status' => "Aeps Settlement Down For Sometime"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();

                $post['user_id'] = \Auth::id();

                if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                    $rules = array(
                        'amount'    => 'required|numeric|min:10',
                        'account'   => 'sometimes|required',
                        'bank'   => 'sometimes|required',
                        'ifsc'   => 'sometimes|required'
                    );
                }else{
                    $rules = array(
                        'amount'    => 'required|numeric|min:10'
                    );

                    $post['account'] = $user->account;
                    $post['bank']    = $user->bank;
                    $post['ifsc']    = $user->ifsc;
                }

                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                    User::updateOrCreate(['id' => \Auth::user()->id], ['account' => $post->account, 'bank' => $post->bank, 'ifsc'=>$post->ifsc]);
                }

                $settlerequest = Microatmfundrequest::where('user_id', \Auth::user()->id)->where('status', 'pending')->count();
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

                    $previousrecharge = Microatmfundrequest::where('account', $post->account)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subSeconds(30)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(30)->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge){
                        return response()->json(['status'=> "Transaction Allowed After 1 Min."]);
                    } 
                    
                    $api = Api::where('code', 'sprintpayout')->first();

                    do {
                        $post['payoutid'] = $this->transcode().rand(111111111111, 999999999999);
                    } while (Microatmfundrequest::where("payoutid", "=", $post->payoutid)->first() instanceof Microatmfundrequest);

                    $post['status']   = "pending";
                    $post['pay_type'] = "payout";
                    $post['payoutid'] = $post->payoutid;
                    $post['payoutref']= $post->payoutid;
                    $post['create_time']= Carbon::now()->toDateTimeString();
                    try {
                        $aepsrequest = Microatmfundrequest::create($post->all());
                    } catch (\Exception $e) {
                        return response()->json(['status'=> "Duplicate Transaction Not Allowed, Please Check Transaction History"]);
                    }

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
                        "apitxnid" => $post->payoutid,
                        "amount"   => $post->amount, 
                        "account"  => $post->account,
                        "name"     => $user->name,
                        "bank"     => $post->bank,
                        "ifsc"     => $post->ifsc,
                        "token"    => $api->username,
                        'ip'       => $post->ip(),
                        'callback' => url('api/callback/update/payout')
                    ];
                    $header = array("Content-Type: application/json");

                    if(env('APP_ENV') != "local"){
                        $result = \Myhelper::curl($url, 'POST', json_encode($parameter), $header, 'yes',$post->payoutid);
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
                        Microatmfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "approved", "payoutref" => $response->rrn]);
                        return response()->json(['status'=>"success"], 200);
                    }else{
                        User::where('id', $aepsreports['user_id'])->increment('aepsbalance', $aepsreports['amount']+$aepsreports['charge']);
                        Microatmreport::updateOrCreate(['id'=> $myaepsreport->id], ['status' => "failed", "refno" => isset($response->rrn) ? $response->rrn : $response->message]);
                        Microatmfundrequest::updateOrCreate(['id'=> $aepsrequest->id], ['status' => "rejected"]);
                        return response()->json(['status'=>'ERR', 'message' => $response->message], 400);
                    }
                }else{
                    $post['pay_type'] = "manual";
                    $request = Microatmfundrequest::create($post->all());
                }

                if($request){
                    return response()->json(['status'=>"success", 'message' => "Fund request successfully submitted"], 200);
                }else{
                    return response()->json(['status'=>"ERR", 'message' => "Something went wrong."], 400);
                }
                break;

            case 'matmwallet':
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

                $request = Microatmfundrequest::where('user_id', \Auth::user()->id)->where('status', 'pending')->count();
                if($request > 0){
                    return response()->json(['status'=> "One request is already submitted"], 400);
                }

                if(\Auth::user()->aepsbalance < $post->amount){
                    return response()->json(['status'=>  "Low aeps balance to make this request"], 400);
                }

                $post['user_id'] = \Auth::id();

                if($settlementtype == "auto"){
                    $previousrecharge = Microatmfundrequest::where('type', $post->type)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                    if($previousrecharge > 0){
                        return response()->json(['status'=> "Transaction Allowed After 5 Min."]);
                    }

                    $post['status'] = "approved";
                    $load  = Microatmfundrequest::create($post->all());
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

                    Microatmreport::create($inserts);

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
                            'description' =>  "MicroAtm Fund Recieved",
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
                    $load = Microatmfundrequest::create($post->all());
                }

                if($load){
                    return response()->json(['status' => "success"],200);
                }else{
                    return response()->json(['status' => "fail"],200);
                }
                break;
                
            case 'aepstransfer':
                if(\Myhelper::hasNotRole('admin')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();
                if($user->aepsbalance < $post->amount){
                    return response()->json(['status' => "Insufficient Aeps Wallet Balance"],400);
                }

                $request = Aepsfundrequest::find($post->id);
                $action  = Aepsfundrequest::where('id', $post->id)->update(['status'=>$post->status, 'remark'=> $post->remark]);
                $payee   = User::where('id', $request->user_id)->first();

                if($action){
                    if($post->status == "approved" && $request->status == "pending"){
                        User::where('id', $payee->id)->decrement('aepsbalance', $request->amount);

                        $inserts = [
                            "mobile"  => $payee->mobile,
                            "amount"  => $request->amount,
                            "bank"    => $payee->bank,
                            'txnid'   => $request->id,
                            'refno'   => $post->refno,
                            "user_id" => $payee->id,
                            "credited_by" => $user->id,
                            "balance"     => $payee->aepsbalance,
                            'type'        => "debit",
                            'transtype'   => 'fund',
                            'status'      => 'success',
                            'remark'      => "Move To ".ucfirst($request->type)." Request",
                        ];

                        if($request->type == "wallet"){
                            $inserts['payid'] = "Wallet Transfer Request";
                            $inserts["aadhar"]= $payee->aadhar;
                        }else{
                            $inserts['payid'] = $payee->bank." ( ".$payee->ifsc." )";
                            $inserts['aadhar'] = $payee->account;
                        }

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
                                'txnid' => $request->id,
                                'payid' => $request->id,
                                'refno' => $post->refno,
                                'description' =>  "Aeps Fund Recieved",
                                'remark' => $post->remark,
                                'option1' => $payee->name,
                                'status' => 'success',
                                'user_id' => $payee->id,
                                'credit_by' => $user->id,
                                'rtype' => 'main',
                                'via' => 'portal',
                                'balance' => $payee->mainwallet,
                                'trans_type' => 'credit',
                                'product' => "fund request"
                            ];

                            Report::create($insert);
                        }
                    }
                    return response()->json(['status'=> "success"], 200);
                }else{
                    return response()->json(['status'=> "fail"], 400);
                }

                break;

            case 'microatmtransfer':
                if(\Myhelper::hasNotRole('admin')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $user = User::where('id',\Auth::user()->id)->first();
                if($user->aepsbalance < $post->amount){
                    return response()->json(['status' => "Insufficient Aeps Wallet Balance"],400);
                }

                $request = Microatmfundrequest::find($post->id);
                $action  = Microatmfundrequest::where('id', $post->id)->update(['status'=>$post->status, 'remark'=> $post->remark]);
                $payee   = User::where('id', $request->user_id)->first();

                if($action){
                    if($post->status == "approved" && $request->status == "pending"){
                        User::where('id', $payee->id)->decrement('aepsbalance', $request->amount);

                        $inserts = [
                            "mobile"  => $payee->mobile,
                            "amount"  => $request->amount,
                            "bank"    => $payee->bank,
                            'txnid'   => $request->id,
                            'refno'   => $post->refno,
                            "user_id" => $payee->id,
                            "credited_by" => $user->id,
                            "balance"     => $payee->aepsbalance,
                            'type'        => "debit",
                            'transtype'   => 'fund',
                            'status'      => 'success',
                            'remark'      => "Move To ".ucfirst($request->type)." Request",
                        ];

                        if($request->type == "wallet"){
                            $inserts['payid'] = "Wallet Transfer Request";
                            $inserts["aadhar"]= $payee->aadhar;
                        }else{
                            $inserts['payid'] = $payee->bank." ( ".$payee->ifsc." )";
                            $inserts['aadhar'] = $payee->account;
                        }

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
                                'txnid' => $request->id,
                                'payid' => $request->id,
                                'refno' => $post->refno,
                                'description' =>  "MicroAtm Fund Recieved",
                                'remark' => $post->remark,
                                'option1' => $payee->name,
                                'status' => 'success',
                                'user_id' => $payee->id,
                                'credit_by' => $user->id,
                                'rtype' => 'main',
                                'via' => 'portal',
                                'balance' => $payee->mainwallet,
                                'trans_type' => 'credit',
                                'product' => "fund request"
                            ];

                            Report::create($insert);
                        }
                    }
                    return response()->json(['status'=> "success"], 200);
                }else{
                    return response()->json(['status'=> "fail"], 400);
                }

                break;
            
            case 'loadwallet':
                if(\Myhelper::hasNotRole('admin')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }
                $action = User::where('id', \Auth::id())->increment('mainwallet', $post->amount);
                if($action){
                    $insert = [
                        'number' => \Auth::user()->mobile,
                        'mobile' => \Auth::user()->mobile,
                        'provider_id' => $post->provider_id,
                        'api_id' => $this->fundapi->id,
                        'amount' => $post->amount,
                        'charge' => '0.00',
                        'profit' => '0.00',
                        'gst' => '0.00',
                        'tds' => '0.00',
                        'apitxnid' => NULL,
                        'txnid' => date('ymdhis'),
                        'payid' => NULL,
                        'refno' => NULL,
                        'description' => NULL,
                        'remark' => $post->remark,
                        'option1' => NULL,
                        'option2' => NULL,
                        'option3' => NULL,
                        'option4' => NULL,
                        'status' => 'success',
                        'user_id' => \Auth::id(),
                        'credit_by' => \Auth::id(),
                        'rtype' => 'main',
                        'via' => 'portal',
                        'adminprofit' => '0.00',
                        'balance' => \Auth::user()->mainwallet,
                        'trans_type' => 'credit',
                        'product' => "fund ".$post->type
                    ];
                    $action = Report::create($insert);
                    if($action){
                        return response()->json(['status' => "success"], 200);
                    }else{
                        return response()->json(['status' => "Technical error, please contact your service provider before doing transaction."],400);
                    }
                }else{
                    return response()->json(['status' => "Fund transfer failed, please try again."],400);
                }
                break;
              case 'dynamicqr':
                    $rules = array(
                    // 'amount'    => 'required|numeric|min:1',
                   );
                   $validator = \Validator::make($post->all(), $rules);
                   if ($validator->fails()) {
                       return response()->json($validator->errors(), 422);
                    }        
                  $api = Api::where('code', 'dynamicqr')->first();
                  if(!$api || $api->status == 0){
                     return response()->json(['status' => "failed",'message'=>"Service is down"], 200);   
                  }
                  $user=User::where('id', \Auth::user()->id)->first();
                  $merchentid = \DB::table('upimerchants')->orderBy('id', 'DESC')->first();
                  if(!$merchentid){
                       return response()->json(['status' => "failed",'message'=>"Merchant Not Activated"], 200);   
                  }
                  $url = $api->url ;
                  $token = $this->getToken(\Auth::id().Carbon::now()->timestamp);
                  $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".$token['token'],
                    "Authorisedkey: ".$api->optional1
                  );
                 $request['amount'] = $post->amount ?? 100;
                 $request['merchant_code']  = $merchentid->merchent;
                 $result = \Myhelper::curl($url, "POST", json_encode($request), $header, 'no');
                 
                  \DB::table('rp_log')->insert([
                       'ServiceName' => "Create_Qr_dynamic",
                       'header' => json_encode($header),
                       'body' => json_encode([$request]),
                       'response' => $result['response'],
                       'url' => $url,
                       'created_at' => date('Y-m-d H:i:s')
                   ]);
                   
                 $response = json_decode($result['response']);
                 if(isset($response->response_code) && $response->response_code == "1"){
                      return response()->json(['status' => "success",'data'=>$responce->qr_link ??''], 200);
                 }else{
                       return response()->json(['status' => "failed",'message'=>$responce->message ??'Somethis went wrong'], 200);
                 }     
                break;
            default:
                # code...
                break;
        }
    }

    public function paymentAction($post)
    {
        $user = User::where('id', $post->user_id)->first();
        $charge = $post->charge ?? 0 ; 
        if($post->type == "transfer" || $post->type == "request"){
            $action = User::where('id', $post->user_id)->increment('mainwallet', $post->amount - $charge);
        }else{
            $action = User::where('id', $post->user_id)->decrement('mainwallet', $post->amount);
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
                'charge' => $charge,
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
                'credit_by' => \Auth::id(),
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
                return response()->json(['status' => "Technical error, please contact your service provider before doing transaction."],400);
            }
        }else{
            return response()->json(['status' => "Fund transfer failed, please try again."],400);
        }
    }

    public function paymentActionCreditor($post)
    {
        $payee = $post->user_id;
        $user = User::where('id', \Auth::id())->first();
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
                return response()->json(['status' => "success"], 200);
            }else{
                return response()->json(['status' => "Technical error, please contact your service provider before doing transaction."],400);
            }
        }else{
            return response()->json(['status' => "Technical error, please contact your service provider before doing transaction."],400);
        }
    }
    
    
    public function createvpa(Request $post){
      $rules = array(
         'amount'    => 'required|numeric|min:10',
         'account'   => 'sometimes|required',
         'bank'      => 'sometimes|required',
         'ifsc'      => 'sometimes|required',
         'mobile'    => 'required',
         'address'   => 'required',
         'state'     => 'required',
         'city'      => 'required',
         'pincode'   => 'required',
         'pan'       => 'required',
         'vpa'      => 'required',
       );
       $validator = \Validator::make($post->all(), $rules);
       if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
        }        
      $user=User::where('id', \Auth::user()->id)->first();
      $url = 'https://paysprint.in/service-api/api/v1/service/upi/upi/createvpa';
      $token = $this->getToken(\Auth::id().Carbon::now()->timestamp);
      $header = array(
        "Cache-Control: no-cache",
        "Content-Type: application/json",
        "Token: ".$token['token'],
        "Authorisedkey: NjJlNGEwZDBlNzJmOTU1NmVlNWU1NTI0ZmYxYTQ0MzI="
         );
      $request['refid'] = $this->transcode().rand(11, 99).Carbon::now()->timestamp;;
      $request['acc_no'] = $post->account;
      $request['ifsccode'] = $post->ifsc;
      $request['mobile'] =  $post->mobile ;
      $request['address']  = $post->address;
      $request['state'] = $post->state;
      $request['city'] = $post->city;
      $request['pincode'] = $post->pincode;
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
         $api = Api::where('code', 'dynamicqr')->first();
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
    public function bankList(Request $post)
    {
      
            $token = $this->getpayoutToken($post->user_id.Carbon::now()->timestamp);
                //{\"C\":xxxxx,\"ackno\":xxxxx}
                $parameter['refid'] = "RP585495640078";
                $parameter['ackno']  = "546495";
                
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://paysprint.in/service-api/api/v1/service/payout/payout/status',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{"refid":"RP585495640078","ackno":"546495"}',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Token:'.$token['token'],
                'Authorisedkey:OTQ4YzViZDc4Mzc5NTA1ZjYzYjZhNWVlYWJmYTA4ODY=',
                'Content-Tsion=6b06cc8557c3f39b14b77eb940ae475b516513ac'
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            
           // dd($response);
            

            dd([$response,$token,'https://paysprint.in/service-api/api/v1/service/payout/payout/status']);
  
    }

    public function initiateRunPaisaPg(Request $post){
	        
        $rules = array(
                   'amount'    => 'required|numeric|min:10|max:199999',
               ); 
       
               $validator = \Validator::make($post->all(), $rules);
               if ($validator->fails()) {
                   return response()->json(['errors'=>$validator->errors()], 422);
               }  
           
               $api = Api::where('code', 'runpaisa_pg')->first();    
               if(!$api || $api->status == 0){
                    return response()->json(['status'=>'PG Service Currently Down', 'message'=> "PG Service Currently Down"]);
               } 
  
               
       $token = $this->getRunpaisaToken();

       $request = [];
                     
       $header = array(
           'Content-Type:multipart/form-data',
           'client_id: '. $api->optional1,
           'token:'.$token
       );
                       
      $post['user_id'] = \Auth::id();
       $user = User::where('id', $post->user_id)->first();
     do {
       $post['txnid'] = $post->user_id."_order_".rand(1111111111, 9999999999);
       } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport);
   

     $parameter = array (
                 "callbackurl" => url('')."/api/runpaisa/callback/runpaisaPg",
               "order_id" => $post->txnid,  
               "amount" => $post->amount,
               );
                 
            $query = $parameter; 

            $url = $api->optional2."/order";
            $result = \Myhelper::curl($url, 'POST', $query, $header, "yes", 'PG');  
           if($result['response'] != ''){
               $datas = json_decode($result['response']);  
           if(isset($datas->status) && $datas->status == 'SUCCESS'){

                return response()->json(['status'=>'TXN', 'data'=> $datas->paymentLink]);

           }else{
           return response()->json(['status'=>'TXF', 'message'=> "Something went wrong"]);
           }
       }else{
           
           return response()->json(['status'=>'TXF', 'message'=> "Something went wrong"]);
       }
     } 


     
    public function getRunpaisaToken(){
	     
        $api = Api::where('code', 'runpaisa_pg')->first();   
        $request = [];
        $header = array(
            'client_id: ' . $api->optional1,
            'username: ' . $api->username,    
            'password: '.$api->password, 
            'Content-Type: application/json', 
        );
     
        $url = $api->url."/token" ;  
        $result = \Myhelper::curl($url, "POST", json_encode($request), $header, 'yes', 1, 'runpaisa', 'Runpaisa');
        $response['data'] = json_decode($result['response']);
        if (isset($response['data']->status) && $response['data']->status == 'SUCCESS') {
            return $response['data']->data->token;
        }
        return "";
	}
    
}
