<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Circle;
use App\Models\Scheme;
use App\Models\Company;
use App\Models\Provider;
use App\Models\Utiid;
use App\Models\Permission;
use App\User;
use App\Models\Commission;
use App\Models\Packagecommission;
use App\Models\Package;

class MemberController extends Controller
{
    public function index($type , $action="view")
    {
        if($action != 'view' && $action != 'create'){
            abort(404);
        }

        $data['role'] = Role::where('slug', $type)->first();
        $data['roles'] = [];
        if(!$data['role'] && !in_array($type, ['other', 'kycpending', 'kycsubmitted', 'kycrejected','web'])){
            abort(404);
        }
        
        if($action == "view" && !\Myhelper::can('view_'.$type) ){
            abort(401);
        }elseif($action == "create"  && !\Myhelper::can('create_'.$type) && !in_array($type, ['kycpending', 'kycsubmitted', 'kycrejected'])){
            abort(401);
        }

        if(!$data['role']){
            $roles = Role::whereIn('slug', ["whitelable", "md", 'distributor', 'retailer', 'apiuser'])->get();

            foreach ($roles as $role) {
                if(\Myhelper::can('create_'.$type)){
                    $data['roles'][] = $role;
                }
            }

            $roless = Role::whereNotIn('slug', ['admin', "whitelable", "md", 'distributor', 'retailer', 'apiuser'])->get();

            foreach ($roless as $role) {
                if(\Myhelper::can('create_other')){
                    $data['roles'][] = $role;
                }
            }
        }
        
        if ($action == "create" && (!$data['role'] && sizeOf($data['roles']) == 0)){
            abort(404);
        }
        
        $data['type'] = $type;
        $data['state'] = Circle::all();
        if($this->schememanager() != "all"){
            $data['scheme'] = Scheme::where('user_id', \Auth::id())->get();
        }else{
            $data['scheme'] = Package::where('user_id', \Auth::id())->get();
        }

        $types = array(
            'Resource' => 'resource',
            'Setup Tools' => 'setup',
            'Member'   => 'member',
            'Member Setting'   => 'memberaction',
            'Member Report'    => 'memberreport',

            'Wallet Fund'   => 'fund',
            'Wallet Fund Report'   => 'fundreport',

            'Aeps Fund'   => 'aepsfund',
            'Aeps Fund Report'   => 'aepsfundreport',

            'Agents List'   => 'idreport',

            'Portal Services'   => 'service',
            'Transactions'   => 'report',

            'Transactions Editing'   => 'reportedit',
            'Transactions Status'   => 'reportstatus',

            'User Setting' => 'setting'
        );
        foreach ($types as $key => $value) {
            $data['permissions'][$key] = Permission::where('type', $value)->orderBy('id', 'ASC')->get();
        }

        if($action == "view"){
            return view('member.index')->with($data);
        }else{
            return view('member.create')->with($data);
        }
    }

    public function create(\App\Http\Requests\Member $post)
    {
        $role = Role::where('id', $post->role_id)->first();

        if(!in_array($role->slug, ['admin', "whitelable", "md", 'distributor', 'retailer', 'apiuser','employee'])){
            if(!\Myhelper::can('create_other')){
                return response()->json(['status' => "Permission not allowed"],200);
            }
        }
        
        if(!\Myhelper::can('create_'.$role->slug)){
            return response()->json(['status' => "Permission not allowed"],200);
        }

        if(\Myhelper::hasNotRole('admin')){
            $parent = User::where('id', \Auth::id())->first(['id', 'rstock', 'dstock', 'mstock']);
            if($role->slug == "md"){
                if($parent->mstock < 1){
                    return response()->json(['status'=>'Low id stock'], 200);
                }
            }

            if($role->slug == "distributor"){
                if($parent->dstock < 1){
                    return response()->json(['status'=>'Low id stock'], 200);
                }
            }

            if($role->slug == "retailer"){
                if($parent->rstock < 1){
                    return response()->json(['status'=>'Low id stock'], 200);
                }
            }
        }

        if($this->schememanager() != "all"){
            if(!$post->has('scheme_id')){
                $post['scheme_id'] = \Auth::user()->scheme_id;
            }
        }

        $post['id'] = "new";
        $post['parent_id'] = \Auth::id();
        $post['kyc'] = "pending";
        $post['passwordold'] = $post->mobile;
        $post['password'] = bcrypt($post->mobile);
        $post['agentcode']  = date('ymdhis') ;
        if($role->slug == "whitelable"){
            $company = Company::create($post->all());
            $post['company_id'] = $company->id;
        }else{
            $post['company_id'] = \Auth::user()->company_id;
        }

        if($post->hasFile('aadharcardpics')){
            $filename ='addhar'.\Auth::id().date('ymdhis').".".$post->file('aadharcardpics')->guessExtension();
            $post->file('aadharcardpics')->move(public_path('kyc/'), $filename);
            $post['aadharcardpic'] = $filename;
        }

        if($post->hasFile('pancardpics')){
            $filename ='pan'.\Auth::id().date('ymdhis').".".$post->file('pancardpics')->guessExtension();
            $post->file('pancardpics')->move(public_path('kyc/'), $filename);
            $post['pancardpic'] = $filename;
        }
        if($post->hasFile('signatures')){
            $filename ='signature'.\Auth::id().date('ymdhis').".".$post->file('signatures')->guessExtension();
            $post->file('signatures')->move(public_path('kyc/'), $filename);
            $post['signature'] = $filename;
        }
        
        if($post->hasFile('livepics')){
            $filename ='livepics'.\Auth::id().date('ymdhis').".".$post->file('livepics')->guessExtension();
            $post->file('livepics')->move(public_path('kyc/'), $filename);
            $post['livepic'] = $filename;
        }
        
        if($post->hasFile('profiles')){
            $filename ='profiles'.\Auth::id().date('ymdhis').".".$post->file('profiles')->guessExtension();
            $post->file('profiles')->move(public_path('kyc/'), $filename);
            $post['profile'] = $filename;
        }
        
        if($post->hasFile('agntpics')){
            $filename ='agntpics'.\Auth::id().date('ymdhis').".".$post->file('agntpics')->guessExtension();
            $post->file('agntpics')->move(public_path('kyc/'), $filename);
            $post['agntpics'] = $filename;
        }
        
        if($post->hasFile('shop_photos')){
            $filename ='shop_photos'.\Auth::id().date('ymdhis').".".$post->file('shop_photos')->guessExtension();
            $post->file('shop_photos')->move(public_path('kyc/'), $filename);
            $post['shop_photo'] = $filename;
        }
        
        if (!$post->has('scheme_id')) {
            $scheme = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $post->role_id)->first();
            if($scheme){
                $post['scheme_id'] = $scheme->permission_id;
            }
        }

        $response = User::updateOrCreate(['id'=> $post->id], $post->all());
    	if($response){
            // $responses = session('parentData');
            // array_push($responses, $response->id);
            // session(['parentData' => $responses]);
            
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id', $post->role_id)->get();
            if(sizeof($permissions) > 0){
                foreach ($permissions as $permission) {
                    $insert = array('user_id'=> $response->id , 'permission_id'=> $permission->permission_id);
                    $inserts[] = $insert;
                }
                \DB::table('user_permissions')->insert($inserts);
            }
            // try {
            //     $this->utiidcreation($response);
            // } catch (\Exception $e) {}

            if(\Myhelper::hasNotRole(['admin'])){
                if($role->slug == "md"){
                    User::where('id', \Auth::user()->id)->decrement('mstock', 1);
                }

                if($role->slug == "distributor"){
                    User::where('id', \Auth::user()->id)->decrement('dstock', 1);
                }
    
                if($role->slug == "retailer"){
                    User::where('id', \Auth::user()->id)->decrement('rstock', 1);
                }
            }

            $regards="";
            $content = "Dear Partner, your login details are mobile - ".$post->mobile." & password - ".$post->mobile." Don't share with anyone Regards ".$regards." LCO FINTECH(OPC) PRIVATE LIMITED";
             	
            \Myhelper::sms($post->mobile, $content);

    		return response()->json(['status'=>'success'], 200);
    	}else{
    		return response()->json(['status'=>'fail'], 400);
    	}
    }

    public function utiidcreation($user)
    {
        $provider = Provider::where('recharge1', 'utipancard')->first();

        if($provider && $provider->status != 0 && $provider->api && $provider->api->status != 0){
            $parameter['token'] = $provider->api->username;
            $parameter['vle_id'] = $user->mobile;
            $parameter['vle_name'] = $user->name;
            $parameter['location'] = $user->city;
            $parameter['contact_person'] = $user->name;
            $parameter['pincode'] = $user->pincode;
            $parameter['state'] = $user->state;
            $parameter['email'] = $user->email;
            $parameter['mobile'] = $user->mobile;
            $url = $provider->api->url."/create";
            $result = \Myhelper::curl($url, "POST", json_encode($parameter), ["Content-Type: application/json", "Accept: application/json"], "no");

            if(!$result['error'] || $result['response'] != ''){
                $doc = json_decode($result['response']);
                if($doc->statuscode == "TXN"){
                    $parameter['user_id'] = $user->email;
                    $parameter['type'] = "new";
                    Utiid::create($post->all());
                }
            }
        }
    }

    public function getCommission(Request $post)
    {
        $product = ['mobile', 'dth', 'electricity', 'pancard', 'dmt', 'aeps','lpggas','postpaid','water','loanrepay','fasttag','cable'];
        foreach ($product as $key) {
            $data['commission'][$key] = Commission::where('scheme_id', $post->scheme_id)->whereHas('provider', function ($q) use($key){
                $q->where('type' , $key);
            })->get();
        }
       // dd($data);
        return response()->json(view('member.commission')->with($data)->render());
    }

    public function getPackageCommission(Request $post)
    {
        $product = ['mobile', 'dth', 'electricity', 'pancard', 'dmt', 'aeps', 'nsdlpan','lpggas','postpaid','water','loanrepay','fasttag','cable'];
        foreach ($product as $key) {
            $data['commission'][$key] = Packagecommission::where('scheme_id', $post->scheme_id)->whereHas('provider', function ($q) use($key){
                $q->where('type' , $key);
            })->get();
        }
       // dd($data);
        return response()->json(view('member.packagecommission')->with($data)->render());
    }
}
