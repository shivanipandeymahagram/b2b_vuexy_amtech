<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\User;

class CronController extends Controller
{
    public function sessionClear()
  	{
	    $session = \DB::table('sessions')->where('last_activity' , '<', time()-900)->delete();
  	}

  	public function appClear()
  	{
	    \DB::table('securedatas')->where('last_activity' , '<', time() - 60)->delete();
  	}
  	
  	public function passwordClear()
  	{
	    \DB::table('password_resets')->where('last_activity' , '<', time()-180)->delete();
  	}

  	public function otpClear()
  	{
  		User::where('otpverify', '!=', 'yes')->update(['otpverify' => "yes", 'otpresend' => 0]);
  	}

  	public function recharge()
  	{
  		$reports = Report::where('product', 'recharge')->whereIn('status', ['pending', 'success'])->where('rtype', 'main')->whereIn('refno', ['', NULL])->take(50)->orderBy('id', 'DSEC')->get(['id', 'txnid', 'api_id']);

  		foreach ($reports as $report) {
  			switch ($report->api->code) {
					case 'recharge1':
						$url = $report->api->url.'/status?token='.$report->api->username.'&apitxnid='.$report->txnid;
						break;

					case 'recharge2':
						$url = $report->api->url.'rechargestatus.aspx?memberid='.$report->api->username."&pin=".$report->api->password.'&transid='.$report->txnid.'&format=json';
						break;
				}

	  		$result = \Myhelper::curl($url, "GET", "", []);

	  		if($result['response'] != ''){
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

					case 'recharge2':
						$doc = json_decode($result['response']);
						if(strtolower($doc->Status) == "success" || strtolower($doc->Status) == "pending"){
							$update['refno'] = $doc->OperatorRef;
							$update['status'] = "success";
						}elseif(strtolower($doc->Status) == "failed" || strtolower($doc->Status) == "failure" || strtolower($doc->Status) == "refund"){
							$update['status'] = "reversed";
							$update['refno'] = (isset($doc->ErrorMessage)) ? $doc->ErrorMessage : "failed";
						}else{
							$update['status'] = "Unknown";
							$update['refno'] = (isset($doc->ErrorMessage)) ? $doc->ErrorMessage : "Unknown";
						}
						break;
				}
			}
			if ($update['status'] != "Unknown") {
				$reportupdate = Report::where('id', $report->id)->update($update);
				if ($reportupdate && $update['status'] == "reversed") {
					\Myhelper::transactionRefund($report->id, "recharge");
				}
			}
  		}
  	}
}
