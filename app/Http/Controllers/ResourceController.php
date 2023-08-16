<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scheme;
use App\Models\Company;
use App\Models\Provider;
use App\Models\Commission;
use App\Models\Companydata;
use App\Models\Packagecommission;
use App\Models\Package;
use App\User;

class ResourceController extends Controller
{
    public function index($type)
    {
        switch ($type) {
            case 'scheme':
                $permission = "scheme_manager";
                $data['mobileOperator'] = Provider::where('type', 'mobile')->where('status', "1")->get();
                $data['dthOperator'] = Provider::where('type', 'dth')->where('status', "1")->get();
                $data['cmsOperator'] = Provider::where('type', 'cms')->where('status', "1")->get();
                $data['matmOperator'] = Provider::where('type', 'matm')->where('status', "1")->get();
                $data['ebillOperator'] = Provider::where('type', 'electricity')->where('status', "1")->where('mode','online')->get();
                $data['ebillOperatorOffiline'] = Provider::where('type', 'electricity')->where('status', "1")->where('mode','offline')->get();
                $data['lpggasOperator'] = Provider::where('type', 'lpggas')->where('status', "1")->get();
                $data['waterOperator'] = Provider::where('type', 'water')->where('status', "1")->get();
                $data['loanrepayOperator'] = Provider::where('type', 'loanrepay')->where('status', "1")->get();
                $data['fasttagOperator'] = Provider::where('type', 'fasttag')->where('status', "1")->get();
                $data['cableOperator'] = Provider::where('type', 'cable')->where('status', "1")->get();
                $data['postpaidOperator'] = Provider::where('type', 'postpaid')->where('status', "1")->get();
                $data['pancardOperator'] = Provider::where('type', 'pancard')->where('status', "1")->get();
                $data['nsdlpanOperator'] = Provider::where('type', 'nsdlpan')->where('status', "1")->get();
                $data['dmtOperator'] = Provider::where('type', 'dmt')->where('status', "1")->get();
                $data['aepsOperator'] = Provider::where('type', 'aeps')->where('status', "1")->get();
                $data['aadharpayOperator']    = Provider::where('type', 'aadharpay')->where('status', "1")->get();
                $data['bbpsOperator']    = Provider::where('type', 'bbpsofflineslab')->where('status', "1")->get();
                $data['licOperator']    = Provider::where('type', 'licslab')->where('status', "1")->get();
                break;

            case 'package':
                if($this->schememanager() != "all"){
                    abort(403);
                }
                $data['mobileOperator'] = Provider::where('type', 'mobile')->where('status', "1")->get();
                $data['dthOperator'] = Provider::where('type', 'dth')->where('status', "1")->get();
                $data['ebillOperator'] = Provider::where('type', 'electricity')->where('status', "1")->get();
                $data['lpggasOperator'] = Provider::where('type', 'lpggas')->where('status', "1")->get();
                $data['waterOperator'] = Provider::where('type', 'water')->where('status', "1")->get();
                $data['pancardOperator'] = Provider::where('type', 'pancard')->where('status', "1")->get();
                $data['nsdlpanOperator'] = Provider::where('type', 'nsdlpan')->where('status', "1")->get();
                $data['dmtOperator'] = Provider::where('type', 'dmt')->where('status', "1")->get();
                $data['aepsOperator'] = Provider::where('type', 'aeps')->where('status', "1")->get();
                break;

            case 'company':
                $permission = "company_manager";
                break;

            case 'companyprofile':
                $permission = "change_company_profile";
                $data['company'] = Company::where('id', \Auth::user()->company_id)->first();
                $data['companydata'] = Companydata::where('company_id', \Auth::user()->company_id)->first();
                break;
            
            case 'commission':
                $permission = "view_commission";
                $product = ['mobile', 'dth', 'electricity', 'pancard', 'dmt', 'aeps','lpggas','postpaid','water','loanrepay','fasttag','cable'];

                if($this->schememanager() != "all"){
                    foreach ($product as $key) {
                        $data['commission'][$key] = Commission::where('scheme_id', \Auth::user()->scheme_id)->whereHas('provider', function ($q) use($key){
                            $q->where('type' , $key)->where('status','1');
                        })->get();
                    }
                }else{
                    foreach ($product as $key) {
                        $data['commission'][$key] = Packagecommission::where('scheme_id', \Auth::user()->scheme_id)->whereHas('provider', function ($q) use($key){
                            $q->where('type' , $key)->where('status','1');
                        })->get();
                    }
                }
                
                break;
            
            default:
                # code...
                break;
        }

        if ($type != "package" && !\Myhelper::can($permission)) {
            abort(403);
        }
        $data['type'] = $type;
// dd($data);
        return view("theme_1.resource.".$type)->with($data);
        
    }

    public function update(Request $post)
    {
        switch ($post->actiontype) {
            case 'scheme':
            case 'commission':
                $permission = "scheme_manager";
                break;
            
            case 'company':
                $permission = ["company_manager", "change_company_profile"];
                break;

            case 'companydata':
                $permission = "change_company_profile";
                break;
        }

        if (isset($permission) && !\Myhelper::can($permission)) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }

        switch ($post->actiontype) {
            case 'scheme':
                $rules = array(
                    'name'    => 'sometimes|required|unique:schemes,name' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $post['user_id'] = \Auth::id();
                $action = Scheme::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'package':
                $rules = array(
                    'name'    => 'sometimes|required|unique:packages,name' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $post['user_id'] = \Auth::id();
                $action = Package::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'company':
                $rules = array(
                    'companyname'    => 'sometimes|required'
                );

                if($post->file('logos')){
                    $rules['logos'] = 'sometimes|required|mimes:jpg,JPG,jpeg,png|max:500';
                }
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                if($post->id != 'new'){
                    $company = Company::find($post->id);
                }
                
                if($post->hasFile('logos')){
                    try {
                        unlink(public_path('logos/').$company->logo);
                    } catch (\Exception $e) {
                    }
                    $filename ='logo'.$post->id.".".$post->file('logos')->guessExtension();
                    $post->file('logos')->move(public_path('logos/'), $filename);
                    $post['logo'] = $filename;
                }

                $action = Company::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'companydata':
                $rules = array(
                    'company_id'    => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Companydata::updateOrCreate(['company_id'=> $post->company_id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            
            case 'commission':
                $rules = array(
                    'scheme_id'    => 'sometimes|required|numeric' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                foreach ($post->slab as $key => $value) {
                    $update[$value] = Commission::updateOrCreate([
                        'scheme_id' => $post->scheme_id,
                        'slab'      => $post->slab[$key]
                    ],[
                        'scheme_id' => $post->scheme_id,
                        'slab'      => $post->slab[$key],
                        'type'      => $post->type[$key],
                        'whitelable'=> $post->whitelable[$key],
                        'md'        => $post->md[$key],
                        'distributor'  => $post->distributor[$key],
                        'retailer'     => $post->retailer[$key],
                    ]);
                }
                return response()->json(['status'=>$update], 200);
                break;

            case 'packagecommission':
                $rules = array(
                    'scheme_id'    => 'sometimes|required|numeric' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                foreach ($post->slab as $key => $value) {
                    $data     = Packagecommission::where('scheme_id',\Auth::user()->scheme_id)->where('slab', $value)->first();
                    $provider = Provider::where('id', $value)->first();
                    $pass = true;

                    if(\Myhelper::hasNotRole('admin') && $data){
                        if($data->provider->type == "dmt"){
                            if($post->type[$key] == "flat" && $post->value[$key] > 50 ){
                                $pass = false;
                                $update[$post->slab[$key]] = "value shouldn't be greater than 50";
                            }

                            if($post->type[$key] == "percent" && $post->value[$key] > 1 ){
                                $pass = false;
                                $update[$post->slab[$key]] = "value shouldn't be greater than 1";
                            }
                        }
                    }

                    if($post->value[$key] < 0 ){
                        $pass = false;
                        $update[$post->slab[$key]] = "value should be greater than 0";
                    }

                    if(\Myhelper::hasNotRole('admin') && !$data){
                        $pass = false;
                        $update[$post->slab[$key]] = "Your commission not set by parent";
                    }

                    if(\Myhelper::hasNotRole('admin') && $data){
                        if(
                            $provider->type == "mobile" || 
                            $provider->type == "electricity"|| 
                            $provider->type == "dth"  || 
                            $provider->type == "pancard" || 
                            $provider->type == "aeps"
                        ){
                            if($data->value < $post->value[$key]){
                                $pass = false;
                                $update[$post->slab[$key]] = "value shouldn't be greater than ".$data->value;
                            }
                        }

                        if(($provider->type == "dmt" && $provider->recharge1 != "dmt1accverify") || $provider->type == "nsdlpan"){
                            if($data->value > $post->value[$key]){
                                $pass = false;
                                $update[$post->slab[$key]] = "value shouldn't be less than ".$data->value;
                            }
                        }
                    }

                    if(\Myhelper::hasNotRole('admin') && $data){
                        $slabtype = $data->type;
                    }else{
                        $slabtype = $post->type[$key];
                    }
                    if($pass){
                        $update[$value] = Packagecommission::updateOrCreate(
                            [
                                'scheme_id' => $post->scheme_id,
                                'slab'      => $post->slab[$key],
                            ],
                            [
                                'scheme_id' => $post->scheme_id,
                                'slab'      => $post->slab[$key],
                                'type'      => $slabtype,
                                'value'     => $post->value[$key]
                            ]
                        );
                    }
                }
                return response()->json(['status'=>$update], 200);
                break;
            
            default:
                # code...
                break;
        }
    }

    public function getCommission(Request $post , $type)
    {
        return Commission::where('scheme_id', $post->scheme_id)->get()->toJson();
    }

    public function getPackageCommission(Request $post , $type)
    {
        return Packagecommission::where('scheme_id', $post->scheme_id)->get()->toJson();
    }

    public function mycommission(Type $var = null)
    {
        # code...
    }
    
    public function getRetailer(Request $request){
          if(!isset($request->search)){
         $userResponse = User::whereIn('role_id',[4,3])->take(50)->get();
      }else{
     $search = $request->search;
     $userResponse = User::whereIn('role_id',[2,3,4])->where('mobile', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%')->take(10)->get();
      }
        $response = [];
        foreach($userResponse as $key => $value){
            $response[$key+1]['id'] = $value->id;
            $response[$key+1]['text'] = $value->name.' ('.$value->mobile.')';
        }
        return json_encode($response);
      
    }
}
