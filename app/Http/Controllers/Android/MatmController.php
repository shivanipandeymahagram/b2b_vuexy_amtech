<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Api;
use App\Models\Companydata;
use App\Models\Provider;
use App\Models\Microatmreport;
use App\Models\Aepsreport;
use App\Models\Report;
use App\Models\Securedata;
use App\Models\Role;
use Carbon\Carbon;
use App\Models\Circle;
use Illuminate\Validation\Rule;

class MatmController extends Controller
{
 protected $api;
    public function __construct()
    {
        $this->api = Api::where('code', 'raeps')->first();
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
           // return response()->json(['status' => "ERR", "message" => "Service Not Allowed"]);
        }
        

        $user = User::where('id', $post->user_id)->first();
        if($user){

            $agent =  \DB::table('aepsusers')->where('user_id',$user->id)->first();

            if($agent){
                $api = Api::where('code', 'raeps')->first();

                if(!$api || $api->status == 0){
                    return response()->json(['status' => "ERR", "message" => "Service Not Allowed"]);
                }

                do {
                    $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                } while (Microatmreport::where("txnid", "=", $post->txnid)->first() instanceof Microatmreport);

                $insert = [
                    "mobile"   => $agent->merchantPhoneNumber,
                    "aadhar"   => $agent->merchantLoginId,
                    "txnid"    => $post->txnid,
                    "user_id"  => $user->id,
                    "balance"  => $user->aepsbalance,
                    'status'   => 'initiated',
                    'credited_by' => $user->id,
                    'type'        => 'credit',
                    'api_id'      => $api->id
                ];

                $matmreport = Microatmreport::create($insert);
                 $gpsdata = geoip($post->ip());
                if($matmreport){
                    $output['status'] = "TXN";
                    $output['message'] = "Deatils Fetched Successfully";
                    $output['data'] = [ 
                        "partnerId"   => $api->username , 
                        "apiKey"      => $api->password,
                        "merchantCode"=> $agent->merchantLoginId,
                        "UserId"      => $post->user_id,
                        "bcEmailId"   => $agent->merchantEmail,
                        "referenceNumber"  => $agent->merchantPhoneNumber,
                        "clientrefid" => $post->txnid,
                         "lon"      => $gpsdata->lon,
                         'lat'     => $gpsdata->lat,
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
        \DB::table('microlog')->insert(['product'=>"paysprint",'response' => json_encode($post->all())]);
        
        
        $output['status']  = "TXN";
        $output['data']  = $post->all();
        $output['message'] = "Transaction Successfully";
            
        return response()->json($output);
        
       $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
            'statuscode' =>'required',
            'amount' =>'required',
            'dataTxnId' =>'required'
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

        $response = json_decode($post->transactionData);

        // $rules = array(
        //     'user_id' => 'required',
        //     'apptoken'     => 'required',
        //     'statuscode'  => 'required',
        //     'amount'      => 'required',
        // );

        // $validator = \Validator::make((array)$response, array_reverse($rules));
        // if ($validator->fails()) {
        //     foreach ($validator->errors()->messages() as $key => $value) {
        //         $error = $value[0];
        //     }
        //     return response()->json(array(
        //         'status' => 'ERR',  
        //         'message' => $error
        //     ));
        // }

        $report = Microatmreport::where('txnid', $response->clientrefid)->where('user_id', $post->user_id)->first();

        if(!$report){
            $output['status'] = "ERR";
            $output['message'] = "Report Not Found";
            return response()->json($output);
        }
        //dd($response);
        

        $update['amount'] = ($response->txnamount != "") ? $response->txnamount : '0';
        $update['payid']  = (isset($response->refstan)) ? $response->refstan : '';
        $update['refno']  = $response->rrn;
        $update['remark'] = $response->bankremarks;
        $update['aadhar'] = $response->cardno;
        $update['mytxnid']= (isset($response->refstan)) ? $response->refstan : '';
        $update['balance']= $user->aepsbalance;
        if($response->statuscode === 'TXN'){
            $update['status'] = "success";
        }else{
            $update['status'] = "failed";
        }
        
        $updates = Microatmreport::where('id', $report->id)->update($update);
        
        if($updates && $update['amount'] > 0 && $response->statuscode === '00'){
            if($response->txnamount >= 100 && $response->txnamount <= 500){
                $provider = Provider::where('recharge1', 'matm1')->first();
            }elseif($response->txnamount > 500 && $response->txnamount <= 1000){
                $provider = Provider::where('recharge1', 'matm2')->first();
            }elseif($response->txnamount > 1000 && $response->txnamount <= 1500){
                $provider = Provider::where('recharge1', 'matm3')->first();
            }elseif($response->txnamount > 1500 && $response->txnamount <= 2000){
                $provider = Provider::where('recharge1', 'matm4')->first();
            }elseif($response->txnamount > 2000 && $response->txnamount <= 2500){
                $provider = Provider::where('recharge1', 'matm5')->first();
            }elseif($response->txnamount > 2500 && $response->txnamount <= 3000){
                $provider = Provider::where('recharge1', 'matm6')->first();
            }elseif($response->txnamount > 3000 && $response->txnamount <= 4000){
                $provider = Provider::where('recharge1', 'matm7')->first();
            }elseif($response->txnamount > 4000 && $response->txnamount <= 5000){
                $provider = Provider::where('recharge1', 'matm8')->first();
            }elseif($response->txnamount > 5000 && $response->txnamount <= 7000){
                $provider = Provider::where('recharge1', 'matm9')->first();
            }elseif($response->txnamount > 7000 && $response->txnamount <= 10000){
                $provider = Provider::where('recharge1', 'matm10')->first();
            }
            
            $post['provider_id'] = $provider->id;
            $update['provider_id'] = $provider->id;
            if($response->txnamount > 500){
                $update['charge'] = \Myhelper::getCommission($response->txnamount, $user->scheme_id, $post->provider_id, $user->role->slug);
            }else{
                $update['charge'] = 0;
            }

            $credit = User::where('id', $user->id)->increment('microatmbalance', $update['amount'] + $update['charge']);

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
}    