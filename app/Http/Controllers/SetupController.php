<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fundbank;
use App\Models\Api;
use App\Models\Provider;
use App\Models\PortalSetting;
use App\Models\Complaintsubject;
use App\Models\Link;
use App\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SetupController extends Controller
{
    public function index($type)
    { 
        switch ($type) {
            case 'userupload' :
            case 'api':
                $permission = "setup_api";
                break;

            case 'bank':
                $permission = "setup_bank";
                break;

            case 'operator':
                $permission = "setup_operator";
                $data['apis'] = Api::whereIn('type', ['recharge', 'bill', 'pancard','cms','matm', 'money','fund'])->where('status', '1')->get(['id', 'product']);
                break;
            
            case 'complaintsub':
                $permission = "complaint_subject";
                break;

            case 'portalsetting':
                $data['settlementtype'] = PortalSetting::where('code', 'settlementtype')->first();
                $data['banksettlementtype'] = PortalSetting::where('code', 'banksettlementtype')->first();
                $data['otplogin'] = PortalSetting::where('code', 'otplogin')->first();
                $data['otpsendmailid']   = PortalSetting::where('code', 'otpsendmailid')->first();
                $data['otpsendmailname'] = PortalSetting::where('code', 'otpsendmailname')->first();
                $data['bcid']   = \App\Models\PortalSetting::where('code', 'bcid')->first();
                $data['cpid']   = \App\Models\PortalSetting::where('code', 'cpid')->first();
                $data['transactioncode']   = \App\Models\PortalSetting::where('code', 'transactioncode')->first();
                $data['mainlockedamount']   = \App\Models\PortalSetting::where('code', 'mainlockedamount')->first();
                $data['aepslockedamount']   = \App\Models\PortalSetting::where('code', 'aepslockedamount')->first();
                $data['settlementcharge']   = \App\Models\PortalSetting::where('code', 'settlementcharge')->first();
                $data['impschargeupto25']   = \App\Models\PortalSetting::where('code', 'impschargeupto25')->first();
                $data['impschargeabove25']   = \App\Models\PortalSetting::where('code', 'impschargeabove25')->first();
                $data['aepsslabtime']   = \App\Models\PortalSetting::where('code', 'aepsslabtime')->first();
                $permission = "portal_setting";
                break;

            case 'links':
                $permission = "setup_links";
                break;
                
             case 'loginslide':
                $permission = "setup_links";
                break;    
             case 'mappingid':
                $permission = "mapping_manager";
                
                $data['parents'] = User::whereHas('role', function ($q){
                    $q->whereIn('slug',  ['distributor','employee']);
                })->get(['id', 'name', 'role_id', 'mobile']);  
                
            
                $data['alluser'] = User::whereHas('role', function ($q){
                    $q->where('slug', '=', 'retailer');
                })->get(['id', 'name', 'role_id', 'mobile'])->take(10);  
             
              break;
              
            case 'adminprofit':     
                   $permission = "portal_setting";
                   $data['apis'] = Api::whereIn('type', ['recharge', 'bill', 'pancard', 'money','fund'])->where('status', '1')->get(['id', 'product']);
                   $data['providers'] = Provider::whereIn('type', ['mobile','licslab','dth','electricity','pancard','dmt','aeps','fund','nsdlpan','tax','lpggas','gasutility','landline','postpaid','broadband','water','loanrepay','lifeinsurance','fasttag','cable','insurance','schoolfees','muncipal','housing','idstock','aadharpay','lic','onlinelic','licbillpay','cms','product','giblinsurance'])->get(['id', 'name']);
                break ;   
            default:
                abort(404);
                break;
        }

        if (!\Myhelper::can($permission)) {
            abort(403);
        }
        $data['type'] = $type;

        return view("setup.".$type)->with($data);
    }

    public function update(Request $post)
    {
        switch ($post->actiontype) {
            case 'api':
                $permission = "setup_api";
                break;

            case 'bank':
                $permission = "setup_bank";
                break;

            case 'operator':
                $permission = "setup_operator";
                break;

            case 'complaintsub':
                $permission = "complaint_subject";
                break;

            case 'portalsetting':
                $permission = "portal_setting";
                break;
            case 'mappingids':
                 $permission = 'mapping_manager' ;
                break ;
            case 'links':
                $permission = "setup_links";
                break;
        }

        if (isset($permission) && !\Myhelper::can($permission)) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }

        switch ($post->actiontype) {
            case 'bank':
                $rules = array(
                    'name'    => 'sometimes|required',
                    'account'    => 'sometimes|required|numeric|unique:fundbanks,account'.($post->id != "new" ? ",".$post->id : ''),
                    'ifsc'    => 'sometimes|required',
                    'branch'    => 'sometimes|required',
                    'charge'   => 'sometimes|required' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $post['user_id'] = \Auth::id();
                $action = Fundbank::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            
            case 'api':
                $rules = array(
                    'product'    => 'sometimes|required',
                    'name'    => 'sometimes|required',
                    'code'    => 'sometimes|required|unique:apis,code'.($post->id != "new" ? ",".$post->id : ''),
                    'type' => ['sometimes', 'required', Rule::In(['recharge', 'bill', 'money', 'pancard', 'fund'])],
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Api::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'operator':

                $rules = array(
                    'name'    => 'sometimes|required',
                    'recharge1'    => 'sometimes|required',
                    'recharge2'    => 'sometimes|required',
                    'type' => ['sometimes', 'required', Rule::In(['mobile','dth','electricity','pancard','dmt','aeps','fund','nsdlpan'])],
                    'api_id'    => 'sometimes|required|numeric',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Provider::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'complaintsub':
                $rules = array(
                    'subject'    => 'sometimes|required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Complaintsubject::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            case 'mappingids' : 
                $rules = array(
                    'parent_id'    => 'required',
                    'user_id'     => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }    
                if(\Myhelper::hasNotRole(['admin','employee'])){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }
                
             
                  $response = User::where('id', $post->user_id)->update(['parent_id'=>$post->parent_id]);
                  if ($response) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                
                break ;
            case 'portalsetting':
                $rules = array(
                    'value'    => 'required',
                    'name'     => 'required',
                    'code'     => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = PortalSetting::updateOrCreate(['code'=> $post->code], $post->all());;
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'links':
                $rules = array(
                    'name'    => 'required',
                    'value'    => 'required|url',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = Link::updateOrCreate(['id'=> $post->id], $post->all());;
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
                
                
               case 'slides':
                $rules = array(
                    'value' => 'sometimes|required',
                    'code'  => 'required',
                );
                
                $post['company_id'] = \Auth::user()->company_id;
                $validator = \Validator::make($post->all(), $rules);
                
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                
                // if($post->hasFile('slides')){
                //     $post['value'] = $post->file('slides')->store('slides');
                // }
                
                if ($files = $post->hasFile('slides')) {
             
                //store file into document folder
                $image = $post->file('slides');
                $name = $image->getClientOriginalName();                    
                $destinationPath = ('public/slides');
                $post['value'] = $image->move($destinationPath, $name);
     
                }
                
                $post['name'] = "Login Slide ".date('ymdhis');
                $action = PortalSetting::updateOrCreate(['name'=> $post->name], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
                
            case 'exceluploade':
               
                 if($post->file('excel')){
               
                $filename =  $post->file('excel');  
                  Excel::load($filename, function ($reader) {
 
                      foreach ($reader->toArray() as $key => $row) {
                      
                            $check = User ::where('mobile',$row['mobile'])->orWhere('email',$row['email'])->first() ;
                            if(!$check){
                            $password    =  $row['mobile'] ;
                            $data['name']       = $row['name'];
                            $data['agentcode']  = $row['agentcode'];
                            $data['email']      = $row['email'] ?? "retail@gmail.com".$key ;
                            $data['mobile']     = $row['mobile'] ;
                            $data['city']       = $row['city'] ;
                            $data['state']  = $row['state'];
                            $data['pincode']      = $row['pincode'] ;
                            $data['pancard']     = $row['pancard'] ;
                            $data['aadharcard']       = $row['aadharcard'] ;
                            $data['gender']       = $row['gender'] ;
                            $data['scheme_id']  =  "1";
                            $data['pincode']      = $row['pincode'] ;
                            $data['passwordold']   = $password;
                            $data['role_id']       = "2";
                            $data['parent_id']  = 1;
                            $data['passwordold']   = $password;
                            $data['password']   = bcrypt($password);
                            $data['company_id'] = "1";
                            $data['status']     = "block";
                            $data['kyc']        = "verified";
                            
                            $response=User::create($data);
                         }
                      }
                  });
                }
             return response()->json(['status'=>'success'], 200);
          break ; 
             
            default:
                # code...
                break;
        }
    }
}
