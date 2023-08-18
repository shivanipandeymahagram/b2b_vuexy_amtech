<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Circle;
use App\User;
use App\Models\Report;
use App\Models\Aepsreport;
use App\Models\Api;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function comingsoon()
    {
        return view('comingsoon');
    }
    public function index()
    {
        if (!\Myhelper::getParents(\Auth::id())) {
            session(['parentData' => \Myhelper::getParents(\Auth::id())]);
        }

        // if(\Auth::id() == "1080")
        // {
        //     dd(\Myhelper::getParents(\Auth::id())) ;
        // }

        $data['state'] = Circle::all();
        $roles = ['whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'other', 'employee'];

        foreach ($roles as $role) {
            if ($role == "other") {
                $data[$role] = User::whereHas('role', function ($q) {
                    $q->whereNotIn('slug', ['whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'admin', 'employee']);
                })->whereIn('kyc', ['verified'])->count();  //->whereIn('id', \Myhelper::getParents(\Auth::id()))
            } else {
                if (\Myhelper::hasRole('admin')) {
                    $data[$role] = User::whereHas('role', function ($q) use ($role) {
                        $q->where('slug', $role);
                    })->whereIn('kyc', ['verified'])->count();
                } else {
                    $data[$role] = User::whereHas('role', function ($q) use ($role) {
                        $q->where('slug', $role);
                    })->whereIn('id', \Myhelper::getParents(\Auth::id()))->whereIn('kyc', ['verified'])->count();
                }
            }
        }

        $product = [
            'recharge',
            'billpayment',
            'utipancard',
            'money',
            'aeps',
            'commission',
            'charge'
        ];

        $slot = ['today', 'month', 'lastmonth'];

        $statuscount = ['success' => ['success'], 'pending' => ['pending'], 'failed' => ['failed', 'reversed']];

        foreach ($product as $value) {
            foreach ($slot as $slots) {

                if ($value == "aeps") {
                    if (\Myhelper::hasRole('admin')) {
                        $query = Aepsreport::where('status', 'success');
                    } else {
                        $query = Aepsreport::whereIn('user_id', \Myhelper::getParents(\Auth::id()));
                    }
                } else {
                    $query = Report::whereIn('user_id', \Myhelper::getParents(\Auth::id()));
                }

                if ($value == "charge" || $value == "commission") {
                    $query2 = Aepsreport::whereIn('user_id', \Myhelper::getParents(\Auth::id()));
                }

                if ($slots == "today") {
                    $query->whereDate('created_at', date('Y-m-d'));
                }

                if ($slots == "month") {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                }

                if ($slots == "lastmonth") {
                    $query->whereMonth('created_at', date('m', strtotime("-1 months")))->whereYear('created_at', date('Y'));
                }

                switch ($value) {
                    case 'recharge':
                        $query->where('product', 'recharge');
                        break;

                    case 'billpayment':
                        $query->where('product', 'billpay');
                        break;

                    case 'utipancard':
                        $query->where('product', 'utipancard');
                        break;

                    case 'money':
                        $query->where('product', 'dmt');
                        break;
                    case 'commission':
                        $query2->where('aepstype', 'CW')->where('rtype', 'main');
                        break;
                    case 'charge':
                        $query2->where('aepstype', 'AP')->where('rtype', 'main');
                        break;
                    case 'aeps':
                        $query->where('rtype', 'main')->whereIn('aepstype', ['CW', 'M']);
                        break;
                }

                if ($value == "charge") {
                    $sum1 =  $query2->where('status', 'success')->sum('charge');
                    $sum2 =  $query->where('status', 'success')->sum('charge');
                    $data[$value][$slots] = $sum1 + $sum2;
                } else if ($value == "commission") {
                    $sum1 =   $query2->where('status', 'success')->sum('charge');
                    $sum2 = $query->where('status', 'success')->where('profit', ">", 0)->sum('profit');
                    $data[$value][$slots] = $sum1 + $sum2;
                } else {
                    if ($value == "aeps" && \Auth::id() == "1") {
                        // dd($query) ;
                    }
                    $data[$value][$slots] = $query->where('status', 'success')->sum('amount');
                }
            }

            if ($value == "aeps" && \Auth::id() == "1") {
                // dd($data) ;
            }

            foreach ($statuscount as $keys => $values) {
                if ($value == "aeps") {
                    $query = Aepsreport::whereIn('user_id', \Myhelper::getParents(\Auth::id()));
                } else {
                    $query = Report::whereIn('user_id', \Myhelper::getParents(\Auth::id()));
                }
                switch ($value) {
                    case 'recharge':
                        $query->where('product', 'recharge');
                        break;

                    case 'billpayment':
                        $query->where('product', 'billpay');
                        break;

                    case 'utipancard':
                        $query->where('product', 'utipancard');
                        break;

                    case 'money':
                        $query->where('product', 'dmt');
                        break;
                }
                $data[$value][$keys] = $query->whereIn('status', $values)->count();
            }
        }
        if (\Auth::id() == "1") {
            // dd($data);
        }

        return view('home')->with($data);
    }

    public function getbalance()
    {
        $data['apibalance'] = 0;
        $data['downlinebalance'] = round(User::whereIn('id', array_diff(\Myhelper::getParents(\Auth::id()), array(\Auth::id())))->sum('mainwallet'), 2);
        $data['mainwallet'] = \Auth::user()->mainwallet;
        $data['microatmbalance'] = \Auth::user()->microatmbalance;
        $data['lockedamount'] = \Auth::user()->lockedamount;
        if (\Myhelper::hasRole('admin') || \Myhelper::hasRole('employee')) {
            $data['aepsbalance'] = round(User::where('id', '!=',  \Auth::id())->sum('aepsbalance'), 2);
        } else {
            $data['aepsbalance'] = round(\Auth::user()->aepsbalance, 2);
        }

        return response()->json($data);
    }

    public function getmysendip()
    {
        $url = "http://login.securepayments.co.in/api/getip";
        $result = \Myhelper::curl($url, "GET", "", [], "no");
        dd($result);
    }

    public function setpermissions()
    {
        $users = User::whereHas('role', function ($q) {
            $q->where('slug', '!=', 'admin');
        })->get();

        foreach ($users as $user) {
            $inserts = [];
            $insert = [];
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id', $user->role_id)->get();

            if (sizeof($permissions) > 0) {
                \DB::table('user_permissions')->where('user_id', $user->id)->delete();
                foreach ($permissions as $permission) {
                    $insert = array('user_id' => $user->id, 'permission_id' => $permission->permission_id);
                    $inserts[] = $insert;
                }
                \DB::table('user_permissions')->insert($inserts);
            }
        }
    }

    public function setscheme()
    {
        // $users = User::whereHas('role', function($q){ $q->where('slug', '!=' ,'admin'); })->get();

        // foreach ($users as $user) {
        //     $inserts = [];
        //     $insert = [];
        //     $scheme = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $user->role_id)->first();
        //     if ($scheme) {
        //         User::where('id', $user->id)->update(['scheme_id' => $scheme->permission_id]);
        //     }
        // }

        $bcids = App\Models\Mahaagent::get(['phone1', 'id']);

        foreach ($bcids as $user) {
            $userdata = User::where('mobile', $user->phone1)->first(['id']);
            if ($userdata) {
                App\Models\Mahaagent::where('id', $user->id)->update(['user_id' => $userdata->id]);
            }
        }
    }

    public function mydata()
    {
        $data['fundrequest'] = \App\Models\Fundreport::where('credited_by', \Auth::id())->where('status', 'pending')->count();
        $data['aepsfundrequest'] = \App\Models\Aepsfundrequest::where('status', 'pending')->where('pay_type', 'manual')->count();
        $data['aepspayoutrequest'] = \App\Models\Aepsfundrequest::where('status', 'pending')->count();
        $data['member'] = \App\User::where('status', 'block')->where('kyc', 'pending')->count();
        return response()->json($data);
    }

    public function bulkSms()
    {
        $content = "Welcome to Webtalk, Username-9971702408,Password-12345678, Web: http://b2b.webtalkatmmini.com/, App: http://bit.ly/webtalkapplication Thanks-Webtalk Team";
        \Myhelper::sms("9971702308", $content);
        // $user = User::get(['id', 'mobile']);

        // foreach ($user as $value) {
        //     $content = "Welcome to Webtalk, Username-".$user->mobile.",Password-12345678, Web: http://b2b.webtalkatmmini.com/, App: http://bit.ly/webtalkapplication Thanks-Webtalk Team";
        //     \Myhelper::sms("9971702308", $content);        
        // }   
    }

    public function checkcommission(Request $post)
    {
        // $total = "6000";

        // $amount = $total;
        // for ($i=1; $i < 6; $i++) { 
        //     if(5000*($i-1) <= $amount  && $amount <= 5000*$i){
        //         if($amount == 5000*$i){
        //             $n = $i;
        //         }else{
        //             $n = $i-1;
        //             $x = $amount - $n*5000;
        //         }
        //         break;
        //     }
        // }

        // $amounts = array_fill(0,$n,5000);
        // if(isset($x)){
        //     array_push($amounts , $x);
        // }

        // //dd($amounts);

        // foreach($amounts as $value){
        //     echo $value."<br>";
        //     continue;
        //     echo "total - ".$total."<br>";
        //     $total = $total - $value;
        // }

        \Myhelper::commission($post);
    }




    function searchdatestatics(Request $post)
    {


        $session = \Myhelper::getParents(\Auth::id());
        $product = [
            // 'recharge',
            // 'billpayment',
            // 'utipancard',
            // 'money',
            // 'aeps',
            // 'matm',
            // 'nsdlpan',
            // 'insurance',
            // 'tax',
            // 'aepsadharpay',
            'commission',
            'charge'
        ];

        $slot = ['today', 'month', 'lastmonth'];

        $statuscount = ['success' => ['success'], 'pending' => ['pending'], 'failed' => ['failed', 'reversed']];

        foreach ($product as $value) {

            if ($value == "aeps" || $value == "aepsadharpay" || $value == "nsdlaeps") {
                $query = \DB::table('aepsreports');
            } elseif ($value == "matm") {
                $query = \DB::table('microatmreports');
            } elseif ($value == "upi") {
                $query = \DB::table('upireports');
            } else {
                $query = \DB::table('reports');
            }
            if ($value == "charge" || $value == "commission") {
                $query2 = Aepsreport::whereIn('user_id', \Myhelper::getParents(\Auth::id()));
            }
            if (\Myhelper::hasRole(['retailer', 'apiuser'])) {
                $query->where('user_id', \Auth::id());
            } elseif (\Myhelper::hasRole(['admin', 'distributor', 'whitelable', 'statepartner'])) {
                $query->whereIntegerInRaw('user_id', $session);
            }

            if ((isset($post->fromdate) && !empty($post->fromdate)) && (isset($post->todate) && !empty($post->todate))) {
                if ($post->fromdate == $post->todate) {
                    $query->whereDate('created_at', '=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));
                    $query2->whereDate('created_at', '=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));
                } else {
                    $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $post->todate)->addDay(1)->format('Y-m-d')]);
                    $query2->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $post->todate)->addDay(1)->format('Y-m-d')]);
                }
            }



            switch ($value) {
                case 'recharge':
                    $query->where('product', 'recharge');
                    break;

                case 'billpayment':
                    $query->where('product', 'billpay');
                    break;

                case 'utipancard':
                    $query->where('product', 'utipancard');
                    break;

                case 'money':
                    $query->where('product', 'dmt');
                    break;

                case 'insurance':
                    $query->where('product', 'insurance');
                    break;

                case 'aepsadharpay':
                    $query->where('transtype', 'transaction')->where('rtype', 'main')->where('aepstype', 'AP');
                    break;

                case 'nsdlaeps':
                    $query->where('transtype', 'transaction')->where('rtype', 'main')->where('api_id', '22');
                    break;

                case 'aeps':
                    $query->where('transtype', 'transaction')->where('rtype', 'main');
                    break;

                case 'matm':
                    $query->where('transtype', 'transaction')->where('rtype', 'main');
                    break;
                case 'commission':
                    $query2->where('aepstype', 'CW')->where('rtype', 'main');
                    break;
                case 'charge':
                    $query2->where('aepstype', 'AP')->where('rtype', 'main');
                    break;
            }

            if ($value == "charge") {
                $sum1 =  $query2->where('status', 'success')->sum('charge');
                $sum2 =  $query->where('status', 'success')->sum('charge');
                $data[$value] = round($sum1 + $sum2, 2);
            } else if ($value == "commission") {
                $sum1 =   $query2->where('status', 'success')->sum('charge');
                $sum2 = $query->where('status', 'success')->where('profit', ">", 0)->sum('profit');
                $data[$value] = round($sum1 + $sum2, 2);
            }
        }
        return response()->json($data);
    }
}
