<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utiid;
use Carbon\Carbon;
use App\Models\Report;
use App\Models\Aepsreport;
use App\Models\Aepsfundrequest;
use App\Models\Microatmreport;
use App\User;
use App\Models\Provider;
use App\Models\Mahaagent;
use App\Models\PortalSetting;
use App\Models\Api;
use App\Models\Adminprofit;
use App\Models\Aepsuser;
use Illuminate\Support\Facades\Auth;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class CommonController extends Controller
{
    public function fetchData(Request $request, $type, $id=0, $returntype="all")
	{
		$request['return'] = 'all';
		$request['returntype'] = $returntype;
		$parentData = \Myhelper::getParents($id);
		switch ($type) {
			case 'permissions':
				$request['table']= '\App\Models\Permission';
				$request['searchdata'] = ['name', 'slug'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = 'all';
			break;

			case 'roles':
				$request['table']= '\App\Models\Role';
				$request['searchdata'] = ['name', 'slug'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = 'all';
			break;
			
			case 'setupadminprofit':
				$request['table']= '\App\Models\Adminprofit';
				$request['searchdata'] = ['type', 'api_id', 'provider_id', 'commissiontype', 'commission'];
				$request['select'] = 'all';
				$request['order'] = ['id','asc'];
				$request['parentData'] = 'all';
			break;
			
			case 'sprintpayoutusers':
			$request['table']= '\App\Models\Sprintpayoutuser';
			$request['searchdata'] = ['account','name', 'user_id'];
			$request['select'] = 'all';
			$request['order'] = ['id','desc'];
	     	if (\Myhelper::hasRole(['admin', 'subadmin'])){
		     	$request['parentData'] = 'all';
	     	}else{
	     	 	$request['parentData'] = [\Auth::id()];   
	     	}
			$request['whereIn'] = 'user_id';
			break;

			case 'whitelable':
			case 'md':
			case 'distributor':
			case 'retailer':
			case 'apiuser':
			case 'other':
	     	case 'employee':    
			case 'tr' :
			case 'kycpending':
			case 'kycsubmitted':
			case 'kycrejected':  
				$request['table']= '\App\User';
				$request['searchdata'] = ['id','name', 'mobile','aadharcard','pancard'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if (\Myhelper::hasRole(['retailer', 'apiuser'])){
					$request['parentData'] = [\Auth::id()];
				}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
					$request['parentData'] = $parentData;
				}else{
					$request['parentData'] = 'all';
				}
				$request['whereIn'] = 'parent_id';
			break;
			case 'web':
			    $request['table']= '\App\User';
				$request['searchdata'] = ['id','name', 'mobile','aadharcard','pancard'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
			    $request['parentData'] = 'all';
				if (\Myhelper::hasRole(['retailer', 'apiuser'])){
					$request['parentData'] = [\Auth::id()];
				}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
					$request['parentData'] = $parentData;
				}else{
					$request['parentData'] = 'all';
				}
				$request['whereIn'] = 'parent_id';
			    
			    break;
		   case 'raepsagentstatement':
				$request['table']= '\App\Models\Aepsuser';
				$request['searchdata'] = ['user_id', 'merchantLoginId','merchantPhoneNumber','merchantName','merchantEmail'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
							$request['parentData'] = 'all';
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = [$id];
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;	
	    	case 'licbillpaystatement':        
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno','option1', 'option2', 'mobile','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, \Myhelper::getParents($id))){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;	
			
			case 'mobilestatement':
			case 'dthstatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno', 'id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;
			case 'billpaystatement':
		    case 'cablestatement':
		    case 'fasttagstatement': 
		    case 'fastagstatement' :
		    case 'electricitystatement': 
		    case 'postpaidstatement':
		    case 'waterstatement':
		    case 'broadbandstatement':
		    case 'lpggasstatement':
		    case 'gasutilitystatement':
		    case 'landlinestatement':
		    case 'schoolfeesstatement':      
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno','option1', 'option2', 'mobile','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;	

			case 'fundrequest':
				$request['table']= '\App\Models\Fundreport';
				$request['searchdata'] = ['amount','ref_no', 'remark','paymode', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;
			
			case 'fundrequestview':
			case 'fundrequestviewall':
				$request['table']= '\App\Models\Fundreport';
				$request['searchdata'] = ['amount','ref_no', 'remark','paymode', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'credited_by';
				break;

			case 'fundstatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['amount','number', 'mobile','credit_by', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = [\Auth::id()];
			    if(\Myhelper::hasRole(['subadmin'])){
					    	$request['parentData'] = 'all';
				}else{
				  	$request['parentData'] = [\Auth::id()];
				}
				$request['whereIn'] = 'user_id';
				break;
			
			case 'aepsfundrequest':
				$request['table']= '\App\Models\Aepsfundrequest';
				$request['searchdata'] = ['amount','type', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;

			case 'aepsfundrequestview':
			case 'aepsfundrequestviewall':
			case 'aepspayoutrequestview':
				$request['table']= '\App\Models\Aepsfundrequest';
				$request['searchdata'] = ['payoutid','amount','type', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if(\Myhelper::hasNotRole(['admin'])){
					$request['parentData'] = [\Auth::id()];
				}else{
					$request['parentData'] = 'all';
				}
				$request['whereIn'] = 'user_id';
				break;
			
			case 'setupbank':
				$request['table']= '\App\Models\Fundbank';
				$request['searchdata'] = ['name','account', 'ifsc','branch'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;
			
			case 'setupapi':
				$request['table']= '\App\Models\Api';
				$request['searchdata'] = ['name','account', 'ifsc','branch'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
				
			case 'setupoperator':
				$request['table']= '\App\Models\Provider';
				$request['searchdata'] = ['name','recharge1', 'recharge2','type'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
			
			case 'setupcomplaintsub':
				$request['table']= '\App\Models\Complaintsubject';
				$request['searchdata'] = ['name'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;

			case 'resourcescheme':
				$request['table']= '\App\Models\Scheme';
				$request['searchdata'] = ['name', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;

			case 'resourcepackage':
				$request['table']= '\App\Models\Package';
				$request['searchdata'] = ['name', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;

			case 'resourcecompany':
				$request['table']= '\App\Models\Company';
				$request['searchdata'] = ['companyname'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;

			case 'setuplinks':
				$request['table']= '\App\Models\Link';
				$request['searchdata'] = ['name'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
			
			case 'accountstatement':
			case 'commissionstatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['txnid', 'user_id', 'credited_by', 'id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if($id == 0){
					$request['parentData'] = [\Auth::id()];
				}else{
					if(in_array($id, $parentData)){
						$request['parentData'] = [$id];
					}else{
						$request['parentData'] = [\Auth::id()];
					}
				}
				$request['whereIn'] = 'user_id';
				
				break;

			case 'awalletstatement':
				$request['table']= '\App\Models\Aepsreport';
				$request['searchdata'] = ['mobile','txnid', 'refno', 'payid', 'amount','mytxnid','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if($id == 0){
					$request['parentData'] = [\Auth::id()];
				}else{
					if(in_array($id, $parentData)){
						$request['parentData'] = [$id];
					}else{
						$request['parentData'] = [\Auth::id()];
					}
				}
				$request['whereIn'] = 'user_id';
				break;
			
			case 'utiidstatement':
				$request['table']= '\App\Models\Utiid';
				$request['searchdata'] = ['name','vleid', 'user_id', 'location', 'contact_person', 'pincode', 'email', 'id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;

			case 'portaluti':
				$request['table']= '\App\Models\Utiid';
				$request['searchdata'] = ['name','vleid', 'user_id', 'location', 'contact_person', 'pincode', 'email','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					$request['parentData'] = [\Auth::id()];
					$request['whereIn'] = 'sender_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;
			
			case 'utipancardstatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;
           case 'cmsstatement':
			case 'rechargestatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno', 'id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole('employee',['md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;
		 	case 'iciciagentstatement':
				$request['table']= '\App\Models\Fingagent';
				$request['searchdata'] = ['merchantPhoneNumber','userPan', 'merchantAadhar', 'merchantName','passport','shoppic','dob','merchantalernativeNumber','father','thana','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if ($id == 0 || $returntype == "all") {
				if($id == 0){
					if (\Myhelper::hasRole(['retailer', 'apiuser'])){
						$request['parentData'] = [\Auth::id()];
					}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable','masterwhitelable'])){
						$request['parentData'] = $parentData;
					}else{
						$request['parentData'] = 'all';
					}
				}else{
					if(in_array($id, $parentData)){
						$request['parentData'] = [$id];
					}else{
						$request['parentData'] = [\Auth::id()];
					}
				}
				$request['whereIn'] = 'user_id';
			}else{
				$request['parentData'] = [$id];
				$request['whereIn'] = 'id';
				$request['return'] = 'single';
			}
				break;			

			case 'billpaystatement':
				
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['number', 'txnid', 'payid', 'remark', 'description', 'refno','option1', 'option2', 'mobile','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
							
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;

			case 'moneystatement':
				$request['table']= '\App\Models\Report';
				$request['searchdata'] = ['txnid', 'mobile', 'number', 'option1', 'option2', 'option3', 'option4', 'refno', 'payid', 'amount','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['md','employee','distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;
			
			case 'aepsstatement':
				$request['table']= '\App\Models\Aepsreport';
				$request['searchdata'] = ['refno', 'mobile', 'txnid','id','aepstype'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;

			case 'complaints':
				$request['table']= '\App\Models\Complaint';
				$request['searchdata'] = ['type', 'solution', 'description', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;
				
				case 'supportdata':
				$request['table']= '\App\Models\Support';
				$request['searchdata'] = ['title', 'discrption','user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole('employee',['md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;

			case 'apitoken':
				$request['table']= '\App\Models\Apitoken';
				$request['searchdata'] = ['ip'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if (\Myhelper::hasRole('admin')){
					$request['parentData'] = 'all';
				}else{
					$request['parentData'] = [\Auth::id()];
				}
				$request['whereIn'] = 'user_id';
				break;

			case 'aepsagentstatement':
				$request['table']= '\App\Models\Mahaagent';
				$request['searchdata'] = ['bc_f_name','bc_m_name', 'bc_id', 'phone1', 'phone2', 'emailid','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = [$id];
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;

			case 'nsdlstatement':
				$request['table']= '\App\Models\Nsdlpan';
				$request['searchdata'] = ['lastname'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;

			case 'matmfundrequest':
				$request['table']= '\App\Models\Microatmfundrequest';
				$request['searchdata'] = ['amount','type', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;

			case 'matmfundrequestview':
			case 'matmfundrequestviewall':
				$request['table']= '\App\Models\Microatmfundrequest';
				$request['searchdata'] = ['amount','type', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if(\Myhelper::hasNotRole(['admin'])){
					$request['parentData'] = [\Auth::id()];
				}else{
					$request['parentData'] = 'all';
				}
				$request['whereIn'] = 'user_id';
				break;

			case 'matmstatement':
				$request['table']= '\App\Models\Microatmreport';
				$request['searchdata'] = ['aadhar', 'mobile', 'txnid', 'payid', 'mytxnid', 'terminalid','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['employee','md', 'distributor','whitelable'])){
							$request['parentData'] = $parentData;
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, $parentData)){
							$request['parentData'] = \Myhelper::getParents($id);
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;

			case 'matmwalletstatement':
				$request['table']= '\App\Models\Microatmreport';
				$request['searchdata'] = ['mobile','aadhar', 'txnid', 'refno', 'payid', 'amount','mytxnid','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				if($id == 0){
					$request['parentData'] = [\Auth::id()];
				}else{
					if(in_array($id, $parentData)){
						$request['parentData'] = [$id];
					}else{
						$request['parentData'] = [\Auth::id()];
					}
				}
				$request['whereIn'] = 'user_id';
				break;
			case 'securedata':
				$request['table']= '\App\Models\Securedata';
				$request['searchdata'] = ['user_id','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if (\Myhelper::hasRole('admin') || \Myhelper::hasRole('subadmin')){
					$request['parentData'] = 'all';
				}else{
					$request['parentData'] = [\Auth::id()];
				}
				$request['whereIn'] = 'user_id';
				break;
				
			case 'loginslide':
				$request['table']= '\App\Models\PortalSetting';
				$request['searchdata'] = ['name'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = ['slides'];
				$request['whereIn'] = 'code';
				break;	
				
			case 'loanenquirystatement':
				$request['table']= '\App\Models\LoanEnquiry';
				$request['searchdata'] = ['user_id','id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if (\Myhelper::hasRole('admin')){
					$request['parentData'] = 'all';
				}else{
					$request['parentData'] = [\Auth::id()];
				}
				$request['whereIn'] = 'user_id';
				break;

			default:
				# code...
				break;
        }
        
		$request['where']=0;
		$request['type']= $type;
        
		try {
			$totalData = $this->getData($request, 'count');
		} catch (\Exception $e) {
			$totalData = 0;
		}

		if ((isset($request->searchtext) && !empty($request->searchtext)) ||
           	(isset($request->todate) && !empty($request->todate))       ||
           	(isset($request->product) && !empty($request->product))       ||
           	(isset($request->status) && $request->status != '')		  ||
           	(isset($request->agent) && !empty($request->agent))
         ){
	        $request['where'] = 1;
	    }

		try {
			$totalFiltered = $this->getData($request, 'count');
		} catch (\Exception $e) {
			$totalFiltered = 0;
		}
		//return $data = $this->getData($request, 'data');
		try {
			$data = $this->getData($request, 'data');
		} catch (\Exception $e) {
			$data = [$e];
		}
		
		//dd($data);
		if ($request->return == "all" || $returntype =="all") {
			$json_data = array(
				"draw"            => intval( $request['draw'] ),
				"recordsTotal"    => intval( $totalData ),
				"recordsFiltered" => intval( $totalFiltered ),
				"data"            => $data
			);
			echo json_encode($json_data);
		}else{
			return response()->json($data);
		}
	}

	public function getData($request, $returntype)
	{ 
		$table = $request->table;
		$data = $table::query();
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

		if( $request->type != "roles" &&
			$request->type != "permissions" &&
			$request->type != "fundrequestview" &&
			$request->type != "fundrequest" &&
			$request->type != "setupbank" &&
			$request->type != "setupapi" &&
			$request->type != "setuplinks" &&
			$request->type != "setupoperator" &&
			$request->type != "resourcescheme" &&
			$request->type != "resourcecompany" &&
			$request->type != "resourcepackage" &&
			$request->type != "aepsfundrequestview" &&
			$request->type != "fundrequestview" &&
			$request->type != "loginslide" &&
			$request->type != "raepsagentstatement" &&
			$request->type != "kycpending" &&
			$request->type != "kycrejected" &&
			$request->type != "kycsubmitted" && 
			$request->type != "web" && 
			$request->type != "fastagstatement" &&
			$request->type != "sprintpayoutusers" &&
			!in_array($request->type , ['whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'other','employee', 'tr'])&&
			$request->where != 1
        ){
            if(!empty($request->fromdate)){
                $data->whereDate('created_at', $request->fromdate);
            }
	    }

        switch ($request->type) {
			case 'whitelable':
			case 'md':
			case 'distributor':
			case 'retailer':
			case 'apiuser':
			case 'employee' :    
				$data->whereHas('role', function ($q) use($request){
					$q->where('slug', $request->type);
				})->where('kyc', 'verified');
			break;

			case 'other':
				$data->whereHas('role', function ($q) use($request){
					$q->whereNotIn('slug', ['whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'admin','employee']);
				});
			break;
			
			case 'web':  
				$data->where('kyc', 'verified');
			break;
			
			case 'licbillpaystatement':
				$data->where('product', 'licbillpay')->where('rtype', 'main');
			break;	

			case 'tr':
				$data->whereHas('role', function ($q) use($request){
					$q->whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer', 'apiuser']);
				})->where('kyc', 'verified');
			break;

			case 'kycpending':
				$data->whereHas('role', function ($q) use($request){
					$q->whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer', 'apiuser','employee']);
				})->whereIn('kyc', ['pending']);
			break;

			case 'kycsubmitted':
				$data->whereHas('role', function ($q) use($request){
					$q->whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer', 'apiuser','employee']);
				})->whereIn('kyc', ['submitted']);
			break;
				
			case 'kycrejected':
				$data->whereHas('role', function ($q) use($request){
					$q->whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer', 'apiuser','employee']);
				})->whereIn('kyc', ['rejected']);
			break;
		    case 'mobilestatement':
				$data->where('product', 'recharge')->whereHas('provider', function ($q){
					$q->where('type', 'mobile');
				})->where('rtype', 'main');
				break;

			case 'dthstatement':
				$data->where('product', 'recharge')->whereHas('provider', function ($q){
					$q->where('type', 'dth');
				})->where('rtype', 'main');
				break;	

			case 'fundrequest':
				$data->where('type', 'request');
				break;
				
			case 'matmstatement':
				$data->where('rtype', 'main')->where('aepstype', 'MATM');
				break;	

			case 'fundrequestview':
				$data->where('status', 'pending')->where('type', 'request');
				break;
			
			case 'fundrequestviewall':
				$data->where('type', 'request');
				break;

			case 'aepsfundrequestview':
				$data->where('status', 'pending');
				break;

			case 'aepspayoutrequestview':
				$data->where('status', 'pending')->where('payouttype', 'payout');
				break;

			case 'rechargestatement':
				$data->where('product', 'recharge')->where('rtype', 'main');
				break;
			case 'cmsstatement':
	            		$data->where('product', 'cms');
			break;	
			case 'billpaystatement':

				$data->where('product', 'billpay')->where('rtype', 'main');

				break;
			case 'cablestatement':
				$data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'cable');
				})->where('rtype', 'main');
				break;
			case 'fasttagstatement':
			case 'fastagstatement':    
				$data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'fasttag');
				})->where('rtype', 'main');
				break;	
			case 'electricitystatement':
			    $data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'electricity');
				})->where('rtype', 'main');
				break;
			case 'postpaidstatement':
			    $data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'postpaid');
				})->where('rtype', 'main');
				break;
			case 'waterstatement':
			    $data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'water');
				})->where('rtype', 'main');
				break;
			case 'broadbandstatement':
			    $data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'broadband');
				})->where('rtype', 'main');
				break;
			case 'lpggasstatement':
			    $data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'lpggas');
				})->where('rtype', 'main');
				break;	
			case 'gasutilitystatement':
			    $data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'gasutility');
				})->where('rtype', 'main');
				break;
			case 'landlinestatement':
			    $data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'landline');
				})->where('rtype', 'main');
				break;
			case 'schoolfeesstatement':
			    $data->where('product', 'billpay')->whereHas('provider', function ($q){
					$q->where('type', 'schoolfees');
				})->where('rtype', 'main');
				break;	
			
			

			case 'aepsstatement':
				$data->where('rtype', 'main')->where('transtype','transaction')->whereIn('aepstype', ['CW','AP','M',"BE","MS"]);
				break;
			
			case 'utipancardstatement':
				$data->where('product', 'utipancard')->where('rtype', 'main');
				break;
			
			case 'fundstatement':
				$data->whereHas('provider', function ($q){
					$q->where('recharge1', 'fund');
				});
				break;

			case 'moneystatement':
				$data->where('product', 'dmt')->where('rtype', 'main');
				break;

			case 'commissionstatement':
				$data->where('rtype', 'commission');
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
					case 'billpaystatement':
					case 'rechargestatement':
	            		$data->where('provider_id', $request->product);
					break;

					case 'setupoperator':
	            		$data->where('type', $request->product);
					break;

					case 'complaints':
	            		$data->where('product', $request->product);
					break;

					case 'fundstatement':
					case 'aepsfundrequestview':
					case 'aepsfundrequestviewall':
	            		$data->where('type', $request->product);
					break;
				}
			}
			
	        if(isset($request->status) && $request->status != '' && $request->status != null){
	        	switch ($request->type) {	
					case 'kycpending':
					case 'kycsubmitted':
					case 'kycrejected':
						$data->where('kyc', $request->status);
					break;

					default:
	            		$data->where('status', $request->status);
					break;
				}
			}
			
			if(isset($request->agent) && !empty($request->agent)){
	        	switch ($request->type) {					
					case 'whitelable':
					case 'md':
					case 'distributor':
					case 'retailer':   
					case 'apiuser':
					case 'other':
					case 'employee' :    
					case 'tr' :
					case 'kycpending':
					case 'kycsubmitted':
					case 'kycrejected':
					case 'web':    
						$data->whereIn('id', $this->agentFilter($request));
					break;
                    case 'raepsagentstatement' :
                         $data->where('user_id',$request->agent);
                        break ;
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
		
		if ($request->return == "all" || $request->returntype == "all") {
			if($returntype == "count"){
				return $data->count();
			}else{
				if($request['length'] != -1){
					$data->skip($request['start'])->take($request['length']);
				}

				if($request->select == "all"){
					return $data->get();
				}else{
					return $data->select($request->select)->get();
				}
			}
		}else{
			if($request->select == "all"){
				return $data->first();
			}else{
				return $data->select($request->select)->first();
			}
		}
	}

	public function agentFilter($post)
	{
		if ($post->type =="raepsagentstatement" || \Myhelper::hasRole('admin') || in_array($post->agent, session('parentData'))) {
			return \Myhelper::getParents($post->agent);
		}else{
			return [];
		}
	}

	public function update(Request $post)
    {
        switch ($post->actiontype) {
            case 'utiid':
                $permission = "Utiid_statement_edit";
				break;
			case 'updateaccount' : 	
            case 'raepsid' :
			case 'aepsid':
                $permission = "aepsid_statement_edit";
				break;
				
			case 'utipancard':
                $permission = "utipancard_statement_edit";
				break;
				
			case 'recharge':
                $permission = "recharge_statement_edit";
				break;
				
			case 'billpay':
                $permission = "billpay_statement_edit";
				break;
			
			case 'money':
                $permission = "money_statement_edit";
                break;

			case 'aeps':
                $permission = "aeps_statement_edit";
				break;
			
			case 'payout':
                $permission = "payout_statement_edit";
                break;
            case 'admincommission':    
                $permission = "payout_statement_edit";
                break;    
        }

        if (isset($permission) && !\Myhelper::can($permission) && $post->actiontype != "raepsid" && $post->actiontype != "aepsid") {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }

        switch ($post->actiontype) {
            case 'utiid':
                $rules = array(
					'id'    => 'required',
                    'status'    => 'required',
                    'vleid'    => 'required|unique:utiids,vleid'.($post->id != "new" ? ",".$post->id : ''),
                    'vlepassword'    => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = Utiid::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype', 'actiontype']));
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
				
            case 'updateaccount' : 
                 $rules = array(
					'id'    => 'required',
                    'status'   => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = \DB::table('sprintpayoutusers')->where('id', $post->id)->update($post->except(['id', '_token', 'actiontype']));
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                
                break ;
                
            
            case 'admincommission':
                $rules = array(
					
                    'type'    => 'required',
                    'api_id'    => 'required',
					'provider_id'    => 'required',
                    'commissiontype'    => 'required',
                    'commission'    => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				

                $action = Adminprofit::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
					

                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
				
			case 'aepsid':
                $rules = array(
					'id'    => 'required',
                    'bbps_agent_id' => 'required',
                    'bbps_id'   => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = Mahaagent::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype']));
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
				
			case 'utipancard':
                $rules = array(
					'id'    => 'required',
                    'status'    => 'required',
                    'number'    => 'required',
                    'remark'    => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}
				
				$report = Report::where('id', $post->id)->first();
				if(!$report || !in_array($report->status , ['pending', 'success'])){
					return response()->json(['status' => "Utipancard Editing Not Allowed"], 400);
				}

                $action = Report::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
					if($post->status == "reversed"){
						\Myhelper::transactionRefund($post->id);
					}

					if($report->user->role->slug == "apiuser" && $report->status == "pending" && $post->status != "pending"){
						\Myhelper::callback($report, 'utipancard');
					}
					
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
		 	case 'raepsid':
                $rules = array(
					'id'    => 'required',
                    'merchantLoginId' => 'required',
                    'merchantLoginPin'   => 'required',
                    'status'   => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = Aepsuser::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype']));
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;			
			case 'recharge':
                $rules = array(
					'id'    => 'required',
                    'status'    => 'required',
                    'txnid'    => 'required',
					'refno'    => 'required',
                    'payid'    => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				$report = Report::where('id', $post->id)->first();
				if(!$report || !in_array($report->status , ['pending', 'success'])){
					return response()->json(['status' => "Recharge Editing Not Allowed"], 400);
				}

                $action = Report::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
					if($post->status == "reversed"){
						\Myhelper::transactionRefund($post->id);
					}

					if($report->user->role->slug == "apiuser" && $report->status != "reversed" && $post->status != "pending"){
						\Myhelper::callback($report, 'recharge');
					}

                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
				
			case 'billpay':
                $rules = array(
					'id'    => 'required',
                    'status'    => 'required',
                    'txnid'    => 'required',
					'refno'    => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				$report = Report::where('id', $post->id)->first();
				if(!$report || !in_array($report->status , ['pending', 'success'])){
					return response()->json(['status' => "Recharge Editing Not Allowed"], 400);
				}

                $action = Report::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
					if($post->status == "reversed"){
						\Myhelper::transactionRefund($post->id);
					}
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
				
			case 'money':
                $rules = array(
					'id'    => 'required',
                    'status'=> 'required',
                    'txnid' => 'required',
					'refno' => 'required',
                    'payid' => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				$report = Report::where('id', $post->id)->first();
				if(!$report || !in_array($report->status , ['pending', 'success'])){
					return response()->json(['status' => "Money Transfer Editing Not Allowed"], 400);
				}

                $action = Report::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
					if($post->status == "reversed"){
						\Myhelper::transactionRefund($post->id);
					}
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;

			case 'aeps':
                $rules = array(
					'id'    => 'required',
                    'status'=> 'required',
                    'txnid' => 'required',
					'refno' => 'required',
                    'payid' => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				$report = Aepsreport::where('id', $post->id)->first();
				if(!$report || !in_array($report->status , ['pending'])){
					return response()->json(['status' => "Money Transfer Editing Not Allowed"], 400);
				}
				if($post->status == "success"){
					$post['status'] = "complete";
				}
                $action = Aepsreport::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype']));
                if ($action) {
					if($report->status == "pending" && $post->status == "complete"){
					    $user = User::where('id', $report->user_id)->first();
					    $insert = [
                            "mobile" => $report->mobile,
                            "aadhar" => $report->aadhar,
                            "api_id" => $report->api_id,
                            "txnid"  => $report->txnid,
                            "refno"  => "Txnid - ".$report->id. " Cleared",
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
                        if($report->amount >= 100 && $report->amount <= 3000){
                            $provider = Provider::where('recharge1', 'aeps1')->first();
                        }elseif($report->amount>3000 && $report->amount<=10000){
                            $provider = Provider::where('recharge1', 'aeps2')->first();
                        }
                        $post['provider_id'] = $provider->id;
                        $post['service'] = $provider->type;
            
                        if($report->aepstype == "CW"){
                            if($report->amount >= 100){
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
                            $post['reportid'] = $aeps->id;
                            $post['precommission'] = $usercommission;
                            if($report->amount > 500){
                                \Myhelper::commission($aeps);
                            }
                        }
					}
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
				
			case 'payout':
                $rules = array(
					'id'    => 'required',
                    'status'=> 'required',
					'payoutref' => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				$fundreport = Aepsfundrequest::where('id', $post->id)->first();
				if(!$fundreport || !in_array($fundreport->status , ['pending', 'approved'])){
					return response()->json(['status' => "Transaction Editing Not Allowed"], 400);
				}

                $action = Aepsfundrequest::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype']));
                if ($action) {
					if($post->status == "rejected"){
						$report = Aepsreport::where('txnid', $fundreport->payoutid)->update(['status' => "reversed"]);
						$report = Aepsreport::where('payid', $fundreport->id)->first();
						$aepsreports['api_id'] = $report->api_id;
	                    $aepsreports['payid']  = $report->payid;
	                    $aepsreports['mobile'] = $report->mobile;
	                    $aepsreports['refno']  = $report->refno;
	                    $aepsreports['aadhar'] = $report->aadhar;
	                    $aepsreports['amount'] = $report->amount;
	                    $aepsreports['charge'] = $report->charge;
	                    $aepsreports['bank']   = $report->bank;
	                    $aepsreports['txnid']  = $report->id;
	                    $aepsreports['user_id']= $report->user_id;
	                    $aepsreports['credited_by'] = $report->credited_by;
	                    $aepsreports['balance']     = $report->user->aepsbalance;
	                    $aepsreports['type']        = "credit";
	                    $aepsreports['transtype']   = 'fund';
	                    $aepsreports['status'] = 'refunded';
	                    $aepsreports['remark'] = "Bank Settlement";
	                    Aepsreport::create($aepsreports);
                    	User::where('id', $aepsreports['user_id'])->increment('aepsbalance',$aepsreports['amount']+$aepsreports['charge']);
					}
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
        }
	}
	
	public function status(Request $post)
    {
     
		if (!\Myhelper::can($post->type."_status") && $post->type != "ragentstatus") {
            return response()->json(['status' => "Permission Not Allowed"], 400);
		}
		
		if(\Myhelper::hasNotRole('admin') && $post->type != "ragentstatus"){
              return response()->json(['status' => "Permission Not Allowed"], 400);
        }
		
		switch ($post->type) {
			case 'recharge':
			case 'billpayment':
			case 'utipancard':
			case 'licbillpayment':     
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
				
			case 'bcstatus':
				$report = Mahaagent::where('id', $post->id)->first();
				break;
			 case 'ragentstatus2':
			 case 'ragentstatus3':     
             case 'ragentstatus':
		         	$report = Aepsuser::where('id', $post->id)->first();
		       break ;
			default:
				return response()->json(['status' => "Status Not Allowed"], 400);
				break;
		}

		if(!$report || $post->type != "ragentstatus" && !in_array($report->status , ['pending', 'success'])){
		   return response()->json(['status' => " Status Not Allowed"], 400);
		}

		if($post->type == "aeps" && (!$report || !in_array($report->status , ['success','pending']))){
			return response()->json(['status' => "Aeps Status Not Allowed"], 400);
		}

		switch ($post->type) {
			case 'recharge':
				switch ($report->api->code) {
					case 'recharge1':
						$url = $report->api->url.'/status?token='.$report->api->username.'&apitxnid='.$report->txnid;
        				$method = "GET";
        				$parameter = "";
        				$header = [];
						break;

					case 'recharge2':
        				$url = "https://api.paysprint.in/api/v1/service/recharge/recharge/status" ; //$report->api->url."recharge/status";
        			   //$url = 'https://paysprint.in/service-api/api/v1/service/recharge/recharge/status' ;
        				$method = "POST";
        				$parameter = json_encode(array(
        					'referenceid' => $report->txnid,
        				));
        				
                        $payload =  [
                            "timestamp" => time(),
                            "partnerId" => $report->api->username,
                            "reqid"     => $report->user_id.Carbon::now()->timestamp
                        ];
                        
                        $key = $report->api->password;
                        $signer = new HS256($key);
                        $generator = new JwtGenerator($signer);
                        $header = array(
                            "Cache-Control: no-cache",
                            "Content-Type: application/json",
                            "Token: ".$generator->generate($payload),
                            "Authorisedkey: ".$report->api->optional3
                        );
                       // dd($url,$parameter,$header) ;
        				break;
						case 'recharge5':
							$url = $report->api->url.'StatusCheck?UserID='.$report->api->username.'&Token='.$report->api->password.'&RPID='.$report->payid.'&AGENTID='.$report->txnid;
							$method = "GET";
							$parameter = "";
							$header = [];
							break;
					default:
						return response()->json(['status' => "Recharge Status Not Allowed"], 400);
						break;
				}
				break;
            	case 'licbillpayment':
        			//	$url = "https://api.paysprint.in/api/v1/service/bill-payment/bill/licstatus";
        			$url = 'https://api.paysprint.in/api/v1/service/bill-payment/bill/licstatus' ;
        				$method = "POST";
        				$parameter = json_encode(array(
                					'referenceid' => $report->txnid,
                				));
                				
                                $payload =  [
                                    "timestamp" => time(),
                                    "partnerId" => $report->api->username,
                                    "reqid"     => $report->user_id.Carbon::now()->timestamp
                                ];
                                
                                $key = $report->api->password;
                                $signer = new HS256($key);
                                $generator = new JwtGenerator($signer);
                                $header = array(
                                    "Cache-Control: no-cache",
                                    "Content-Type: application/json",
                                    "Token: ".$generator->generate($payload),
                                    "Authorisedkey: ".$report->api->optional1
                                    
                                );
                	break;	
			case 'billpayment':
			  
			    switch ($report->api->code) {  
			      
					case 'billpayment':
						$url = $report->api->url.'/status?token='.$report->api->username.'&apitxnid='.$report->txnid;
				
        				$method = "GET";
        				$parameter = "";
        				$header = [];
						break;
             
					case 'paysprintbill':
					    $provider = Provider::where('id',$report->provider_id)->first(); 
					     $url = $report->api->url."bill/status";
					    if($provider && $provider->type == "fasttag"){
					         $url = "https://api.paysprint.in/api/v1/service/fastag/Fastag/status";
					    }
        		     
        		   	//$url = "https://api.paysprint.in/api/v1/service/bill-payment/bill/licstatus" ;
        		   //	$url = "https://paysprint.in/service-api/api/v1/service/bill-payment/bill/status";  
        				$method = "POST";
        				$parameter = json_encode(array(
        					'referenceid' => $report->txnid,
        				));
        				
                        $payload =  [
                            "timestamp" => time(),
                            "partnerId" => $report->api->username,
                            "reqid"     => $report->user_id.Carbon::now()->timestamp
                        ];
                       // dd($parameter) ;
                        $key = $report->api->password;
                        $signer = new HS256($key);
                        $generator = new JwtGenerator($signer);
                        $header = array(
                            "Cache-Control: no-cache",
                            "Content-Type: application/json",
                            "Token: ".$generator->generate($payload),
                            "Authorisedkey: ".$report->api->optional1
                        );
        				break;
					
					default:
						return response()->json(['status' => "Recharge Status Not Allowed"], 400);
						break;
				}
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
			    switch ($report->api->code) {
					case 'dmt1':
        				$url = $report->api->url."Common/CheckAndUpdateStatus";
        				$method = "POST";
        				$parameter = json_encode(array(
        					'Secretkey' => $report->api->password,
        					'Saltkey' => $report->api->username,
        					'Mhid' => $report->payid,
        					'FsessionId' => $report->remark,
        				));
        
        				$header = array(
        					"Accept: application/json",
        					"Cache-Control: no-cache",
        					"Content-Type: application/json"
        				);
						break;

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
					
					default:
						return response()->json(['status' => "Dmt Status Not Allowed"]);
						break;
				}
				break;
			
			case 'aeps':
			    switch ($report->api->code) { 
					case 'raeps':
					    $url=$report->api->url."aeps/aepsquery/query";
					    $method = "POST";
					    $parameters['reference'] = $report->txnid;
                        
        
                         $key = "f4222daf470aef51" ;  //$this->api->optional2;
                         $iv  = "c316420cbd6de29b"; //$this->api->optional3;
                        $cipher   = openssl_encrypt(json_encode($parameters,true), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                        $request  = base64_encode($cipher);
                        $request  = array('body'=>$request);
                        $parameter =http_build_query($request) ;
                    	$token = $this->getPToken($report->user_id.Carbon::now()->timestamp);
                        $header = array(
                                    "Cache-Control: no-cache",
                                    "Content-Type: application/x-www-form-urlencoded",
                                    "Token: ".$token['token'],  
                                    "Authorisedkey: OWU3ZjExYjI1YmVhYjkyMGU5ZWRkMmMxYTVmZTYzOWE=" 
                                );
					    break;
					default:
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
			    }
				
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
				
			case 'bcstatus':
			    $api  = Api::where('code', 'aeps')->first();
				$url  = "http://uat.mahagram.in/AEPS/APIBCStatus";
				$method = "POST";
				$parameter = json_encode(array(
					'Secretkey' => $api->password,
					'Saltkey' => $api->username,
					'bc_id' => $report->bc_id
				));

				$header = array(
					"Accept: application/json",
					"Cache-Control: no-cache",
					"Content-Type: application/json"
				);
				break;
				case 'ragentstatus':
			    //$url = 'https://paysprint.in/service-api/api/v1/service/onboard/onboard/getonboardstatus' ;	    
				$url  = "https://api.paysprint.in/api/v1/service/onboard/onboard/getonboardstatus";
				$method = "POST";
				$token = $this->getPToken($report->user_id.Carbon::now()->timestamp);
                $header = array(
                  "Cache-Control: no-cache",
                  "Content-Type: application/json",
                  "Accept: application/json",
                  "Token: ".$token['token'],
                   "Authorisedkey: OWU3ZjExYjI1YmVhYjkyMGU5ZWRkMmMxYTVmZTYzOWE=" 
                 );
            	$parameter = json_encode(array(
            	    'merchantcode' => $report->merchantLoginId,
            	  	'mobile' => $report->merchantPhoneNumber,
					'pipe' => "bank1",
				
				));
			
				break;
				case 'ragentstatus2':
			    //$url = 'https://paysprint.in/service-api/api/v1/service/onboard/onboard/getonboardstatus' ;	    
				$url  = "https://api.paysprint.in/api/v1/service/onboard/onboard/getonboardstatus";
				$method = "POST";
				$token = $this->getPToken($report->user_id.Carbon::now()->timestamp);
                $header = array(
                  "Cache-Control: no-cache",
                  "Content-Type: application/json",
                  "Accept: application/json",
                  "Token: ".$token['token'],
                   "Authorisedkey: OWU3ZjExYjI1YmVhYjkyMGU5ZWRkMmMxYTVmZTYzOWE=" 
                 );
            	$parameter = json_encode(array(
            	    'merchantcode' => $report->merchantLoginId,
            	  	'mobile' => $report->merchantPhoneNumber,
					'pipe' => "bank2",
				
				));
			
				break;	
			 	case 'ragentstatus3':
			    //$url = 'https://paysprint.in/service-api/api/v1/service/onboard/onboard/getonboardstatus' ;	    
				$url  = "https://api.paysprint.in/api/v1/service/onboard/onboard/getonboardstatus";
				$method = "POST";
				$token = $this->getPToken($report->user_id.Carbon::now()->timestamp);
                $header = array(
                  "Cache-Control: no-cache",
                  "Content-Type: application/json",
                  "Accept: application/json",
                  "Token: ".$token['token'],
                   "Authorisedkey: OWU3ZjExYjI1YmVhYjkyMGU5ZWRkMmMxYTVmZTYzOWE=" 
                 );
            	$parameter = json_encode(array(
            	    'merchantcode' => $report->merchantLoginId,
            	  	'mobile' => $report->merchantPhoneNumber,
					'pipe' => "bank3",
				
				));
			
				break;		
			default:
				# code...
				break;
		}

		$result = \Myhelper::curl($url, $method, $parameter, $header);
	//   dd($result,$url, $method, $parameter, $header) ;
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

						case 'recharge2':
						    \DB::table('rp_log')->insert([
                                'ServiceName' => "RechargeStatus",
                                'header' => json_encode($header),
                                'body' => json_encode($parameter),
                                'response' => $result['response'],
                                'url' => $url,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                         
							$doc = json_decode($result['response']);
							// dd($doc,$result['response'],$url, $method, $parameter, $header) ;
							if(isset($doc->data->status) && $doc->data->status == "1"){
								$update['refno'] = $doc->data->operatorid ?? $report->refno;
								$update['status'] = "success";
							}elseif(isset($doc->data->status) && $doc->data->status == "0"){
								$update['status'] = "reversed";
								$update['refno'] = (isset($doc->data->operatorid)) ? $doc->data->operatorid : "failed";
							}else{
								$update['status'] = "Unknown";
								$update['refno'] = (isset($doc->data->operatorid)) ? $doc->data->operatorid : "Unknown";
							}
							break;
							case 'recharge5':
								
								$doc = json_decode($result['response']);
								// dd($doc,$result['response'],$url, $method, $parameter, $header) ;
								//dd($doc->status);
								if($doc->status == "2"){
									$update['status'] = "success";
									$update['payid'] = $doc->rpid;
									$update['refno'] = $doc->opid;
									$update['description'] = "Recharge Accepted";
								}elseif($doc->status == "3"){
									$update['status'] = "reversed";
									$update['payid'] = $doc->rpid;
									$update['refno'] = $doc->opid;
									$update['description'] = (isset($doc->MSG)) ? $doc->MSG : "Failed";
								}elseif($doc->status == "1"){
									$update['status'] = "pending";
									$update['payid'] = $doc->rpid;
									$update['refno'] = $doc->opid;
									$update['description'] = (isset($doc->MSG)) ? $doc->MSG : "Pending";
								}else{
									$update['status'] = "Unknown";
									$update['refno'] = (isset($doc->data->operatorid)) ? $doc->data->operatorid : "Unknown";
								}
								//dd($update,$doc,$result['response'],$url, $method, $parameter, $header) ;
								break;
					}
					$product = "recharge";
					break;

				case 'billpayment':
				    
					$doc = json_decode($result['response']);
					
					switch ($report->api->code) {
						case 'billpayment':
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
							break;

						case 'paysprintbill':
						    \DB::table('rp_log')->insert([
                                'ServiceName' => "BillpayStatus",
                                'header' => json_encode($header),
                                'body' => json_encode($parameter),
                                'response' => $result['response'],
                                'url' => $url,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                            
							if(isset($doc->response_code) && in_array($doc->response_code, [1])){
                                $update['status'] = "success";
                                $update['refno'] =  $doc->data->operatorid;
                            }elseif(isset($doc->response_code) && in_array($doc->response_code, [0])){
                                $update['status'] = "reversed";
                                $update['refno'] =  $doc->message;
                            }elseif(isset($doc->response_code) && in_array($doc->response_code, [12])){
                            	$update['status'] = "Unknown";
                            }else{
                                $update['status'] = "pending";
                                //$update['refno']  = "Please wait for status change or contact service provider";
                            }
					}
					
					
					$product = "billpay";
					break;
				case 'licbillpayment':	
			           \DB::table('rp_log')->insert([
                        'ServiceName' => "BillpayStatus",
                        'header' => json_encode($header),
                        'body' => json_encode($parameter),
                        'response' => $result['response'],
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    
					$doc = json_decode($result['response']);
				//dd($doc);
					  
					  if(($doc->status) == true && $doc->data->status=="1"){
						$update['refno'] = (isset($doc->txnid))?$doc->txnid:"null";
						$update['status'] = "success";
						$update['description'] = (isset($doc->message))?$doc->message:"null";
					}elseif(($doc->status) == true && $doc->data->status=="0"){
						$update['status'] = "reversed";
						$update['refno'] = (isset($doc->txnid))?$doc->txnid:"null";
						$update['description'] = (isset($doc->message))?$doc->message:"null";
					}else{
					    
						$update['status'] = "pending";
						$update['refno'] = (isset($doc->txnid))?$doc->txnid:"null";
						$update['description'] = (isset($doc->message))?$doc->message:"null";
					    
					}  
					
					$product = "licbillpay";
					
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

				case 'money':
					$doc = json_decode($result['response']);
					//dd($doc);
					switch ($report->api->code) {
						case 'dmt1':
							if(isset($doc->statuscode) && $doc->statuscode == "000"){
        					    if(isset($doc->Data[0]) && isset($doc->Data[0]->status)){
        					       if(strtolower($doc->Data[0]->status) == "success"){
            						    $update['status'] = "success";
            						    $update['refno'] = $doc->Data[0]->opt_rrn;
        					       }elseif(strtolower($doc->Data[0]->status) == "failure"){
        					            $update['status'] = "failed";
        					            $update['refno'] = isset($doc->Data[0]->opt_rrn) ? $doc->Data[0]->opt_rrn : "Failed";
        					       }elseif(strtolower($doc->Data[0]->status) == "pending"){
        					            $update['status'] = "pending";
        					       }else{
            						    $update['status'] = "Unknown";
                				   }
        					    }else{
            						$update['status'] = "Unknown";
            					}
        					}else{
        						$update['status'] = "Unknown";
        					}
        					break;

						case 'pdmt':
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
							break;
					}
					$product = "aeps";
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
			     switch ($report->api->code) {
			         
					case 'raeps':
					   \DB::table('rp_log')->insert([
                        'ServiceName' => 'Check status',
                        'header' => json_encode($header),
                        'body' => json_encode([$parameters, $request]),
                        'response' => $result['response'],
                        'url' => $url,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);   
			              if(isset($doc->response_code) && ($doc->response_code=="1")){
    						    $update['status'] = "complete";
    						    $update['refno'] = $doc->bankrrn;
    						   
					       }elseif(isset($doc->response_code) && ($doc->response_code=="0")){
					            $update['status'] = "failed";
					            $update['refno'] = isset($doc->message) ? $doc->message : "Failed";
    					
					       }elseif(isset($doc->response_code) && ($doc->response_code=="2")){
					            $update['status'] = "pending";
    						    $update['remark'] = isset($doc->message) ? $doc->message : "pending";
					       }else{
    						    $update['status'] = "Unknown";
        				   }
					  
					break;
					default:    
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
					break ;
			     }
					$product = "aeps";
					break;

				case 'matm':
					$doc = json_decode($result['response']);
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
					            $update['refno']  = isset($doc->Data[0]->rrn) ? $doc->Data[0]->rrn : "Failed";
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
					}else{
						$update['status'] = "Unknown";
					}
					$product = "matm";
					break;
					
				case 'bcstatus':
    			    $doc = json_decode($result['response']);
					//dd($doc);
					
					if(isset($doc[0]->status) && $doc[0]->status == "Active"){
					    $update['status'] = "success";
					}elseif(isset($doc[0]->status) && $doc[0]->status == "Rejected"){
					    $update['status'] = "rejected";
					    $update['remark'] = isset($doc[0]->remarks) ? $doc[0]->remarks : "Failed";
					}else{
						$update['status'] = "Unknown";
					}
    				break;
    	    	case 'ragentstatus':
    	        case 'ragentstatus2':
    	    	case 'ragentstatus2':
    	    	      \DB::table('rp_log')->insert([
                                'ServiceName' => "check status ",
                                'header' => json_encode($header),
                                'body' => json_encode($parameter),
                                'response' => $result['response'],
                                'url' => $url,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
    			    $doc = json_decode($result['response']);
					
					//dd( $doc,$result['response']);
					if(isset($doc->status) && $doc->status == true){
						if(isset($doc->is_approved) && $doc->is_approved == "Accepted"){
					    $update['status'] = "approved";
					    $update['remark'] = isset($doc->message) ? $doc->message : "success";
						}else if(isset($doc->is_approved) && $doc->is_approved == "Pending"){
						    $update['status'] = "pending";
						    $update['remark'] = isset($doc->message) ? $doc->message : "Pending";
						}else{
						     $update['status'] = "pending";
					        $update['remark'] = isset($doc->message) ? $doc->message : "Failed";
						}
					    
					}elseif(isset($doc->status) && $doc->status == false){
					    $update['status'] = "rejected";
					    $update['remark'] = isset($doc->message) ? $doc->message : "Failed";
					}else{
						$update['status'] = "Unknown";
					}
    				break;		
			}

			if ($update['status'] != "Unknown") {
				switch ($post->type) {
					case 'recharge':
					case 'billpayment':
					case 'utipancard':
				    case 'licbillpayment':      
					case 'money':
						$reportupdate = Report::updateOrCreate(['id'=> $post->id], $update);
						if ($reportupdate && $update['status'] == "reversed") {
							\Myhelper::transactionRefund($post->id);
						}
						break;
                    
                    case 'bcstatus':
						$reportupdate = Mahaagent::where('id', $post->id)->update($update);
						break;
						
                    case 'aeps':
						$reportupdate = Aepsreport::updateOrCreate(['id'=> $post->id], $update);
						
						if($report->status == "pending" && in_array($update['status'], ["complete","success"]) ){
						    $user = User::where('id', $report->user_id)->first();
						    $insert = [
                                "mobile" => $report->mobile,
                                "aadhar" => $report->aadhar,
                                "api_id" => $report->api_id,
                                "txnid"  => $report->txnid,
                                "refno"  => "Txnid - ".$report->id. " Cleared",
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
                            if($report->aepstype == "CW"){
                                if($report->amount >=500 && $report->amount <= 999){
                                    $provider = Provider::where('recharge1', 'aeps1')->first();
                                }elseif($report->amount>=1000 && $report->amount<=1499){
                                    $provider = Provider::where('recharge1', 'aeps2')->first();
                                }elseif($report->amount>=1500 && $report->amount<=1999){
                                    $provider = Provider::where('recharge1', 'aeps3')->first();
                                }elseif($report->amount>=2000 && $report->amount<=2499){
                                    $provider = Provider::where('recharge1', 'aeps4')->first();
                                }elseif($report->amount>=2500 && $report->amount<=2999){
                                    $provider = Provider::where('recharge1', 'aeps5')->first();
                                }elseif($report->amount>=3000 && $report->amount<=5999){
                                    $provider = Provider::where('recharge1', 'aeps6')->first();
                                }elseif($report->amount>=6000 && $report->amount<=10000){
                                    $provider = Provider::where('recharge1', 'aeps7')->first();
                                }
                            }else{
                                $provider = Provider::where('recharge1', 'aadharpay')->first();
                            }
                    
                            $post['provider_id'] = $provider->id;
                            $post['service']     = $provider->type;
                
                            if($report->aepstype == "CW"){
                                if($report->amount >=500){
                                    $usercommission = \Myhelper::getCommission($report->amount, $user->scheme_id, $post->provider_id,$user->role->slug);
                                }else{
                                    $usercommission = 0;
                                }
                            }elseif($report->aepstype == "AP"){
                                $usercommission = \Myhelper::getCommission($report->amount, $user->scheme_id, $post->provider_id,$user->role->slug);
                            }else{
                                $usercommission = 0;
                            }
                            
                            $insert['charge'] = $usercommission;
                            if($report->aepstype == "CW"){
                                $action = User::where('id', $report->user_id)->increment('aepsbalance', $report->amount+$usercommission);
                            }else{
                                $action = User::where('id', $report->user_id)->increment('aepsbalance', $report->amount-$usercommission);
                            }
                            
                            if($action){
                                $aeps = Aepsreport::create($insert);
                                if($report->amount > 500){
                                    \Myhelper::commission(Aepsreport::find($aeps->id));
                                }
                            }
						}
						break;

					case 'matm':
						$reportupdate = Microatmreport::where('id', $post->id)->update($update);
						
						if($report->status == "pending" && $update['status'] == "complete"){
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
                                "balance" => $user->aepsbalance,
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
            	           if($myreport->amount >= 500 && $myreport->amount <= 999){
                            $provider = Provider::where('recharge1', 'matm1')->first();
                        }elseif($myreport->amount > 1000 && $myreport->amount <= 1499){
                            $provider = Provider::where('recharge1', 'matm2')->first();
                        }elseif($myreport->amount > 1500 && $myreport->amount <= 1999){
                            $provider = Provider::where('recharge1', 'matm3')->first();
                        }elseif($myreport->amount > 2000 && $myreport->amount <= 2999){
                            $provider = Provider::where('recharge1', 'matm4')->first();
                        }elseif($myreport->amount > 3000 && $myreport->amount <= 3499){
                            $provider = Provider::where('recharge1', 'matm5')->first();
                        }elseif($myreport->amount > 3500 && $myreport->amount <= 4999){
                            $provider = Provider::where('recharge1', 'matm6')->first();
                        }elseif($myreport->amount > 5000 && $myreport->amount <= 10000){
                            $provider = Provider::where('recharge1', 'matm7')->first();
                        }
	                            
	                            $insert['provider_id'] = $provider->id;
                                if($myreport->amount > 500){
                                    $insert['charge'] = \Myhelper::getCommission($myreport->amount, $user->scheme_id, $insert['provider_id'], $user->role->slug);
                                }else{
                                	$insert['charge'] = 0;
                                }
	                        }else{
	                        	$insert['provider_id'] = 0;
	                        	$insert['charge'] = 0;
	                        }
                            
                            $action = User::where('id', $report->user_id)->increment('aepsbalance',$myreport->amount + $insert['charge']);
                            if($action){
                                 $matm = Aepsreport::create($insert);

                                if($report->amount > 500){
                                    \Myhelper::commission(Aepsreport::find($matm->id));
                                }
                            }
						}
						break;
						
					case 'utiid':
						$reportupdate = Utiid::updateOrCreate(['id'=> $post->id], $update);
						break;
				   case 'ragentstatus':
						$reportupdate = Aepsuser::where('id', $post->id)->update($update);
						break;			
				}
			}
			return response()->json($update, 200);
		}else{
			return response()->json(['status' => "Status Not Fetched , Try Again."], 400);
		}
	}
	
		public function delete(Request $post)
    {
    	if (\Myhelper::hasNotRole(['admin', 'whitelable'])) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
		}
		
		switch ($post->type) {
			case 'slide':
				try {
					\Storage::delete($post->slide);
				} catch (\Exception $e) {}
                $action = true;
				if ($action) {
		            PortalSetting::where('value', $post->slide)->delete();
		            return response()->json(['status' => "success"], 200);
		        }else{
		            return response()->json(['status' => "Task Failed, please try again"], 200);
		        }
				break;

			default:
				return response()->json(['status' => "Permission Not Allowed"], 400);
				break;
		}
    }
    
       public function getPToken($uniqueid)
    {
        $payload =  [
            "timestamp" => time(),
            "partnerId" => 'PS003380',//$this->pdmt->username,
            "reqid"     => $uniqueid
        ];
        
       $key = "UFMwMDMzODBjZTI1ZjZkYzM4MGEzMDUzZTVmZjY0MDE4YjlkYzU3YQ==";//$this->api->password;
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }
	
}
