<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Utiid;
use App\Models\Report;
use App\Models\Aepsreport;
use App\Models\Microatmreport;
use App\Models\Aepsfundrequest;
use App\User;
use App\Models\Provider;
use App\Models\Api;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class TransactionController extends Controller
{
    public function transaction(Request $request)
    {
    	$rules = array(
            'apptoken' => 'required',
            'user_id'  => 'required|numeric',
            'type' 	   => 'required'
        );

        $validate = \Myhelper::FormValidator($rules, $request);
        if($validate != "no"){
        	return $validate;
        }

        if(!$request->has('start')){
        	$request['start'] = 0;
        }

    	switch ($request->type) {
    	   case 'distributor':
			case 'retailer':
				$request['table']= '\App\User';
				$request['searchdata'] = ['id','name', 'mobile','email'];
				$request['select'] = 'all';
				$request['order']  = ['id','DESC'];
				$request['parentData'] = \Myhelper::getParents($request->user_id);
				$request['whereIn'] = 'parent_id';
			break;
			
    		case 'aepsstatement':
				$request['table']= '\App\Models\Aepsreport';
				$request['searchdata'] = ['aadhar', 'mobile', 'txnid', 'payid', 'mytxnid', 'terminalid'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;

			case 'aepsfundrequest':
				$request['table']= '\App\Models\Aepsfundrequest';
				$request['searchdata'] = ['amount','type', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;
				
			case 'matmwalletstatement':
			    $request['table']= '\App\Models\Microatmreport';
				$request['searchdata'] = ['mobile','aadhar', 'txnid', 'refno', 'payid', 'amount','mytxnid','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;

			case 'fundrequest':
				$request['table']= '\App\Models\Fundreport';
				$request['searchdata'] = ['amount', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;

			case 'awalletstatement':
				$request['table']= '\App\Models\Aepsreport';
				$request['searchdata'] = ['mobile','aadhar', 'txnid', 'refno', 'payid', 'amount'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;
				
        	case 'cmsstatement':
			case 'rechargestatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;
				
			case 'licbillpaystatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno','option1', 'option2', 'mobile','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;

			case 'billpaystatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;

			case 'utipancardstatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;

			case 'dmtstatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;

			case 'matmstatement':
				$request['table']= '\App\Models\Microatmreport';
				$request['searchdata'] = ['aadhar', 'mobile', 'txnid', 'payid', 'mytxnid', 'terminalid','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;
			case 'accountstatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['txnid', 'user_id', 'credited_by', 'id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				
				break;		

			case 'matmfundrequest':
				$request['table']= '\App\Models\Microatmfundrequest';
				$request['searchdata'] = ['amount','type', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [$request->user_id];
				$request['whereIn'] = 'user_id';
				break;
    	}

    	$request['where']=0;
        
		try {
			$totalData = $this->getData($request, 'count');
			//dd($totalData);
		} catch (\Exception $e) {
			$totalData = 0;
		}
		$totalpage = floor($totalData/20);

		if ((isset($request->searchtext) && !empty($request->searchtext)) ||
           	(isset($request->todate) && !empty($request->todate))       ||
           	(isset($request->product) && !empty($request->product))       ||
           	(isset($request->status) && $request->status != '')		  ||
           	(isset($request->agent) && !empty($request->agent))
         ) 
	    {
	        $request['where'] = 1;
	    }
		
		try {
			$data = $this->getData($request, 'data');
			//dd($data);
		} catch (\Exception $e) {
			$data = [];
		}
		
		return response()->json(['statuscode' => "TXN", 'pages' => $totalpage,"data" => $data]);
    }

    public function getData($request, $returntype)
	{ 
		$table = $request->table;
		$data  = $table::query();
		$data->orderBy($request->order[0], $request->order[1]);

		if($request->parentData != 'all'){
			if(!is_array($request->whereIn)){
				$data->whereIn($request->whereIn, $request->parentData);
			}else{
				$data->where(function ($query) use($request){
					$query->where($request->whereIn[0] , $request->parentData)
					->orWhere($request->whereIn[1] , $request->parentData);
				});
			}
		}

        switch ($request->type) {
            case 'distributor':
			case 'retailer':
				$data->whereHas('role', function ($q) use($request){
					$q->where('slug', $request->type);
				})->where('kyc', 'verified');
			break;
			case 'licbillpaystatement':
				$data->where('product', 'licbillpay')->where('rtype', 'main');
			break;
			case 'aepsstatement':
				$data->where('rtype', 'main')->whereIN('aepstype', ['CW','AP','M']);
				break;

			case 'awalletstatement':
				$data->where('rtype', 'main')->whereNotIn('aepstype', ['BE']);
				break;

			case 'rechargestatement':
				$data->where('product', 'recharge');
				break;

			case 'billpaystatement':
				$data->where('product', 'billpay');
				break;

			case 'utipancardstatement':
				$data->where('product', 'utipancard');
				break;

			case 'dmtstatement':
				$data->where('product', 'dmt');
				break;
			case 'accountstatement':
				$data;
				//dd($test);
				break;	
			case 'cmsstatement':
				$data->where('product', 'cms');
				break;	
        }

		if ($request->where) {
	        if((isset($request->fromdate) && !empty($request->fromdate)) 
	        	&& (isset($request->todate) && !empty($request->todate))){
	            if($request->fromdate == $request->todate){
	                $data->whereDate('created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
	            }else{
	                $data->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $request->todate)->addDay(1)->format('Y-m-d')]);
	            }
	        }

	        if(isset($request->product) && !empty($request->product)){
	            switch ($request->type) {
					
				}
			}
			
	        if(isset($request->status) && $request->status != '' && $request->status != null){
	        	switch ($request->type) {	
					default:
	            		$data->where('status', $request->status);
					break;
				}
			}
			
			if(isset($request->agent) && !empty($request->agent)){
	        	switch ($request->type) {					
					default:
						$data->whereIn('user_id', $this->agentFilter($request));
					break;
				}
	        }

	        if(!empty($request->searchtext)){
	            $data->where( function($q) use($request){
	            	foreach ($request->searchdata as $value) {
	            		$q->orWhere($value, 'like',$request->searchtext.'%');
                  		$q->orWhere($value,'like','%'.$request->searchtext.'%');
                  		$q->orWhere($value, 'like','%'.$request->searchtext);
	            	}
				});
	        } 
      	}
		
		if($request->has('start')){
			$data->skip(($request->start - 1) * 20)->take(20);
		}

		if($returntype != "count"){
			if($request->select == "all"){
				return $data->get();
			}else{
				return $data->select($request->select)->get();
			}
		}else{
			return $data->count();
		}
	}
	
	public function transactionStatus(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  => 'required|numeric',
            'type' 	   => 'required'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
        	return $validate;
        }

        $apptoken = \App\Models\Securedata::where('apptoken', $post->apptoken)->where('user_id', $post->user_id)->first();
        if(!$apptoken){
            return response()->json(['statuscode'=>'UA', 'status'=>'UA', 'message' => "Unauthorize Access" ]);
        }
        
		if (!\Myhelper::can($post->type."_status", $post->user_id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Permission Not Allowed"]);
		}
		
		switch ($post->type) {
			case 'recharge':
			case 'billpayment':
			case 'utipancard':
			case 'money':
				$report = Report::where('id', $post->id)->first();
				break;

			case 'utiid':
				$report = Utiid::where('id', $post->id)->first();
				break;
				
			case 'aeps':
				$report = Aepsreport::where('id', $post->id)->first();
				break;
				
			case 'matm':
				$report = Microatmreport::where('id', $post->id)->first();
				break;


			default:
				return response()->json(['statuscode' => "ERR", "message" => "Status Not Allowed"], 400);
				break;
		}

		if(!$report || !in_array($report->status , ['pending', 'success', 'approved', 'initiated'])){
			return response()->json(['statuscode' => "ERR", "message" => "Status Not Allowed, Transaction is ".$report->status], 400);
		}

		if($post->type == "aeps" && (!$report || !in_array($report->status , ['pending']))){
			return response()->json(['statuscode' => "ERR", "message" => "Aeps Status Not Allowed"], 400);
		}
		
		if($post->type == "matm" && (!$report || !in_array($report->status , ['pending', 'initiated']))){
			return response()->json(['statuscode' => "ERR", "message" => "Matm Status Not Allowed"], 400);
		}

		switch ($post->type) {
			case 'recharge':
				switch ($report->api->code) {
					case 'recharge1':
						$url = $report->api->url.'/status?token='.$report->api->username.'&apitxnid='.$report->txnid;
						break;

					default:
						return response()->json(['statuscode' => "ERR", "message" => "Recharge Status Not Allowed"], 400);
						break;
				}
				
				$method = "GET";
				$parameter = "";
				$header = [];
				break;

			case 'billpayment':
				$url = $report->api->url.'/status?token='.$report->api->username.'&apitxnid='.$report->txnid;
				$method = "GET";
				$parameter = "";
				$header = [];
				break;

			case 'utipancard':
				$url = $report->api->url.'UATUTICouponRequestStatus';
				$method = "POST";
				$parameter['securityKey'] = $report->api->password;
                $parameter['createdby']   = $report->api->username;
                $parameter['requestid']   = $report->payid;
				$header = [];
				break;
			
			case 'utiid':
				$url = $report->api->url.'/status?token='.$report->api->username.'&vleid='.$report->vleid;
				$method = "GET";
				$parameter = "";
				$header = [];
				break;

			case 'money':
			case 'pdmt':
				$url = $report->api->url."transact/transact/querytransact";
        		$method = "POST";
        		$parameter = json_encode(array(
        			'referenceid' => $report->txnid,
        		));
                $payload =  [
                  "timestamp" => time(),
                    "partnerId" => $report->api->username,
                    "reqid"     => $report->user_id.Carbon::now()->timestamp
                ];
                 $key       = $report->api->password;
                 $signer    = new HS256($key);
                 $generator = new JwtGenerator($signer);
                 $header = array(
                  "Cache-Control: no-cache",
                  "Content-Type: application/json",
                  "Token: ".$generator->generate($payload),
                  "Authorisedkey: ".$report->api->optional1
                  );
                      
				break;
			
			case 'aeps':
				$url = $report->api->url."Common/CheckAePSTxnStatus";
				$method = "POST";
				$txnid = explode("|", $report->txnid);
				$parameter = json_encode(array(
					'Secretkey' => $report->api->password,
					'Saltkey' => $report->api->username,
					'stanno' => $txnid[0]
				));

				$header = array(
					"Accept: application/json",
					"Cache-Control: no-cache",
					"Content-Type: application/json"
				);
				break;
				
			case 'matm':
				$url = "http://uat.dhansewa.com/MICROATM/GetMATMtxnStatus";
				$method = "POST";
				$parameter = json_encode(array(
					'secretekey' => $report->api->password,
					'saltkey' => $report->api->username,
					'referenceid' => $report->txnid
				));

				$header = array(
					"Accept: application/json",
					"Cache-Control: no-cache",
					"Content-Type: application/json"
				);
				break;

			default:
				# code...
				break;
		}

		$result = \Myhelper::curl($url, $method, $parameter, $header);
		
		//dd($result);
		if($result['response'] != ''){
			switch ($post->type) {
				case 'recharge':
					switch ($report->api->code) {
						case 'recharge1':
							$doc = json_decode($result['response']);
							if($doc->statuscode == "TXN" && ($doc->trans_status =="success" || $doc->trans_status =="pending")){
								$update['refno'] = $doc->refno;
								$update['status'] = "success";
							}elseif($doc->statuscode == "TXN" && $doc->trans_status =="reversed"){
								$update['status'] = "reversed";
								$update['refno'] = $doc->refno;
							}else{
								$update['status'] = "Unknown";
								$update['refno'] = $doc->message;
							}
							break;
					}
					$product = "recharge";
					break;
               	case 'money':
					$doc = json_decode($result['response']);
				   	\DB::table('rp_log')->insert([
                                'ServiceName' => "Status",
                                'header' => json_encode($header),
                                'body' => json_encode($parameter),
                                'response' => $result['response'],
                                'url' => $url,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                            $doc = json_decode($result['response']);
			            if(isset($doc->response_code) && ($doc->response_code=="1")){
                            if(in_array($doc->txn_status, ["0", "5"])){
                                $update['status'] = "reversed";
        					    $update['refno'] = (isset($doc->message))? $doc->message : 'failed';
                                
                            }else{
                                $update['status'] = "success";
                                $update['payid'] = (isset($doc->ackno))? $doc->ackno : 'success';
            					$update['refno'] = (isset($doc->utr))? $doc->utr : 'success';
                                
                            }
                        }
                        elseif(isset($doc->response_code) && in_array($doc->response_code, ["0", "2", "5"])){
                             $update['status'] = "reversed";
        					 $update['refno'] = (isset($doc->message))? $doc->message : 'failed';
                        }
                        else{
            				   $update['status'] = "pending";
                			}
					$product = "money";
				case 'billpayment':
					$doc = json_decode($result['response']);
					if(isset($doc->statuscode)){
						if(($doc->statuscode == "TXN" && $doc->data->status =="success") || ($doc->statuscode == "TXN" && $doc->data->status =="pending")){
							$update['refno'] = $doc->data->ref_no;
							$update['status'] = "success";
						}elseif($doc->statuscode == "TXN" && $doc->data->status =="reversed"){
							$update['status'] = "reversed";
						}else{
							$update['status'] = "Unknown";
						}
					}else{
						$update['status'] = "Unknown";
					}
					$product = "billpay";
					break;

				case 'utipancard':
					$doc = json_decode($result['response']);
					if(isset($doc[0]->StatusCode) && $doc[0]->StatusCode == "000"){
						$update['status'] = "success";
					}else{
						$update['status'] = "Unknown";
					}
					$product = "utipancard";
					break;

				\DB::table('rp_log')->insert([
                                'ServiceName' => "Status",
                                'header' => json_encode($header),
                                'body' => json_encode($parameter),
                                'response' => $result['response'],
                                'url' => $url,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                            $doc = json_decode($result['response']);
			            if(isset($doc->response_code) && ($doc->response_code=="1")){
                            if(in_array($doc->txn_status, ["0", "5"])){
                                $update['status'] = "reversed";
        					    $update['refno'] = (isset($doc->message))? $doc->message : 'failed';
                                
                            }else{
                                $update['status'] = "success";
                                $update['payid'] = (isset($doc->ackno))? $doc->ackno : 'success';
            					$update['refno'] = (isset($doc->utr))? $doc->utr : 'success';
                                
                            }
                        }
                        elseif(isset($doc->response_code) && in_array($doc->response_code, ["0", "2", "5"])){
                             $update['status'] = "reversed";
        					 $update['refno'] = (isset($doc->message))? $doc->message : 'failed';
                        }
                        else{
            				   $update['status'] = "pending";
                			}
					$product = "money";
					break;

				case 'utiid':
					$doc = json_decode($result['response']);
					//dd($doc);
					if(isset($doc->statuscode) && $doc->statuscode == "TXN"){
						$update['status'] = "success";
						$update['remark'] = $doc->message;
					}elseif(isset($doc->statuscode) && $doc->statuscode == "TXF"){
						$update['status'] = "reversed";
						$update['remark'] = $doc->message;
					}elseif(isset($doc->statuscode) && $doc->statuscode == "TUP"){
						$update['status'] = "pending";
						$update['remark'] = $doc->message;
					}else{
						$update['status'] = "Unknown";
					}
					$product = "utiid";
					break;
					
				case 'aeps':
					$doc = json_decode($result['response']);
					//dd($doc);
					if(isset($doc->statuscode) && $doc->statuscode == "000"){
					    if(isset($doc->Data[0]) && isset($doc->Data[0]->status)){
					       if($doc->Data[0]->status == "SUCCESS"){
    						    $update['status'] = "complete";
    						    $update['refno'] = $doc->Data[0]->rrn;
    						    $update['remark'] = isset($doc->Data[0]->bankmessage) ? $doc->Data[0]->bankmessage : "Success";
					       }elseif($doc->Data[0]->status == "FAILURE"){
					            $update['status'] = "failed";
					            $update['refno'] = isset($doc->Data[0]->bankmessage) ? $doc->Data[0]->bankmessage : "Failed";
    						    $update['remark'] = isset($doc->Data[0]->bankmessage) ? $doc->Data[0]->bankmessage : "Failed";
					       }elseif($doc->Data[0]->status == "PENDING"){
					            $update['status'] = "pending";
    						    $update['remark'] = isset($doc->Data[0]->bankmessage) ? $doc->Data[0]->bankmessage : "pending";
					       }else{
    						    $update['status'] = "Unknown";
        				   }
					    }else{
    						$update['status'] = "Unknown";
    					}
					}else{
						$update['status'] = "Unknown";
					}
					$product = "aeps";
					break;

				case 'matm':
					$doc = json_decode($result['response']);
					//dd($doc);
					if(isset($doc->statuscode) && $doc->statuscode == "000"){
					    if(isset($doc->Data[0]) && isset($doc->Data[0]->status)){
					       if(strtolower($doc->Data[0]->status) == "success"){
    						    $update['status'] = "complete";
    						    $update['amount'] = $doc->Data[0]->amount;
    						    $update['refno']  = $doc->Data[0]->rrn;
    						    $update['aadhar'] = $doc->Data[0]->cardno;
    						    $update['payid']  = isset($doc->Data[0]->stanno) ? $doc->Data[0]->stanno : "Failed";
    						    $update['remark'] = isset($doc->Data[0]->bankmessage) ? $doc->Data[0]->bankmessage : "Success";
					       }elseif(strtolower($doc->Data[0]->status) == "failed"){
					            $update['status'] = "failed";
					            $update['amount'] = $doc->Data[0]->amount;
					            $update['refno']  = isset($doc->Data[0]->bankmessage) ? $doc->Data[0]->bankmessage : "Failed";
					            $update['payid']  = isset($doc->Data[0]->stanno) ? $doc->Data[0]->stanno : "Failed";
    						    $update['aadhar'] = $doc->Data[0]->cardno;
    						    $update['remark'] = isset($doc->Data[0]->bankmessage) ? $doc->Data[0]->bankmessage : "Failed";
					       }elseif(strtolower($doc->Data[0]->status) == "pending"){
					            $update['status'] = "pending";
					            $update['amount'] = $doc->Data[0]->amount;
					            $update['payid']  = isset($doc->Data[0]->stanno) ? $doc->Data[0]->stanno : "Failed";
					            $update['refno']  = isset($doc->Data[0]->rrn) ? $doc->Data[0]->rrn : "Failed";
    						    $update['remark'] = isset($doc->Data[0]->bankmessage) ? $doc->Data[0]->bankmessage : "pending";
					       }else{
    						    $update['status'] = "Unknown";
        				   }
					    }else{
    						$update['status'] = "Unknown";
    					}
					}elseif(isset($doc->statuscode) && $doc->statuscode == "002"){
					    $update['status'] = "failed";
					}else{
						$update['status'] = "Unknown";
					}
					$product = "matm";
					break;
			}

			if ($update['status'] != "Unknown") {
				switch ($post->type) {
					case 'recharge':
					case 'billpayment':
					case 'utipancard':
					case 'money':
						$reportupdate = Report::where('id', $post->id)->update($update);
						if ($reportupdate && $update['status'] == "reversed") {
							\Myhelper::transactionRefund($post->id);
						}

						if($post->type == "recharge"){
							$newreport = Report::where('id', $post->id)->first();
							if( ($report->refno != $newreport->refno) || ($report->status != $newreport->status) ){
								if($report->user->role->slug == "apiuser" && $report->user->callbackurl != null && $report->user->callbackurl != ""){
			                        \Myhelper::callback($report->id, 'recharge');
			                    }
							}
						}

						break;

                    case 'aeps':
						$reportupdate = Aepsreport::where('id', $post->id)->update($update);
						
						if($report->status == "pending" && $update['status'] == "complete"){
						    $user = User::where('id', $report->user_id)->first();
						    $insert = [
                                "mobile" => $report->mobile,
                                "aadhar" => $report->aadhar,
                                "api_id" => $report->api_id,
                                "txnid"  => $report->txnid,
                                "refno"  => "Txnid - ".$report->mytxnid. " Cleared",
                                "amount" => $report->amount,
                                "bank"   => $report->bank,
                                "user_id"=> $report->user_id,
                                "balance" => $user->aepsbalance,
                                'aepstype'=> $report->aepstype,
                                'status'  => 'success',
                                'authcode'=> $report->authcode,
                                'payid'=> $report->payid,
                                'mytxnid'=> $report->mytxnid,
                                'terminalid'=> $report->terminalid,
                                'TxnMedium'=> $report->TxnMedium,
                                'credited_by' => $report->credited_by,
                                'type' => 'credit'
                            ];
                            if($report->amount > 99 && $report->amount <= 499){
                            $provider = Provider::where('recharge1', 'aeps1')->first();
                            }elseif($report->amount>499 && $report->amount<=1000){
                                $provider = Provider::where('recharge1', 'aeps2')->first();
                            }elseif($report->amount>1000 && $report->amount<=1500){
                                $provider = Provider::where('recharge1', 'aeps3')->first();
                            }elseif($report->amount>1500 && $report->amount<=2000){
                                $provider = Provider::where('recharge1', 'aeps4')->first();
                            }elseif($report->amount>2000 && $report->amount<=2500){
                                $provider = Provider::where('recharge1', 'aeps5')->first();
                            }elseif($report->amount>2500 && $report->amount<=3000){
                                $provider = Provider::where('recharge1', 'aeps6')->first();
                            }elseif($report->amount>3000 && $report->amount<=4000){
                                $provider = Provider::where('recharge1', 'aeps7')->first();
                            }elseif($report->amount>4000 && $report->amount<=5000){
                                $provider = Provider::where('recharge1', 'aeps8')->first();
                            }elseif($report->amount>5000 && $report->amount<=7000){
                                $provider = Provider::where('recharge1', 'aeps9')->first();
                            }elseif($report->amount>7000 && $report->amount<=10000){
                                $provider = Provider::where('recharge1', 'aeps10')->first();
                            }
                            
                            $post['provider_id'] = $provider->id;
                            $post['service'] = $provider->type;
                
                            if($report->aepstype == "CW"){
                                if($report->amount > 500){
                                    $usercommission = \Myhelper::getCommission($report->amount, $user->scheme_id, $post->provider_id,$user->role->slug);
                                }else{
                                    $usercommission = 0;
                                }
                            }else{
                                $usercommission = 0;
                            }
                            
                            $insert['charge'] = $usercommission;
                            $action = User::where('id', $report->user_id)->increment('aepsbalance', $report->amount+$usercommission);
                            if($action){
                                $aeps = Aepsreport::create($insert);
                                if($report->amount > 500){
                                    \Myhelper::commission($aeps);
                                }
                            }
						}
						break;

					case 'matm':
						$reportupdate = Microatmreport::where('id', $post->id)->update($update);
						
						if(in_array($report->status, ["pending", 'initiated']) && $update['status'] == "complete"){
						    $user     = User::where('id', $report->user_id)->first();
						    $myreport = Microatmreport::where('id', $post->id)->first();

						    $insert = [
                                "mobile"  => $myreport->mobile,
                                "aadhar"  => $myreport->aadhar,
                                "api_id"  => $myreport->api_id,
                                "txnid"   => $myreport->txnid,
                                "refno"   => "Txnid - ".$myreport->id. " Cleared",
                                "amount"  => $myreport->amount,
                                "bank"    => $myreport->bank,
                                "user_id" => $myreport->user_id,
                                "balance" => $user->microatmbalance,
                                'aepstype'=> $myreport->aepstype,
                                'status'  => 'success',
                                'authcode'=> $myreport->authcode,
                                'payid'	  => $myreport->payid,
                                'mytxnid' => $myreport->mytxnid,
                                'terminalid' => $myreport->terminalid,
                                'TxnMedium'  => $myreport->TxnMedium,
                                'credited_by'=> $myreport->credited_by,
                                'type' 	  => 'credit'
                            ];

                            if($myreport->amount > 0){
	                           if($myreport->amount >= 100 && $myreport->amount <= 500){
	                                $provider = Provider::where('recharge1', 'matm1')->first();
	                            }elseif($myreport->amount > 500 && $myreport->amount <= 1000){
	                                $provider = Provider::where('recharge1', 'matm2')->first();
	                            }elseif($myreport->amount > 1000 && $myreport->amount <= 1500){
	                                $provider = Provider::where('recharge1', 'matm3')->first();
	                            }elseif($myreport->amount > 1500 && $myreport->amount <= 2000){
	                                $provider = Provider::where('recharge1', 'matm4')->first();
	                            }elseif($myreport->amount > 2000 && $myreport->amount <= 2500){
	                                $provider = Provider::where('recharge1', 'matm5')->first();
	                            }elseif($myreport->amount > 2500 && $myreport->amount <= 3000){
	                                $provider = Provider::where('recharge1', 'matm6')->first();
	                            }elseif($myreport->amount > 3000 && $myreport->amount <= 4000){
	                                $provider = Provider::where('recharge1', 'matm7')->first();
	                            }elseif($myreport->amount > 4000 && $myreport->amount <= 5000){
	                                $provider = Provider::where('recharge1', 'matm8')->first();
	                            }elseif($myreport->amount > 5000 && $myreport->amount <= 7000){
	                                $provider = Provider::where('recharge1', 'matm9')->first();
	                            }elseif($myreport->amount > 7000 && $myreport->amount <= 10000){
	                                $provider = Provider::where('recharge1', 'matm10')->first();
	                            }
	                            
	                            $insert['provider_id'] = $provider->id;
                                if($report->amount > 500){
                                    $insert['charge'] = \Myhelper::getCommission($myreport->amount, $user->scheme_id, $insert['provider_id'], $user->role->slug);
                                }else{
                                	$insert['charge'] = 0;
                                }
	                        }else{
	                        	$insert['provider_id'] = 0;
	                        	$insert['charge'] = 0;
	                        }
                            
                            $action = User::where('id',$report->user_id)->increment('aepsbalance',$myreport->amount + $insert['charge']);
                            if($action){
                                $matm = Microatmreport::create($insert);

                                if($report->amount > 500){
                                    \Myhelper::commission($matm);
                                }
                            }
						}
						break;
						
					case 'utiid':
						$reportupdate = Utiid::where('id', $post->id)->update($update);
						break;
				}
			}
			switch ($post->type) {
    			case 'recharge':
    			case 'billpayment':
    			case 'utipancard':
    			case 'money':
    				$report = Report::where('id', $post->id)->first();
    				
    				$output['statuscode'] = "TXN";
                    $output['txn_status'] = $report->status;
                    $output['refno'] = $report->refno;
                        
    				break;
    
    			case 'utiid':
    				$report = Utiid::where('id', $post->id)->first();
    				$output['statuscode'] = "TXN";
                    $output['txn_status'] = $report->status;
                    $output['refno'] = "Success";
    				break;
    				
    			case 'aeps':
    				$report = Aepsreport::where('id', $post->id)->first();
    				$output['statuscode'] = "TXN";
                    $output['txn_status'] = $report->status;
                    $output['refno'] = $report->refno;
    				break;
    				
    			case 'matm':
    				$report = Microatmreport::where('id', $post->id)->first();
    				$output['statuscode'] = "TXN";
                    $output['txn_status'] = $report->status;
                    $output['refno'] = $report->refno;
    				break;

    		}
    		return response()->json($output);
		}else{
			return response()->json(['status' => "Status Not Fetched , Try Again."], 400);
		}
	}
	
	
	
}
