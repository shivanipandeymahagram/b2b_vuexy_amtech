<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Mahaagent;
use App\Models\Api;
use App\Models\Utiid;
use App\Models\Role;
use App\Models\Companydata;
use App\Models\Provider;
use App\Models\Microatmreport;
use App\Models\Aepsreport;
use App\Models\Securedata;
use App\Models\Pindata;
use App\Models\Packagecommission;
use App\Models\Commission;
use Carbon\Carbon;
use App\Models\LoanEnquiry;
use App\Models\Fingagent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class UserController extends Controller
{
     protected $api;
    public function __construct()
    {
        
        $this->api = Api::where('code', 'sadharverify')->first();
        
    }
    
    public function slider(Request $post){
        $user['slides'] = \App\Models\PortalSetting::where('code', 'slides')->get();
        if($user['slides']){
            return response()->json(['status' => 'TXN', 'message' => 'Slides Fatched successfully', 'slides' => $user]);
        }else{
            return response()->json(['status' => 'TXN', 'message' => 'Slides Not Found']);
        }
        
    }
    
    public function login(Request $post)
    {
        $rules = array(
            'password' => 'required',
            'mobile'  =>'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('mobile', $post->mobile)->with(['role'])->first();
        if(!$user){
            return response()->json(['status' => 'ERR', 'message' => "Your aren't registred with us." ]);
        }
      $geodata = geoip($post->ip());
          $log['ip']           = $post->ip();
          $log['user_agent']   = $post->server('HTTP_USER_AGENT');
          $log['user_id']      = $user->id;
          $log['geo_location'] = $geodata->lat."/".$geodata->lon;
          $log['url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
          $log['parameters']   = 'app';
          \DB::table('login_activitylogs')->insert($log);        
        
          if($user->role->slug == 'admin'){
          return response()->json(['status' => 'ERR', 'message' => "Admin Login is disabled in Application" ]);
        }
        if($user->kyc != 'verified' || $user->kyc == 'pending'){
          return response()->json(['status' => 'ERR', 'message' => " KYC is Not Approve" ]);
        }   

        if (!\Auth::validate(['mobile' => $post->mobile, 'password' => $post->password])) {
            return response()->json(['status' => 'ERR', 'message' => 'Username and Password is incorrect']);
        }

        if (!\Auth::validate(['mobile' => $post->mobile, 'password' => $post->password, 'status' => "active"])) {
            return response()->json(['status' => 'ERR', 'message' => 'Your account currently de-activated, please contact administrator']);
        }

        $apptoken = Securedata::where('user_id', $user->id)->first();
        
        if ($apptoken) {
            return response()->json(['status' => 'ERR', 'message' => 'You are already logged in to aonther devices']);
        }

        if(!$apptoken){
            do {
                $string = str_random(40);
            } while (Securedata::where("apptoken", "=", $string)->first() instanceof Securedata);

            try {
                $apptoken = Securedata::create([
                    'apptoken' => $string,
                    'ip'       => $post->ip(),
                    'user_id'  => $user->id,
                    'last_activity' => time()
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'ERR', 'message' => 'Already Logged In']);
            }
        }

        $user = User::where('mobile', $post->mobile)->with(['role'])->first();
        $user['apptoken']    = $apptoken->apptoken;
        $utiid = Utiid::where('user_id', $user->id)->first();
        $news = Companydata::where('company_id', $user->company_id)->first();
        $user['slides'] = \App\Models\PortalSetting::where('code', 'slides')->get();
        
        if($news){
            $user['news'] = $news->news;
            $user['notice'] = $news->notice;
            $user['billnotice'] = $news->billnotice;
            $user['supportnumber'] = $news->number;
            $user['supportemail'] = $news->email;
        }else{
            $user['news'] = "";
            $user['notice'] = "";
            $user['billnotice'] = "";
            $user['supportnumber'] = "";
            $user['supportemail'] = "";
        }

        if($utiid){
            $user['utiid'] = $utiid->vleid;
            $user['utiidtxnid'] = $utiid->id;
            $user['utiidstatus'] = $utiid->status;
        }else{
            $user['utiid'] = 'no';
            $user['utiidstatus'] = 'no';
            $user['utiidtxnid'] = 'no';
        }
        $settlementcharge= \DB::table('portal_settings')->where('code','settlementcharge')->first();
        $impschargeupto25= \DB::table('portal_settings')->where('code','impschargeupto25')->first();
        $impschargeabove25= \DB::table('portal_settings')->where('code','impschargeabove25')->first();
        $user['neftcharge'] = $settlementcharge->value;
        $user['upto25kimps'] = $impschargeupto25->value;
        $user['above25kimps'] = $impschargeabove25->value;
        
        $user['tokenamount'] = '107';
        return response()->json(['status' => 'TXN', 'message' => 'User details matched successfully', 'userdata' => $user]);
    }
    
    public function logout(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  => 'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        
        $delete = Securedata::where('user_id', $post->user_id)->where('apptoken', $post->apptoken)->delete();
        if($delete){
            return response()->json(['status' => 'TXN', 'message' => 'User Successfully Logout']);
        }else{
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong']);
        }
    }

    public function getbalance(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id',$post->user_id)->first(['id','mainwallet','aepsbalance']);
        if($user){
            $output['status'] = "TXN";
            $output['message'] = "Balance Fetched Successfully";
            $merchentid = \DB::table('upimerchants')->where('user_id',$user->id)->first();
            $aesmerchentid = \DB::table('aepsusers')->where('user_id',$user->id)->first();
           if($merchentid){
              $upimerchent = "true";    
            }else{
              $upimerchent = "false";      
            }
            if(isset($aesmerchentid->status) && $aesmerchentid->status == "success"){
              $aepsmerchent = "true";    
            }else{
              $aepsmerchent = "false";       
            }
            $aesmerchentid = \DB::table('aepsusers')->where('user_id',$post->user_id)->first();
            if($aesmerchentid){
                      $aepsmerchent = "true";
                      if($aesmerchentid->status == "pending"){
                           $aepsmerchent = "pending";  
                      }else if($aesmerchentid->status == "rejected"){
                         $aepsmerchent = "false";         
                      }
                   }else{
                      $aepsmerchent = "false";      
                   }
            $gpsdata = geoip($post->ip());
            $pId = "PS003059" ;
            $pApiKey = "UFMwMDMwNTlhMTliYjJmMDNmY2JjNWFkMzJhYWJmYzMzYWFhNGNjNg==" ;
            $mCode = $aesmerchentid->merchantLoginId ??  "MB".date('ymd').$user->id;
            $lon= $gpsdata->lon;
            $lat = $gpsdata->lat;
            $output['data'] = [ 'pasprintonboard'=>$aepsmerchent, "mainwallet" => $user->mainwallet , "aepsbalance" => $user->aepsbalance,'pasprintonboard'=>$aepsmerchent,'upimerchent'=>$upimerchent ,"pId"=>$pId, 'apiKey'=>$pApiKey, 'lat'=>$lat,'lon'=>$lon, "mCode"=>$mCode];
        }else{
            $output['status'] = "ERR";
            $output['message'] = "User details not matched";
        }
        return response()->json($output);
    }

    public function aepsInitiate(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        if (!\Myhelper::can('aeps_service', $post->user_id)) {
            return response()->json(['status' => "ERR", "message" => "Service Not Allowed"]);
        }

        $user = User::where('id', $post->user_id)->count();
        if($user){
            $agent = Mahaagent::where('user_id', $post->user_id)->first();
            
            if($agent){
                $api = Api::where('code', 'aeps')->first();

                if(!$api || $api->status == 0){
                    return response()->json(['status' => "ERR", "message" => "Service Not Allowed"]);
                }

                $output['status'] = "TXN";
                $output['message'] = "Deatils Fetched Successfully";
                $output['data'] = [ 
                    "saltKey" => $api->username , 
                    "secretKey" => $api->password,
                    "BcId" => $agent->bc_id,
                    "UserId" => $post->user_id,
                    "bcEmailId" => $agent->emailid,
                    "Phone1" => $agent->phone1
                ];
            }
            else{
                $output['status'] = "ERR";
                $output['message'] = "Aeps Registration Pending";
            }
        }else{
            $output['status'] = "ERR";
            $output['message'] = "User details not matched";
        }

        return response()->json($output);
    }

    public function microatmInitiate(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        if (!\Myhelper::can('matm_service', $post->user_id)) {
            return response()->json(['status' => "ERR", "message" => "Service Not Alloweds"]);
        }

        $user = User::where('id', $post->user_id)->first();
        if($user){

            $agent = Mahaagent::where('user_id', $post->user_id)->first();

            if($agent){
                $api = Api::where('code', 'microatm')->first();

                if(!$api || $api->status == 0){
                    return response()->json(['status' => "ERR", "message" => "Service Not Allowed"]);
                }

                do {
                    $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                } while (Microatmreport::where("txnid", "=", $post->txnid)->first() instanceof Microatmreport);

                $insert = [
                    "mobile"   => $agent->phone1,
                    "aadhar"   => $agent->bc_id,
                    "txnid"    => $post->txnid,
                    "user_id"  => $user->id,
                    "balance"  => $user->aepsbalance,
                    'status'   => 'pending',
                    'credited_by' => $user->id,
                    'type'        => 'credit',
                    'api_id'      => $api->id,
                    'aepstype'    => "matm"
                ];

                $matmreport = Microatmreport::create($insert);

                if($matmreport){
                    $output['status'] = "TXN";
                    $output['message'] = "Deatils Fetched Successfully";
                    $output['data'] = [ 
                        "saltKey" => $api->username , 
                        "secretKey" => $api->password,
                        "BcId" => $agent->bc_id,
                        "UserId" => $post->user_id,
                        "bcEmailId" => $agent->emailid,
                        "Phone1" => $agent->phone1,
                        "clientrefid" => $post->txnid
                    ];
                }else{
                    $output['status'] = "ERR";
                    $output['message'] = "Something went wrong, please try again";
                }
            }
            else{
                $output['status'] = "ERR";
                $output['message'] = "Aeps Registration Pending";
            }
        }else{
            $output['status'] = "ERR";
            $output['message'] = "User details not matched";
        }

        return response()->json($output);
    }

    public function microatmUpdate(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        if (!\Myhelper::can('matm_service', $post->user_id)) {
            return response()->json(['status' => "ERR", "message" => "Service Not Allowed"]);
        }

        $user = User::where('id', $post->user_id)->first();
        if(!$user){
            $output['status'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        $response = json_decode($post->response);

        $rules = array(
            // 'clientrefid' => 'required',
            // 'refstan'     => 'required',
            // 'statuscode'  => 'required',
            // 'tid'      => 'required',
            // 'txnamount'=> 'required',
            // 'cardno'   => 'required',
            // 'mid'      => 'required',
        );

        $validator = \Validator::make((array)$response, array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(array(
                'status' => 'ERR',  
                'message' => $error
            ));
        }

        $report = Microatmreport::where('txnid', $response->clientrefid)->where('user_id', $post->user_id)->first();

        if(!$report){
            $output['status'] = "ERR";
            $output['message'] = "Report Not Found";
            return response()->json($output);
        }

        $update['amount'] = $response->txnamount;
        $update['payid']  = $response->refstan;
        $update['refno']  = $response->rrn;
        $update['remark'] = $response->bankremarks;
        $update['aadhar'] = $response->cardno;
        $update['mytxnid']= $response->refstan;
        $update['balance']= $user->aepsbalance;
        if($response->statuscode === '00'){
            $update['status'] = "success";
        }
        
        $updates = Microatmreport::where('id', $report->id)->update($update);
        
        if($updates && $update['amount'] > 0 && $response->statuscode === '00'){
            if($response->txnamount >= 500 && $response->txnamount <= 999){
                $provider = Provider::where('recharge1', 'matm1')->first();
            }elseif($response->txnamount > 1000 && $response->txnamount <= 1499){
                $provider = Provider::where('recharge1', 'matm2')->first();
            }elseif($response->txnamount > 1500 && $response->txnamount <= 1999){
                $provider = Provider::where('recharge1', 'matm3')->first();
            }elseif($response->txnamount > 2000 && $response->txnamount <= 2999){
                $provider = Provider::where('recharge1', 'matm4')->first();
            }elseif($response->txnamount > 3000 && $response->txnamount <= 3499){
                $provider = Provider::where('recharge1', 'matm5')->first();
            }elseif($response->txnamount > 3500 && $response->txnamount <= 4999){
                $provider = Provider::where('recharge1', 'matm6')->first();
            }elseif($response->txnamount > 5000 && $response->txnamount <= 10000){
                $provider = Provider::where('recharge1', 'matm7')->first();
            }
            
            $post['provider_id'] = $provider->id;
            $update['provider_id'] = $provider->id;
            if($response->txnamount > 500){
                $update['charge'] = \Myhelper::getCommission($response->txnamount, $user->scheme_id, $post->provider_id, $user->role->slug);
            }else{
                $update['charge'] = 0;
            }

            $credit = User::where('id', $user->id)->increment('aepsbalance', $update['amount'] + $update['charge']);

            if($credit){
                $updates  = Microatmreport::where('id', $report->id)->update($update);
                $myreport = Microatmreport::where('id', $report->id)->first();

                $insert = [
                    "mobile"  => $myreport->mobile,
                    "aadhar"  => $myreport->aadhar,
                    "api_id"  => $myreport->api_id,
                    "provider_id"  => $provider->id,
                    "txnid"   => $myreport->txnid,
                    "refno"   => $myreport->refno,
                    "amount"  => $myreport->amount,
                    "charge"  => $myreport->charge,
                    "bank"    => $myreport->bank,
                    "user_id" => $myreport->user_id,
                    "balance" => $user->aepsbalance,
                    'aepstype'=> $myreport->aepstype,
                    'status'  => 'success',
                    'authcode'=> $myreport->authcode,
                    'payid'   => $myreport->payid,
                    'mytxnid' => $myreport->mytxnid,
                    'terminalid' => $myreport->terminalid,
                    'TxnMedium'  => $myreport->TxnMedium,
                    'credited_by'=> $myreport->credited_by,
                    'type'    => 'credit',
                ];

                $matm = Aepsreport::create($insert);
                try {
                    if($response->txnamount > 500){
                        \Myhelper::commission(Aepsreport::where('id', $matm->id)->first());
                    }
                } catch (\Exception $e) {}
            }
        }
        
        $output['status']  = "TXN";
        $output['message'] = "Transaction Successfully";
            
        return response()->json($output);
    }
    
    public function registration(Request $post)
    {
        $rules = array(
            'name'       => 'required',
            'mobile'     => 'required|numeric|digits:10|unique:users,mobile',
            'email'      => 'required|email|unique:users,email',
            'shopname'   => 'required|unique:users,shopname',
            'pancard'    => 'required|unique:users,pancard',
            'aadharcard' => 'required|numeric|unique:users,aadharcard|digits:12',
            'state'      => 'required',
            'city'       => 'required',
            'address'    => 'required',
            'pincode'    => 'required|digits:6|numeric',
            'slug'       => ['required', Rule::In(['retailer', 'md', 'distributor', 'whitelable'])]
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first(['id', 'company_id']);
        $insertuser = $post->all() ; 
        $role = Role::where('slug', $post->slug)->first();

        $insertuser['role_id']    = $role->id;
        $insertuser['id']         = "new";
        $insertuser['parent_id']  = $admin->id;
        $insertuser['password']   = bcrypt('12345678');
        $insertuser['company_id'] = $admin->company_id;
        $insertuser['status']     = "block";
        $insertuser['kyc']        = "pending";

        $scheme = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $role->id)->first();
        if($scheme){
            $insert['scheme_id'] = $scheme->permission_id;
        }

        $response = User::updateOrCreate(['id'=> $post->id],$insertuser); 
        if($response){
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id', $post->role_id)->get();
            if(sizeof($permissions) > 0){
                foreach ($permissions as $permission) {
                    $insert = array('user_id'=> $response->id , 'permission_id'=> $permission->permission_id);
                    $inserts[] = $insert;
                }
                \DB::table('user_permissions')->insert($inserts);
            }

            try {
                $regards="";
                $content = "Dear Partner, your login details are mobile - ".$post->mobile." & password - 12345678 Don't share with anyone Regards ".$regards." LCO FINTECH(OPC) PRIVATE LIMITED";
                \Myhelper::sms($post->mobile, $content);

                $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
                $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();

                $mail = \Myhelper::mail('mail.member', ["username" => $post->mobile, "password" => "12345678", "name" => $post->name], $post->email, $post->name, $otpmailid, $otpmailname, "Member Registration");
            } catch (\Exception $e) {}

            return response()->json(['status' => "TXN", 'message' => "Success"], 200);
        }else{
            return response()->json(['status' => 'ERR', 'message' => "Something went wrong, please try again"], 400);
        }
    }

    public function passwordResetRequest(Request $post)
    {
        $rules = array(
            'mobile'  =>'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = \App\User::where('mobile', $post->mobile)->first();
        if($user){
            $otp = rand(11111111, 99999999);
            $regards="";
            $content = "Dear partner, your password reset token is ".$otp." Don't share with anyone Regards ".$regards.". LCO FINTECH(OPC) PRIVATE LIMITED";
            $sms = \Myhelper::sms($post->mobile, $content);
            $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
            $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();
            try {
                $mail = \Myhelper::mail('mail.password', ["token" => $otp, "name" => $user->name], $user->email, $user->name, $otpmailid->value, $otpmailname->value, "Reset Password");
            } catch (\Exception $e) {
                $mail = "fail";
            }
            
            if($sms == "success" || $mail == "success"){
                \App\User::where('mobile', $post->mobile)->update(['remember_token'=> $otp]);
                return response()->json(['status' => 'TXN', 'message' => "Password reset token sent successfully"]);
            }else{
                return response()->json(['status' => 'ERR', 'message' => "Something went wrong"]);
            }
        }else{
            return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us"]);
        } 
    }

    public function passwordReset(Request $post)
    {
        $rules = array(
            'mobile'  =>'required|numeric',
            'password'  =>'required',
            'token'  =>'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = \App\User::where('mobile', $post->mobile)->where('remember_token' , $post->token)->get();
        if($user->count() == 1){
            $update = \App\User::where('mobile', $post->mobile)->update(['password' => bcrypt($post->password),'passwordold' => $post->password]);
            if($update){
                return response()->json(['status' => "TXN", 'message' => "Password reset successfully"], 200);
            }else{
                return response()->json(['status' => 'ERR', 'message' => "Something went wrong"], 400);
            }
        }else{
            return response()->json(['status' => 'ERR', 'message' => "Please enter valid token"], 400);
        }
    }
    
    public function changepassword(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
            'oldpassword'  =>'required|min:8',
            'password'  =>'required|min:8',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->first();
        if(!\Myhelper::can('password_reset', $post->user_id)){
            return response()->json(['status' => 'ERR', 'message' => "Permission Not Allowed"]);
        }
       if(!Hash::check($post->oldpassword, $user->password)){
            return response()->json(['status' => 'ERR', 'message' => "Please enter correct old password"]);
            }
        if(\Myhelper::hasNotRole('admin')){
            $credentials = [
                'mobile' => $user->mobile,
                'password' => $post->oldpassword
            ];
    
            if(!\Auth::validate($credentials)){
                return response()->json(['status' => 'ERR', 'message' => "Please enter corret old password"]);
            }
        }

        $post['passwordold'] = $post->password;
      //  $post['password'] = bcrypt($post->password);

        $response = User::where('id', $post->user_id)->update(['password' => bcrypt($post->password)]);
        if($response){
            return response()->json(['status' => 'TXN', 'message' => 'User password changed successfully']);
        }else{
            return response()->json(['status' => 'ERR', 'message' => "Something went wrong"]);
        }
    }

    // public function getState()
    // {
    //     $state = \App\Models\Circle::all(['state']);
    //     return response()->json(['status' => 'TXN', 'message' => 'State Details', 'data' => $state]);
    // }

    public function changeProfile(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
            'name'     =>'required',
            'email'    =>'required|email',
            'address'  =>'required',
            'pincode'  =>'required|numeric|digits:6',
            'pancard'     =>'required',
            'aadharcard'  =>'required|numeric|digits:12',
            'shopname'    =>'required',
            'city'    =>'required',
            'state'   =>'required'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->count();

        if($user == 0){
            $output['status'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        $update = User::where('id', $post->user_id)->update(array(
            'name'     => $post->name,
            'email'    => $post->email,
            'address'  => $post->address,
            'pincode'  => $post->pincode,
            'pancard'     => $post->pancard,
            'aadharcard'  => $post->aadharcard,
            'shopname'    => $post->shopname,
            'city'    => $post->city,
            'state'   => $post->state
        ));

        if($update){
            return response()->json(['status' => 'TXN', 'message' => 'User profile updated successfully']);
        }else{
            return response()->json(['status' => 'ERR', 'message' => "Something went wrong"]);
        }
    }
    public function getotp(Request $post)
    {
        $rules = array(
            'mobile'  =>'required|numeric'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
          $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
                $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();
        $user = \App\User::where('mobile', $post->mobile)->first();
        if($user){
            $otp = rand(111111, 999999);
            $regards="";
           $content = "Dear partner, your TPIN reset otp is ".$otp." Don't share with anyone Regards ".$regards." LCO FINTECH(OPC) PRIVATE LIMITED";
            $sms = \Myhelper::sms($post->mobile, $content);
              try {
                $mail = \Myhelper::mail('mail.otp', ["otp" => $otp, "name" => $user->name], $user->email, $user->name, $otpmailid->value, $otpmailname->value, "Reset Password");
            } catch (\Exception $e) {
                 // dd($e) ;
                $mail = "fail";
            }
            if($mail == "success" || $sms == "success"){
                $user = \DB::table('password_resets')->insert([
                    'mobile' => $post->mobile,
                    'token' => \Myhelper::encrypt($otp, "sdsada7657hgfh$$&7678"),
                    'last_activity' => time()
                ]);
            
                return response()->json(['statuscode' => 'TXN', 'message' => "Otp has been send successfully on your Registered Email "]);
            }else{
                return response()->json(['statuscode' => 'ERR', 'message' => "Something went wrong"]);
            }
        }else{
            return response()->json(['statuscode' => 'ERR', 'message' => "You aren't registered with us"]);
        }  
    }
    
    public function setpin(Request $post)
    {
        //dd(\Myhelper::encrypt($post->otp, "a6e028f0c683"));
        $rules = array(
            'otp'  =>'required|numeric',
            'tpin'  =>'required|numeric|confirmed',
            'mobile'=> 'required|numeric'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = \DB::table('password_resets')->where('mobile', $post->mobile)->where('token' , \Myhelper::encrypt($post->otp, "sdsada7657hgfh$$&7678"))->first();
        if($user){
            try {
                Pindata::where('user_id', $post->user_id)->delete();
                $apptoken = Pindata::create([
                    'pin' => \Myhelper::encrypt($post->tpin, "sdsada7657hgfh$$&7678"),
                    'user_id'  => $post->user_id
                ]);
            } catch (\Exception $e) {
                return response()->json(['statuscode' => 'ERR', 'message' => 'Try Again']);
            }
            
              if($apptoken){
                \DB::table('password_resets')->where('mobile', $post->mobile)->where('token' , \Myhelper::encrypt($post->otp, "sdsada7657hgfh$$&7678"))->delete();
                return response()->json(['statuscode' => "TXN", "message" => 'Transaction Pin Generate Successfully']);
            }else{
                return response()->json(['statuscode' => "ERR", "message" => "Something went wrong"]);
            }
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Please enter valid otp"]);
        }  
    }
    
    public function addMember(Request $post)
    {
        $rules = array(
            'user_id'       => 'required',
            'name'       => 'required',
            'mobile'     => 'required|numeric|digits:10|unique:users,mobile',
            'email'      => 'required|email|unique:users,email',
            'shopname'   => 'required|unique:users,shopname',
            'pancard'    => 'required|unique:users,pancard',
            'aadharcard' => 'required|numeric|unique:users,aadharcard|digits:12',
            'state'      => 'required',
            'city'       => 'required',
            'address'    => 'required',
            'pincode'    => 'required|digits:6|numeric',
           // 'role_id'    => 'required'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $admin = User::where('id', $post->user_id)->first(['id', 'company_id']);

        $post['role_id']    = $post->role_id;
        $post['id']         = "new";
        $post['parent_id']  = $post->user_id;
        $post['password']   = bcrypt('12345678');
        $post['company_id'] = $admin->company_id;
        $post['status']     = "active";
        $post['role_id']     = "4";
        $insertuser['kyc']   = "pending";

        $scheme = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $post->role_id)->first();
        if($scheme){
            $post['scheme_id'] = $scheme->permission_id;
        }

        $response = User::updateOrCreate(['id'=> $post->id], $post->all());
        if($response){
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id', $post->role_id)->get();
            if(sizeof($permissions) > 0){
                foreach ($permissions as $permission) {
                    $insert = array('user_id'=> $response->id , 'permission_id'=> $permission->permission_id);
                    $inserts[] = $insert;
                }
                \DB::table('user_permissions')->insert($inserts);
            }

            try {
                $content = "Dear Sahaj Money partner, your login details are mobile - ".$post->mobile." & password - ".$post->mobile;
                \Myhelper::sms($post->mobile, $content);

                $otpmailid   = \App\Models\PortalSetting::where('code', 'otpsendmailid')->first();
                $otpmailname = \App\Models\PortalSetting::where('code', 'otpsendmailname')->first();

                $mail = \Myhelper::mail('mail.member', ["username" => $post->mobile, "password" => "12345678", "name" => $post->name], $post->email, $post->name, $otpmailid, $otpmailname, "Member Registration");
            } catch (\Exception $e) {}

            return response()->json(['statuscode' => "TXN", 'message' => "Thank you for choosing, your request is successfully submitted for approval"], 200);
        }else{
            return response()->json(['statuscode' => 'ERR', 'message' => "Something went wrong, please try again"], 400);
        }
    }
    
     public function aepskyc(Request $post){
        $this->api  = Api::where('code', 'aeps')->first(); 
        
        //dd($this->api);
        
        $data["bc_f_name"] = $post->bc_f_name;
        $data["bc_m_name"] = $post->bc_m_name;
        $data["bc_l_name"] = $post->bc_l_name;
        $data["emailid"] = $post->emailid;
        $data["phone1"] = $post->phone1;
        $data["phone2"] = $post->phone2;
        $data["bc_dob"] = $post->bc_dob;
        $data["bc_state"] = $post->bc_state;
        $data["bc_district"] = $post->bc_district;
        $data["bc_address"] = $post->bc_address;
        $data["bc_block"] = $post->bc_block;
        $data["bc_city"] = $post->bc_city;
        $data["bc_landmark"] = $post->bc_landmark;
        $data["bc_mohhalla"] = $post->bc_mohhalla;
        $data["bc_loc"] = $post->bc_loc;
        $data["bc_pincode"] = $post->bc_pincode;
        $data["bc_pan"] = $post->bc_pan;
        $data["shopname"] = $post->shopname;
        $data["shopType"] = $post->shopType;
        $data["qualification"] = $post->qualification;
        $data["population"] = $post->population;
        $data["locationType"] = $post->locationType;
        $data["saltkey"] = $this->api->username;
        $data["secretkey"] = $this->api->password;
        $data['kyc1'] = $post->kyc1;
        $data['kyc2'] = $post->kyc2;
        $data['kyc3'] = $post->kyc3;
        $data['kyc4'] = $post->kyc4;
        
        $url = $this->api->url."AEPS/APIBCRegistration";
        //dd($url);
        
        $header = array("Content-Type: application/json");
        $result = \Myhelper::curl($url, "POST", json_encode($data), $header, "no");
        if($result['response'] != ''){
            $response = json_decode($result['response']);
            if($response[0]->Message == "Success"){
                $data['bc_id'] = $response[0]->bc_id;
                $data['user_id'] = $post->user_id;
                $user = Mahaagent::create($data);

                try {
                    $gpsdata = geoip($post->ip());
                    $name  = $post->bc_f_name." ".$post->bc_l_name;
                    $burl  = $this->billapi->url."RegBBPSAgent";

                    $json_data = [
                        "requestby"     => $this->billapi->username,
                        "securityKey"   => $this->billapi->password,
                        "name"          => $name,
                        "contactperson" => $name,
                        "mobileNumber"  => $post->phone1,
                        'agentshopname' => $post->shopname,
                        "businesstype"  => $post->shopType,
                        "address1"      => $post->bc_address,
                        "address2"      => $post->bc_city,
                        "state"         => $post->bc_state,
                        "city"          => $post->bc_district,
                        "pincode"       => $post->bc_pincode,
                        "latitude"      => sprintf('%0.4f', $gpsdata->lat),
                        "longitude"     => sprintf('%0.4f', $gpsdata->lon),
                        'email'         => $post->emailid
                    ];
                    
                    $header = array(
                        "authorization: Basic ".$this->billapi->optional1,
                        "cache-control: no-cache",
                        "content-type: application/json"
                    );
                    $bbpsresult = \Myhelper::curl($burl, "POST", json_encode($json_data), $header, "yes", 'MahaBill', $post->phone1);
                    //dd($bbpsresult);
                    if($bbpsresult['response'] != ''){
                        $response = json_decode($bbpsresult['response']);
                        if(isset($response->Data)){
                            $datas = $response->Data;
                            if(!empty($datas)){
                                $data['bbps_agent_id'] = $datas[0]->agentid;
                            }
                        }
                    }
                } catch (\Exception $e) {}
                
                return response()->json(['statuscode'=>'TXN',  'message'=> "Kyc Submitted"]);
            }else{
                return response()->json(['statuscode'=>'TXF',  'message'=> $response[0]->Message]);
            }
        }else{
            return response()->json(['statuscode'=>'TXF', 'message'=> "Something went wrong"]);
        }
        
    }
    
      public function updateprofile(Request $post){
        $rules = array(
           'apptoken' => 'required',
            'user_id'  => 'required|numeric',
           'name'       => 'required',
            'mobile'     => 'required|numeric|digits:10',
            'email'      => 'required|email',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        
        
        $user = \App\User::where('id', $post->user_id)->first();
        if(!$user){
            return response()->json(['status' =>'TXF',"message"=> "Your aren't registred with us." ]);
        }
        
        if($post->hasFile('adharfrontpic')){
            $img_adharfront     =   rand(1, 999).time().'.'.$post->adharfrontpic->getClientOriginalExtension();
            $Aadharfolder       =   public_path('kyc/adharcard');
            $adharfront_upload  =   $post->adharfrontpic->move($Aadharfolder, $img_adharfront);
            $post['aadharcardpic'] =   $img_adharfront;
        }
        
        if($post->hasFile('adharbackpic')){
            $img_adharback      =   rand(1, 999).time().'.'.$post->adharbackpic->getClientOriginalExtension();
            $adharback_upload   =   $post->adharbackpic->move($Aadharfolder, $img_adharback);
            $post['aadharcardbackpic']  =   $img_adharback;
        }
        
        if($post->hasFile('pancardpics')){
            $img_panfront       =   rand(1, 999).time().'.'.$post->pancardpics->getClientOriginalExtension();
             $Panfolder          =   public_path('kyc/pancard');
             $panfront_upload    =   $post->pancardpics->move($Panfolder, $img_panfront);
             $post['pancardpic']         =   $img_panfront;
        }
       
        if($post->hasFile('profilepic')){
            $img_profilepic     =   rand(1, 999).time().'.'.$post->profilepic->getClientOriginalExtension();
            $Profilesfolder     =   public_path('profiles');
            $profile_upload     =   $post->profilepic->move($Profilesfolder, $img_profilepic);
            $post['profile']            =   $img_profilepic;
        }
         
        $response = User::updateOrCreate(['id'=> $user->id], $post->all());
        $data = User::where('id', $user->id)->first();
        if($response){
            return response()->json(['status' =>'TXN',"message"=> "Your Request successfully Updated " ,'data'=>$data]);
        }else{
            return response()->json(['status' =>'TXN',"message"=> "Your Request not Upadte" ]);
        }
        
    }

   public function bcstatus(Request $post){
        // dd("ttttt");
        $user = User::where('id', $post->user_id)->count();
        if($user){
            $agent = Mahaagent::where('user_id', $post->user_id)->first();
            if($agent){
               $data['bc_id'] = $agent->bc_id;
               $data['phone1'] = $agent->phone1;
               $data['status'] = $agent->status;
            }
            return response()->json(['statuscode'=>'TXN',  'message'=> "Bc id fatched successfully",'data'=>$data]);
        }
        
    }
    
         public function adharnumberverify(Request $post){
            $user = User:: where('id',$post->user_id)->first();
            switch($post->type){
                case 'getotp':
                    $post['user_id'] = $user->id ?? 0 ;
                    $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                
                    $post['txnid'] = $this->transcode().rand(1111111111,1000000000);
                   // $url = $this->api->url."generateOTP";
                    $url ="https://api.paysprint.in/api/v1/service/verification/aadharcard/generateOTP" ;
                    $parameter = [
                        "refid" => $post->txnid,
                        "aadhar_number" =>$post->aadharcard,
                    ];
                    
                    $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                    $header = array(
                        "Cache-Control: no-cache",
                        "Content-Type: application/json",
                        "Token: ".$token['token'],
                        "Authorisedkey: ".$this->api->optional1
                    );
                    $parameter = json_encode($parameter);
                    $result = \Myhelper::curl($url, "POST", $parameter, $header, "no");
                    //dd([$url,$parameter,$header,$result]);
                    $response = json_decode($result['response']);
                    if (isset($response->status) && $response->status ==true){
                        return response()->json(['status' => 'TXNOTP',"refid" => $post->txnid,'message' => 'OTP send successfully']);
                    }else{
                        return response()->json(['status' => 'ERR', 'message' => isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                break;
                
                case 'otpverify':
                   
                    $post['user_id'] = $user->id ?? 0;
                    $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                   // $url = $this->api->url."verifyOTP";
                   $url = 'https://api.paysprint.in/api/v1/service/verification/aadharcard/verifyOTP' ;
                    $parameter = [
                        "refid" => $post->refid,
                        "otp" =>$post->otp,
                    ];
                    
                    $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                    $header = array(
                        "Cache-Control: no-cache",
                        "Content-Type: application/json",
                        "Token: ".$token['token'],
                        "Authorisedkey: ".$this->api->optional1
                    );
                    
                    $parameter = json_encode($parameter);
                    $result = \Myhelper::curl($url, "POST", $parameter, $header, "no");
                    //dd([$url,$parameter,$header,$result]);
                    $response = json_decode($result['response']);
                   // dd($response->data);
                    if (isset($response->status) && $response->status ==true && isset($response->data)){
                        $resp=\DB::table('aadhar_details')->insert([
                                    'full_name'=>$response->data->full_name,
                                    'aadhaar_number'=>$response->data->aadhaar_number,
                                    'dob'=>$response->data->dob,
                                    'gender'=>$response->data->gender,
                                    'address'=>json_encode($response->data->address),
                                    'zip'=>$response->data->zip,
                                    'profile_image'=>$response->data->profile_image,
                                ]);
                                
                               // dd($resp);
                        return response()->json(['status' => 'TXN',
                                    'full_name'=>$response->data->full_name,
                                    'aadhaar_number'=>$response->data->aadhaar_number,
                                    'message' => 'Verified successfully']);
                    }else{
                        return response()->json(['status' => 'ERR', 'message' => isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                break;
                
               case 'panverify':
                    $post['user_id'] = $user->id ?? 0;
                    $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                    $url = 'https://api.paysprint.in/api/v1/service/pan/verify';
                    $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                    $query = [
                        "referenceid" => $post->txnid,
                        "pannumber" =>$post->pancard,
                    ];
                    
                    $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                    $header = array(
                        "Cache-Control: no-cache",
                        "Content-Type: application/json",
                        "Token: ".$token['token'],
                        "Authorisedkey: ".$this->api->optional1
                    );
                    
                    $parameter = json_encode($query);
                    $result = \Myhelper::curl($url, "POST", $parameter, $header, "no");
                     \DB::table('rp_log')->insert([
                        'ServiceName' => $post->type,
                        'header' => json_encode($header),
                        'body' => json_encode([$parameter]),
                        'response' => $result['response'],
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);  
                    $response = json_decode($result['response']);
                    //dd($response->data);
                    if (isset($response->status) && $response->status ==true && isset($response->data)){
                        $resp=\DB::table('pandetails')->insert([
                                    'pan_number'=>$response->data->pan_number,
                                    'refid'=> $post->txnid,
                                    'last_name'=>$response->data->last_name,
                                    'middle_name'=>$response->data->middle_name,
                                    'first_name'=>$response->data->first_name,
                                    'title'=>$response->data->title
                                ]);
                        return response()->json(['status' => 'TXN',
                                    'full_name'=>$response->data->first_name,
                                    'pan_number'=>$response->data->pan_number,
                                    'message' => 'Verified successfully']);
                    }else{
                        return response()->json(['status' => 'ERR', 'message' => isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                    
                    break ;    
                
            }
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


     public function getcommission(Request $post)
    {
        $rules = array(
           
            'user_id'  =>'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id',$post->user_id)->first();
        if($user){
            $product = ['mobile', 'dth', 'electricity', 'pancard', 'dmt', 'aeps'];
            if($this->schememanager() != "all"){
                foreach ($product as $key) {
                    $output['commission'][$key] = Commission::where('scheme_id', $user->scheme_id)->whereHas('provider', function ($q) use($key){
                        $q->where('type' , $key);
                    })->get(); 
                }
            }else{
                foreach ($product as $key) {
                    $output['commission'][$key] = Packagecommission::where('scheme_id', $user->scheme_id)->whereHas('provider', function ($q) use($key){
                        $q->where('type' , $key);
                    })->get();
                }
            }
                
            $output['status'] = "TXN";
            $output['message'] = "Balance Fetched Successfully";
        }else{
            $output['status'] = "ERR";
            $output['message'] = "User details not matched";
        }
        return response()->json($output);
    }
    
    public function getroles(Request $post){
	   // $rules = array(
    //         'type'       => 'required',

    //     );
    //     $validate = \Myhelper::FormValidator($rules, $post);
    //     if($validate != "no"){
    //         return $validate;
    //     }
        
        if($post->type=="register"){
            $roles=Role::whereIn('id',['4'])->get();
        }else{
            $data=Role::where('slug',"md")->first();
            if($data){
                $roles=Role::where('pserial','>',$data->pserial)->get();
            }
        }
    }
    
    public function loanenquiery(Request $post){

        $response=LoanEnquiry::create($post->all());
        if($response){
             return response()->json(['statuscode' => "TXN", 'message' => "Success"], 200);
        }else{
            return response()->json(['statuscode' => 'ERR', 'message' => "Something went wrong, please try again"], 400);
        }
    }
    
      public function getemployeelist(Request $post){

          $agents = User::where("role_id","7")->get(['id','name','mobile']);
        if($agents){
             return response()->json(['statuscode' => "TXN", 'message' => "Success",'data'=>$agents], 200);
        }else{
            return response()->json(['statuscode' => 'ERR', 'message' => "Something went wrong, please try again"], 400);
        }
    }
    
      public function accountListing(Request $post)
     {
      $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
        );
        
          $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        $user = User::where('id',$post->user_id)->first();
       if(!$user){
        return response()->json(['statuscode'=>'ERR',  'message'=> "Something Went Wrong"]);   
       }
       if($user->account <> ''){
        if($user->account != ''){   
        $data []= [
            "account"=>$user->account,
            "accountholdername"=>$user->accountholdername,
            "ifsc"=>$user->ifsc,
            "bank"=>$user->bank
            
            ];
        }
        if($user->account2 != ''){
        $data []= [
            "account"=>$user->account2,
            "accountholdername"=>$user->accountholdername2,
            "ifsc"=>$user->ifsc2,
            "bank"=>$user->bank2
            
            ];
        }
        if($user->account3 != ''){
         $data []= [
            "account"=>$user->account3,
            "accountholdername"=>$user->accountholdername3,
            "ifsc"=>$user->ifsc3,
            "bank"=>$user->bank3
            
            ];    
        }    
        return response()->json(['statuscode'=>'TXN',  'message'=> "Account Fetched Successfully",'data'=>$data]);
       }else{
           return response()->json(['statuscode'=>'ERR',  'message'=> "Account Not Found"]);
       }
    }
   //end 
   
     public function addAccount(Request $post)
     {
         
       $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
        );
        
          $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        $user = User::where('id',$post->user_id)->first();
     
       if(!$user){
        return response()->json(['statuscode'=>'ERR',  'message'=> "Something Went Wrong"]);   
       }
       
        //  if ($this->txnpinCheck($post) == "fail") {
        //              return response()->json(['statuscode'=>'ERR',  'message'=> "Invalid Otp"], 200);
        //         }
                $userdata = \App\User::where('id', $post->user_id)->first();
              //  $post['amount'] = 1;
             //$provider = Provider::where('recharge1', 'dmt1accverify')->first();
                // $post['charge'] = \Myhelper::getCommission($post->amount, $userdata->scheme_id, $provider->id, $userdata->role->slug);
                // if($userdata->mainwallet < $post->amount + $post->charge){
                //     return response()->json(["statuscode" => "IWB", 'message' => 'Low balance, kindly recharge your wallet.']);
                // }
                // $post['provider_id'] = $provider->id;
               
                // $authToken = $this->getToken();
                // $header = array(
                //     'Authorization: Bearer '.$authToken,
                //     'Content-Type: application/json'
                // );
                
                // do {
                //     $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                // } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);
                 if($user->account3 == '' && $user->bank3 == '' && $user->ifsc3 == ''){
                             $userdata = User::where('id', $post->user_id)->first();
                            if(isset($post->account) && $post->account != ''){
                                $post['beneaccount'] = $post->account;
                                $post['beneifsc'] = $post->ifsc;
                            }else if(isset($post->account2) && $post->account2 != ''){
                                $post['beneaccount'] = $post->account2;
                                $post['beneifsc'] = $post->ifsc2;
                            }else if(isset($post->account3) && $post->account3 != ''){
                                $post['beneaccount'] = $post->account3;
                                $post['beneifsc'] = $post->ifsc3;
                            }
                 }else{
                      return response()->json(['statuscode'=> 'TXR' , 'message'=> 'Please contact to administrator']);
                 }        
                //  $url = $this->api2->url."bankDetails?bankAccount=".$post->beneaccount."&ifsc=".$post->beneifsc;
                // $result = \Myhelper::curl($url, "GET", [], $header, "no", 'App\Models\Report', '0');
                // $response=json_decode($result['response']);

                
                // if(isset($response->subCode) && $response->subCode == 200 && isset($response->accountStatus) && $response->accountStatus=="VALID"){
                //     $holdername = strtolower(trim(preg_replace('!\s+!', ' ', $response->data->nameAtBank)));
                //     $str=$holdername;
                //     $prefix="mr ";
                //     $prefix1="ms ";
                //     if(substr($str, 0, strlen($prefix)) == $prefix || substr($str, 0, strlen($prefix))==$prefix1) {
                //         $str = substr($str, strlen($prefix));
                //     }
                //     if(strtolower($str) ==  strtolower($userdata->name)){
                //         $balance = User::where('id', $userdata->id)->first(['mainwallet']);
                //         $insert = [
                //             'api_id' => $this->api2->id,
                //             'provider_id' => $post->provider_id,
                //             'option1' => $post->name,
                //             'mobile' => $post->mobile,
                //             'number' => $post->beneaccount,
                //             'option2' => isset($post->data->nameAtBank) ? $post->data->nameAtBank : $post->benename,
                //             'option3' => $post->benebank,
                //             'option4' => $post->beneifsc,
                //             'txnid' => $post->txnid,
                //             'refno' => isset($post->data->utr) ? $post->data->utr : "none",
                //             'amount' => $post->amount,
                //             'charge' => $post->charge,
                //             'remark' => "Money Transfer",
                //             'status' => 'success',
                //             'user_id' => $userdata->id,
                //             'credit_by' => $userdata->id,
                //             'product' => 'dmt',
                //             'balance' => $balance->mainwallet,
                //             'description' => $post->benemobile
                //         ];
    
                //         User::where('id', $post->user_id)->decrement('mainwallet', $post->charge + $post->amount);
                //         $report = Report::create($insert);
                        
                            if($user->account == '' && $user->bank == '' && $user->ifsc == ''){
                              $addAccountUser = \DB::table('users')->where('id',$post->user_id)->update(['accountholdername'=>$post->accountholdername,'account' => $post->account, 'bank' => $post->bank, 'ifsc'=>$post->ifsc]);
                            }elseif($user->account2 == '' && $user->bank2 == '' && $user->ifsc2 == ''){
                               $addAccountUser = \DB::table('users')->where('id',$post->user_id)->update(['accountholdername2'=>$post->accountholdername,'account2' => $post->account, 'bank2' => $post->bank, 'ifsc2'=>$post->ifsc]);
                            }elseif($user->account3 == '' && $user->bank3 == '' && $user->ifsc3 == ''){
                               $addAccountUser = \DB::table('users')->where('id',$post->user_id)->update(['accountholdername3'=>$post->accountholdername,'account3' => $post->account, 'bank3' => $post->bank, 'ifsc3'=>$post->ifsc]);
                            }
                            
                           if($addAccountUser){
                               return response()->json(['statuscode'=>'TXN',  'message'=> "Account Updated Successfully"]);
                           }else{
                              return response()->json(['statuscode'=>'ERR',  'message'=> "Something Went Wrong"]);  
                           }
                          //  return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull','message'=> isset($response->data->account_name) ? $response->data->account_name : '']);
                          
                //         return response()->json(['statuscode'=> 'TXN','message'=> $response->data->nameAtBank]);
                //     }else{
                //         return response()->json(['statuscode'=> 'ERR', 'message'=> 'Account mismatched please use your own Account or contact administrator.']);
                //     }
                // }elseif(is_array($response) && isset($response->accountStatus) && $response->accountStatus == "INVALID" || $response->accountStatus="UNABLE_TO_VALIDATE"){
                //     return response()->json(['statuscode'=> 'ERR', 'message'=> $response->message]);
                // }else{
                //     return response()->json(['statuscode'=> 'ERR' , 'message'=> $response->message]);
                // }            
      


       }   
       
     public function resetTpin(Request $post){
         
       $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
            'tpin'    => 'required|numeric',
            'newtpin'  => 'required|numeric',
        );
        
      $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        } 
      $pin = \Myhelper::encrypt($post->tpin, "sdsada7657hgfh$$&7678");
      $pincheck =   Pindata::where('user_id', $post->user_id)->where('pin',$pin)->first() ;
      if($pincheck){
          $updatePin  =  Pindata::where('id',$pincheck->id)->update([ 'pin' => \Myhelper::encrypt($post->newtpin, "sdsada7657hgfh$$&7678")]) ;
          if($updatePin){
               return response()->json(['statuscode'=>'TXN',  'message'=> "Pin Updated Successfully"]);
          }else{
                return response()->json(['statuscode'=>'ERR',  'message'=> "Something Went Wrong"]);  
          }
      }else{
            return response()->json(['statuscode'=> 'ERR', 'message'=> 'Pin mismatched please use your correct pin or contact administrator.']);
      }
     }
     
     
     public function userpin(Request $post){
          $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
            'tpin'    => 'required|numeric',
        );
        
      $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        } 
       $pin = \Myhelper::encrypt($post->tpin, "sdsada7657hgfh$$&7678");
       $pincheck =   Pindata::where('user_id', $post->user_id)->where('pin',$pin)->first() ;
      if (!$pincheck) {
              return response()->json(['statuscode'=> 'ERR', 'message'=> " Pin is incorrect"]);
         }   
      return response()->json(['statuscode'=>'TXN',  'message'=> "Pin verified successfully"]);
        
     }
     
       public function servicelist(){
         $data['rechrge']  = ['Prepaid', 'DTH', 'Postpaid', 'Landline'];  
         $data['others'] =['Water', 'Loan Repaymen','FASTag','Loan','Cable TV','Health Insurance','Education Fees','Municipal Taxes','Hosing Society'];
         $data['bilpayment'] = ['Electricity','Gas Bill','Life Insurance','Broadband'];
         $i = 0 ;
         foreach($data as $key=>$service){ 
             foreach($service as $key1 => $value) {
               $datas[$key][$key1]['name'] = $value ;
             }
        }
         return response()->json(['status' => 'TXN','data'=> $datas , 'message' => 'Data Fetched Successfully']);
    }
    
      public function GetState(Request $req){
        //dd("rttrrtrtrt");
        $url= 'http://uat.dhansewa.com/Common/GetState';
       
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        
        $result= json_decode($response);
        
        //var_dump($result);
        return response()->json(['status' => 'success', 'message' => 'State Fached Successfully',"data"=>$result]);
  
}

    public function GetDistrictByState(Request $req){
        //dd("rttrrtrtrt");
        $url= 'http://uat.dhansewa.com/Common/GetDistrictByState';
        $header = array("Content-Type: application/json");
        $parameter["stateid"] = $req->stateid;
        $result = \Myhelper::curl($url, "POST", json_encode($parameter), $header, "no", 'App\Models\Report', '0');
        $res= $result['response'];
       // var_dump($res);
        $jsondata= json_decode($res);
        
        return response()->json(['status' => 'success', 'message' => 'District Fached Successfully',"data"=>$jsondata]);
  
} 

  public function fmicroatmInitiate(Request $post)
    {
        \DB::table('microlog')->insert(['response' => json_encode($post->all())]);
        $rules = array(
            'apptoken' => 'required',
            'user_id'  => 'required|numeric',
            'mobile'   => 'required',
            'remark'   => 'required',
            'imei'     => 'required',
            'transactionType' => 'required'
        );
        
        if($post->transactionType == "CW" || $post->transactionType == "M"){
            $rules['amount'] = 'sometimes|required|numeric|min:100';
        }

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        if (!\Myhelper::can('matm_service', $post->user_id)) {
            //return response()->json(['status' => "ERR", "message" => "Service Not Alloweds"]);
        }

        $user  = User::where('id', $post->user_id)->first();
        $agent = Fingagent::where('user_id', $post->user_id)->first();

        if (!$agent || $agent->status != "approved") {
           // return response()->json(['status' => "ERR", "message" => "User onboarding is pending"]);
        }

        $api = Api::where('code', 'fmicroatm')->first();
        
        $post['api_id'] = $api->id;
        if(!$api || $api->status == 0){
            return response()->json(['status' => "ERR", "message" => "Service Not Allowed"]);
        }

        do {
            $post['txnid'] = $this->transcode().$post->transactionType.rand(1111111111, 9999999999);
        } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport);

        $gpsdata = geoip($post->ip());
        $post['lat'] = sprintf('%0.4f', $gpsdata->lat);
        $post['lon'] = sprintf('%0.4f', $gpsdata->lon);
        if($post->transactionType == "CW" || $post->transactionType == "M"){
            $insert = [
                "mobile"  => $post->mobile,
                "api_id"  => $post->api_id,
                "txnid"   => $post->txnid,
                "amount"  => $post->amount,
                "user_id" => $post->user_id,
                "balance" => $user->aepsbalance,
                'aepstype'=> $post->transactionType,
                'status'  => 'initiated',
                'authcode'=> $post->imei,
                'payid'   => $post->payid,
                'mytxnid' => $post->lat,
                'terminalid' => $post->lon,
                'credited_by'=> $post->user_id,
                'type'       => 'credit',
                'product'    => 'matm'
            ];

            Microatmreport::create($insert);
        }

        $output['status']  = "TXN";
        $output['message'] = "Deatils Fetched Successfully";
        $output['data']    = [ 
            "merchantId"       => $agent->merchantLoginId , 
            "merchantPassword" => $agent->merchantLoginPin,
            "superMerchentId"  => $api->optional1,
            "txnid"            => $post->txnid,
            'lat'              => $post->lat,
            'lon'              => $post->lon
        ];

        return response()->json($output);
    }

 
    public function fmicroatmUpdate(Request $post)
    {
        \DB::table('microlog')->insert(['product'=>'microatm','response' => json_encode($post->all())]);
        $rules = array(
            'apptoken' => 'required',
            'user_id'  => 'required|numeric',
            'txn_id'   => 'required'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->first();
        if(!$user){
            $output['status'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        $response = json_decode($post->response, true);
        \DB::table('microlog')->insert(['response' => "res".json_encode($response)]);
        //dd($response);
        $rules = array(
            'Status'    => 'required'
        );

        $validator = \Validator::make((array)$response, array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(array(
                'status' => 'ERR',  
                'message' => $error
            ));
        }

        $report = Microatmreport::where('txnid', $post->txn_id)->where('user_id', $post->user_id)->first();

        if(!$report){
            $output['status'] = "ERR";
            $output['message'] = "Report Not Found";
            return response()->json($output);
        }

        if($report->status != 'initiated'){
            $output['status']  = "ERR";
            $output['message'] = "Permission Not Allowed";
            return response()->json($output);
        }

        $update['payid']   = isset($response['FpId'])?$response['FpId']:'';
        $update['refno']   = isset($response['BankRRN '])?$response['BankRRN ']:'';
        $update['option1'] = isset($response['TerminalId'])?$response['TerminalId']:'';
        $update['option2'] = isset($response['Trans Id'])?$response['Trans Id']:'';
        $update['aadhar']  = isset($response['CardNum'])?$response['CardNum']:'';
        $update['bank']    = isset($response['BankName'])?$response['BankName']:'';
        $update['option3'] = isset($response['CardType'])?$response['CardType']:'';
        
        \DB::table('microlog')->insert(['response' => "up".json_encode($update)]);
        if($report->status == "initiated" && $response['Status'] == "true"){
            $update['status'] = "success";
            if($report->amount > 0 && $report->amount <= 3000){
                $provider = Provider::where('recharge1', 'matm1')->first();
            }elseif($report->amount > 3000 && $report->amount <= 4000){
                $provider = Provider::where('recharge1', 'matm2')->first();
            }elseif($report->amount > 4000 && $report->amount <= 5000){
                $provider = Provider::where('recharge1', 'matm3')->first();
            }elseif($report->amount > 5000 && $report->amount <= 10000){
                $provider = Provider::where('recharge1', 'matm4')->first();
            }
            
            $post['provider_id']   = $provider->id;
            $update['provider_id'] = $provider->id;
            if($report->amount > 500){
                $update['charge'] = \Myhelper::getCommission($report->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
            }else{
                $update['charge'] = 0;
            }
            Microatmreport::where('id', $report->id)->update($update);
            $myreport = Microatmreport::where('id', $report->id)->first();
            $insert = [
                "mobile"  => $myreport->mobile,
                "aadhar"  => $myreport->aadhar,
                "api_id"  => $myreport->api_id,
                "provider_id"  => $myreport->provider_id,
                "txnid"   => $myreport->txnid,
                "refno"   => $myreport->refno,
                "amount"  => $myreport->amount,
                "charge"  => $myreport->charge,
                "bank"    => $myreport->bank,
                "user_id" => $myreport->user_id,
                "balance" => $myreport->user->aepsbalance,
                'aepstype'=> $myreport->aepstype,
                'status'  => 'success',
                'authcode'=> $myreport->authcode,
                'payid'   => $myreport->payid,
                'mytxnid' => $myreport->mytxnid,
                'terminalid' => $myreport->terminalid,
                'TxnMedium'  => $myreport->TxnMedium,
                'credited_by'=> $myreport->credited_by,
                'type'    => 'credit',
                'option1'=> $myreport->option1,
                'option2'=> $myreport->option2,
                'option3'=> $myreport->option3,
                'product'    => 'matm'
            ];

            $matm = Aepsreport::create($insert);
                
            
            if($matm){
                $credit = User::where('id', $user->id)->increment('aepsbalance', $report->amount + $update['charge']);
                \Myhelper::commission(Aepsreport::find($matm->id));
                $output['status']  = "TXN";
                $output['message'] = "Transaction Successfully";
                return response()->json($output);
            }else{
                $output['status']  = "ERR";
                $output['message'] = "Technical Error";
                return response()->json($output);
            }
        }elseif($report->status == "initiated" && $response['Status'] == "false"){
            $update['status'] = "failed";
            $update['refno']  = $response['Message'];
            \DB::table('microlog')->insert(['response' => "update".json_encode($update)]);
            Microatmreport::where('id', $report->id)->update($update);
            
            $output['status']  = "TXR";
            $output['message'] = "Transaction Failed";
            return response()->json($output);
        }

        return response()->json($output);
    }
    

}



