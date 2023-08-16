<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\User;
use App\Models\Report;
use App\Models\Mahaagent;
use Carbon\Carbon;
use App\Models\Api;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class LicBillpayController extends Controller
{
    protected $billapi;
    public function __construct()
    {
        $this->billapi = Api::where('code', 'sprintlicbillpay')->first();
    }
    
     public function getToken($uniqueid)
    {
        //dd($this->api);
        $payload =  [
            "timestamp" => time(),
            "partnerId" => $this->billapi->username,
            "reqid"     => $uniqueid
        ];
        
        $key = $this->billapi->password;
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
        
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }

  
    public function lictransaction(Request $post)
    {
        
        if(!$this->billapi || $this->billapi->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Lic Service Currently Down"]);
        }
        if(!in_array($post->type, ['validate', 'payment'])){
            return response()->json(['statuscode' => "ERR", "message" => "Type parameter request in invalid"]);
        }

        if($post->type == "validate"){
            $rules = array(
                'apptoken' => 'required',
                'user_id'  =>'required|numeric',
                'number'  =>'required',
                'mode'   => 'required' 
                
            );
        }else{
            $rules = array(
                'apptoken' => 'required',
                'user_id'  =>'required|numeric',
                'amount'      => 'required|numeric',
                'TransactionId'=> 'required',
                'number'  =>'required',
                'mode'   => 'required' 
            );
        }
        
        

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->first();
        if(!$user){
            $output['statuscode'] = "ERR";
            $output['message']    = "User details not matched";
            return response()->json($output);
        }

        if (!\Myhelper::can('licbillpay_service', $user->id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Service Not Allowed"]);
        }

        if($user->status != "active"){
          //  return response()->json(['statuscode' => "ERR", "message" => "Your account has been blocked."]);
        }


        switch ($post->type) {
            case 'validate':
                $validate = \Myhelper::FormValidator($rules, $post);
                if($validate != "no"){
                    return $validate;
                }
                $url = $this->billapi->url."fetchlicbill";

                $parameter = [
                    "canumber" => $post->number,
                    "ad1" => $user->email,
                    "mode"   => $post->mode,
                ];

                $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".$token['token'],
                    "Authorisedkey: ".$this->billapi->optional1
                );

                $query = json_encode($parameter);

                $result = \Myhelper::curl($url, "POST", $query, $header, "yes");
                //dd($result,$url,$query);
                \DB::table('rp_log')->insert([
                        'ServiceName' => "LIC_Bill_FETCH",
                        'header' => json_encode($header),
                        'body' => json_encode($parameter),
                        'response' => $result['response'],
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
        

                if(!$result['error'] && $result['response'] != ''){
                    $doc = json_decode($result['response']);
                    if(isset($doc->response_code) && in_array($doc->response_code, [1, 4, 8])){
                        return response()->json([
                            'statuscode' => "TXN",
                            'data'       => [
                                "customername" => $doc->name,
                                "duedate"      => $doc->duedate,
                                "dueamount"    => $doc->amount,
                                "TransactionId" => json_encode($doc->bill_fetch),
                                "ad2"           => $doc->ad2,
                                "ad3"           => $doc->ad3 
                            ]
                        ],200);
                    }else{
                        return response()->json(['statuscode' => "ERR", "message" => $doc->message]);
                    }
                }else{
                    return response()->json(['statuscode' => "ERR", "message" => $result['error']]);
                }
                break;

            case 'payment':
                
                if ($this->pinCheck($post) == "fail") {
                    //return response()->json(['statuscode' => "ERR", "message" => "Transaction Pin is incorrect"]);
                }
                
                if($user->mainwallet - $this->mainlocked() < $post->amount){
                    return response()->json(['statuscode' => "ERR", "message"=> 'Low Balance, Kindly recharge your wallet.']);
                }

                $previousrecharge = Report::where('number', $post->number)->where('amount', $post->amount)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
                if($previousrecharge > 0){
                   // return response()->json(['statuscode' => "ERR", "message"=> 'Same Transaction allowed after 2 min.'], 200);
                }
                $provider = Provider::where('type', 'lic')->first();

                $post['profit'] = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
                $post['tds']    = 0 ;
                $post['gst']    = 0 ;
                $debit = User::where('id', $user->id)->decrement('mainwallet',  $post->amount - $post->profit);
                if ($debit) {
                    do {
                        $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
                    } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

                    $insert = [
                        'number' => $post->number,
                        'mobile'  => isset($post->number1)?$post->number1:$user->mobile,
                        'provider_id' => 0,
                        'api_id' => 8,
                        'amount' => $post->amount,
                        'profit' => $post->profit,
                        'gst' => $post->gst,
                        'tds' => $post->tds,
                        'txnid' => $post->txnid,
                        'option1' => $post->customername,
                        'option2' => $post->duedate,
                        'status' => 'pending',
                        'user_id'    => $user->id,
                        'credit_by'  => $user->id,
                        'rtype'      => 'main',
                        'via'        => 'app',
                        'balance'    => $user->mainwallet,
                        'trans_type' => 'debit',
                        'product'    => 'licbillpay'
                    ];

                    $report = Report::create($insert);
                    
                    $url = $this->billapi->url."paylicbill";
                    $gpsdata = geoip($post->ip());
                    $parameter = [
                                "canumber" => $post->number,
                                "amount"   => $post->amount,
                                "ad1"      => $user->email,
                                "ad2"      => $post->ad2,
                                "ad3"      => $post->ad3,
                                "referenceid" => $post->txnid,
                                "latitude"    => $gpsdata->lat,
                                "longitude"   => $gpsdata->lon,
                                "mode"        => $post->mode,
                                "bill_fetch"  => json_decode($post->TransactionId)
                            ];

                    $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                    $header = array(
                                "Cache-Control: no-cache",
                                "Content-Type: application/json",
                                "Token: ".$token['token'],
                                "Authorisedkey: ".$this->billapi->optional1
                            );

                    $query  = json_encode($parameter);
                    

                            if (env('APP_ENV') == "server") {
                                $result = \Myhelper::curl($url, "POST", json_encode($parameter), $header, "yes", "App\Models\Report", $post->txnid);
                            }else{
                                $result = [
                                    'error'    => false,
                                    'response' => ''
                                ];
                            }
                            
                    

                    if($result['error'] || $result['response'] == ''){
                        $update['status'] = "pending";
                        $update['payid'] = "pending";
                        $update['description'] = "billpayment pending";
                    }else{
                        

                       \DB::table('rp_log')->insert([
                        'ServiceName' => "LIC_BillPay",
                        'header' => json_encode($header),
                        'body' => json_encode($parameter),
                        'response' => $result['response'],
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                
                    $doc = json_decode($result['response']);
                    
                    if(isset($doc->response_code) && in_array($doc->response_code, [1])){
                        $update['status'] = "success";
                        $update['refno'] = $doc->ackno;
                        $update['description'] = "LIC Bill Accepted";
                    }elseif(isset($doc->response_code) && in_array($doc->response_code, [5,14,16,6,17,8,18,12,9,13,14,15,16,17,18,10,11])){
                        $update['status'] = "failed";
                        $update['refno'] =  $doc->message;
                        $update['description'] =  $doc->message;
                        if($doc->message == "Insufficient fund in your account. Please topup your wallet before initiating transaction."){
                            $update['refno'] =  "Service down for sometime";
                            $update['description'] =  $doc->message;
                        }
                    }else{
                        $update['status'] = "pending";
                        $update['refno']  = "Please wait for status change or contact service provider";
                        $update['description'] =  $doc->message;
                    }
                                
                    }

                    if($update['status'] == "success" || $update['status'] == "pending"){
                        Report::where('id', $report->id)->update($update);
                        \Myhelper::commission($report);
                        $output['statuscode'] = "TXN";
                        $output['message'] = " LIC Billpayment Request Submitted";
                    }else{
                        User::where('id', $user->id)->increment('mainwallet', $post->debitAmount);
                        Report::where('id', $report->id)->update($update);
                        $output['statuscode'] = "TXF";
                        $output['message'] = $update['description'];    
                    }
                    $output['txnid']   = $post->txnid;
                    $output['rrn']     = $post->txnid;
                    return response()->json($output);
                }else{
                    return response()->json(['statuscode'=> "ERR" , "message" => 'Transaction Failed, please try again.']);
                }
                break;
        }
    }

    public function status(Request $post)
    {
        $rules = array(
            // 'apptoken' => 'required',
            'user_id'  => 'required|numeric',
            'txnid'    => 'required'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->first();
        if(!$user){
            $output['statuscode'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        if (!\Myhelper::can('billpayment_status', $user->id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Service Not Allowed"]);
        }

        $report = Report::where('id', $post->txnid)->first();
        if(!$report || !in_array($report->status , ['pending', 'success'])){
            return response()->json(['status' => "LIC Status Not Allowed1"], 200);
        }
       $url = $this->billapi->url."licstatus";
				$method = "POST";
				$parameter = json_encode(array(
        					'referenceid' => $report->txnid,
        				));
        				
                        $payload =  [
                            "timestamp" => time(),
                            "partnerId" => $this->billapi->username,
                            "reqid"     => $report->user_id.Carbon::now()->timestamp
                        ];
                        
                        $key = $this->billapi->password;
                        $signer = new HS256($key);
                        $generator = new JwtGenerator($signer);
                        $header = array(
                            "Cache-Control: no-cache",
                            "Content-Type: application/json",
                            "Token: ".$generator->generate($payload),
                            "Authorisedkey: ".$this->billapi->optional1
                            
                        );

        if (env('APP_ENV') != "local") {
                $result = \Myhelper::curl($url, $method, $parameter, $header);
                //dd($result,$url);
            }else{
                $result = [
                    'error' => false,
                    'response' => json_encode([
                        'statuscode' => 'TXN',
                        'message'=> 'local',
                        'data' => ['status'=> 'success', 'ref_no' => 'local']
                    ]) 
                ];
            }
        if($result['response'] != ''){
           \DB::table('rp_log')->insert([
                        'ServiceName' => "BillpayStatus",
                        'header' => json_encode($header),
                        'body' => json_encode($parameter),
                        'response' => $result['response'],
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    
					$doc = json_decode($result['response']);
					if(($doc->status) == true && $doc->data->status=="1"){
						$update['refno'] = (isset($doc->txnid))?$doc->txnid:"null";
						$update['status'] = "success";
						$update['description'] = (isset($doc->message))?$doc->message:"null";
						$output['statuscode'] = "TXN";
                        $output['txn_status'] = "success";
                        $output['message'] = $doc->message;
					}elseif(($doc->status) == true && $doc->data->status=="0"){
						$update['status'] = "reversed";
						$update['refno'] = (isset($doc->txnid))?$doc->txnid:"null";
						$update['description'] = (isset($doc->message))?$doc->message:"null";
						$output['statuscode'] = "ERR";
                        $output['txn_status'] = "success";
                        $output['message'] = $doc->message;
					}else{
						$update['status'] = "pending";
						$update['refno'] = (isset($doc->txnid))?$doc->txnid:"null";
						$update['description'] = (isset($doc->message))?$doc->message:"null";
						$output['statuscode'] = "ERR";
                        $output['txn_status'] = "success";
                        $output['message'] = $doc->message;
					} 
                     $product = "licbillpay";

            if ($update['status'] != "Unknown") {
                $reportupdate = Report::where('id', $report->id)->update($update);
                if ($reportupdate && $update['status'] == "reversed") {
                    \Myhelper::transactionRefund($post->id);
                }
            }
            return response()->json($output);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Something went wrong, contact your service provider"]);
        }
    }
}
