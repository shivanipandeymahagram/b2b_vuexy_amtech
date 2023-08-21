<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Fundbank;
use App\Models\Investfundreport;
use App\Models\Investment;
use App\Models\InvestmentTxn;
use App\Models\Paymode;
use App\Models\Report;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvesmentController extends Controller
{
    public function index()
    {
        if (\Myhelper::hasRole('admin') || \Myhelper::can('invesment')) {
            $banner = Banner::where('status', 'active')->get();
            return view('invesment.index')->with('banner', $banner);
        }

        return \Response::json(['statuscode' => 'ERR', 'status' => "Permission not allowed", 'message' => "Permission not allowed"], 400);

        
    }
    
    public function indexShow()
    {
        if (\Myhelper::hasRole('admin') || \Myhelper::can('invesment_show')) {
            $invesment = Investment::leftJoin('investment_txns',
             'investment_txns.investment_id', 'investments.id')
             ->select('investments.*', 'investment_txns.status as txnStatus')
             ->where('investments.status', 'active')->get();

            return view('invesment.invesment')->with('invesment', $invesment);
        }

        return \Response::json(['statuscode' => 'ERR', 'status' => "Permission not allowed", 'message' => "Permission not allowed"], 400);

        
    }

    
    public function fundReq()
    {
        if ( \Myhelper::can('investment_fund_request')) {
            $data['banks'] = Fundbank::where('user_id', \Auth::user()->parent_id)->where('status', '1')->get();
            $data['paymodes'] = Paymode::where('status', '1')->get();
            return view('fund.request_investment')->with($data);
        }

        return \Response::json(['statuscode' => 'ERR', 'status' => "Permission not allowed", 'message' => "Permission not allowed"], 400); 
    }

        
    public function investfundReq()
    {
        if (\Myhelper::hasRole('admin')) {
            $data['paymodes'] = Paymode::where('status', '1')->get();
            return view('fund.investrequestview')->with($data);
        }

        return \Response::json(['statuscode' => 'ERR', 'status' => "Permission not allowed", 'message' => "Permission not allowed"], 400); 
    }

    public function investReport()
    {
        if (\Myhelper::hasRole('admin')) {
            $data['paymodes'] = Paymode::where('status', '1')->get();
            return view('fund.investment_statement')->with($data);
        }

        return \Response::json(['statuscode' => 'ERR', 'status' => "Permission not allowed", 'message' => "Permission not allowed"], 400); 
    }


    public function investmentRequestUpdate(Request $post) {
        $fundreport = Investfundreport::where('id', $post->id)->first();
                
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
            $action = Investfundreport::updateOrCreate(['id'=> $post->id], [
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
            $action = Investfundreport::updateOrCreate(['id'=> $post->id], [
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
    }


    public function investFundStore(Request $post)
    {
        if(!\Myhelper::can('fund_request')){
            return response()->json(['status' => "Permission not allowed"],400);
        }

        $rules = array(
            'fundbank_id'    => 'required|numeric',
            'paymode'    => 'required',
            'amount'    => 'required|numeric|min:100',
            'ref_no'    => 'required|unique:investfundreports,ref_no',
            'paydate'    => 'required'
        );

        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $action = Investfundreport::where(['status' => 'pending', 'user_id' => Auth::user()->id])->count();
        if($action > 0){
            return response()->json(['status' => "Already reqeust is pending."], 200);
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
        $action = Investfundreport::create($post->all());
        if($action){
            return response()->json(['status' => "success"],200);
        }else{
            return response()->json(['status' => "Something went wrong, please try again."],200);
        }
    }

    public function store(Request $post)
    {
        
        $rules = array(
            'banner_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'mature_amount' => 'required|digits_between:1,999999',
            'maturity_at' => 'required',
            'amount' => 'required|digits_between:1,999999'
        );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }
        $insert = $post->all();
        $insert['user_id'] = Auth::user()->id;

        $action = Investment::updateOrCreate(['id'=> $post->id], $insert);
        if ($action) {
            return response()->json(['status' => "success"], 200);
        }else{
            return response()->json(['status' => "Task Failed, please try again"], 200);
        }
    }

    public function investNow(Request $post)
    {
        
        $rules = array(
            'investment_id' => 'required'
        );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 200);
        }


        if(InvestmentTxn::where(['user_id' => Auth::user()->id, 'investment_id' => $post->investment_id])->count()) {
            return response()->json(['errors' => [0 => 'Already purchased']], 200);
        }

        $inv = Investment::where('id', $post->investment_id)->first();
        $user = User::where('id', Auth::user()->id)->first();

        if ($user->investment_wallet < $inv->amount) {
            return response()->json(['errors' => [0 => 'insufficient wallet balance']], 200);
        }
 
        $user->investment_wallet = $user->investment_wallet - $inv->amount;
        $user->save();

        $insert = $post->all();
        $insert['user_id'] = Auth::user()->id;
        $insert['amount'] = $inv->amount;
        $insert['status'] = 'approved';

        $action = InvestmentTxn::updateOrCreate(['id'=> $post->id], $insert);
        if ($action) {
            return response()->json(['status' => "success"], 200);
        }else{
            return response()->json(['status' => "Task Failed, please try again"], 200);
        }
    }
    
    
    public function paymentAction($post)
    {
        $user = User::where('id', $post->user_id)->first();
        $charge = $post->charge ?? 0 ; 
        if($post->type == "transfer" || $post->type == "request"){
            $action = User::where('id', $post->user_id)->increment('investment_wallet', $post->amount - $charge);
        }else{
            $action = User::where('id', $post->user_id)->decrement('investment_wallet', $post->amount);
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
                'api_id' => 0,
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
                'product' => "investment"
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
                'api_id' => 0,
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
                'product' => "investment"
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
    
}
