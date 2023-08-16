<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function transcode()
    {
    	$code = DB::table('portal_settings')->where('code', 'transactioncode')->first(['value']);
        if($code){
    	   return $code->value;
        }else{
            return "none";
        }
    }

    public function schememanager()
    {
    	$code = DB::table('portal_settings')->where('code', 'schememanager')->first(['value']);
    	if($code){
           return $code->value;
        }else{
            return "none";
        }
    }
    
    

    public function mainlocked()
    {
        $code = DB::table('portal_settings')->where('code', 'mainlockedamount')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return 0;
        }
    }

    public function aepslocked()
    {
        $code = DB::table('portal_settings')->where('code', 'aepslockedamount')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return 0;
        }
    }

    public function impschargeupto25()
    {
        $code = DB::table('portal_settings')->where('code', 'impschargeupto25')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return 0;
        }
    }

    public function impschargeabove25()
    {
        $code = DB::table('portal_settings')->where('code', 'impschargeabove25')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return 0;
        }
    }

    public function neftcharge()
    {
        $code = DB::table('portal_settings')->where('code', 'settlementcharge')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return 0;
        }
    }

    public function settlementtype()
    {
        $code = DB::table('portal_settings')->where('code', 'settlementtype')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return "manual";
        }
    }

    public function banksettlementtype()
    {
        $code = DB::table('portal_settings')->where('code', 'banksettlementtype')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return "manual";
        }
    }

    public function bbpsregistration($post, $agent)
    {
        if(!$agent->bbps_agent_id){
            $gpsdata = geoip($post->ip());
            $burl  = $this->billapi->url."RegBBPSAgent";

            $json_data = [
                "requestby"     => $this->billapi->username,
                "securityKey"   => $this->billapi->password,
                "name"          => $agent->bc_f_name." ".$agent->bc_l_name,
                "contactperson" => $agent->bc_f_name." ".$agent->bc_l_name,
                "mobileNumber"  => $agent->phone1,
                'agentshopname' => $agent->shopname,
                "businesstype"  => $agent->shopType,
                "address1"      => $agent->bc_address,
                "address2"      => $agent->bc_city,
                "state"         => $agent->bc_state,
                "city"          => $agent->bc_district,
                "pincode"       => $agent->bc_pincode,
                "latitude"      => sprintf('%0.4f', $gpsdata->lat),
                "longitude"     => sprintf('%0.4f', $gpsdata->lon),
                'email'         => $agent->emailid
            ];
            
            $header = array(
                "authorization: Basic ".base64_encode($this->billapi->username.":".$this->billapi->optional1),
                "cache-control: no-cache",
                "content-type: application/json"
            );
            $bbpsresult = \Myhelper::curl($burl, "POST", json_encode($json_data), $header, "yes", 'MahaBill', $agent->phone1);

            if($bbpsresult['response'] != ''){
                $response = json_decode($bbpsresult['response']);
                if(isset($response->Data)){
                    $datas = $response->Data;
                    if(!empty($datas)){
                        \App\Models\Mahaagent::where('user_id', $post->user_id)->update(['bbps_agent_id' => $datas[0]->agentid]);
                    }
                }
            }
        }

        if($agent->bbps_agent_id && !$agent->bbps_id){
            $url = $this->billapi->url."GetBBPSAgentStatus";
            $json_data = [
                "requestby"   => $this->billapi->username,
                "securityKey" => $this->billapi->password,
                "agentId"     => $agent->bbps_agent_id
            ];

            $header = array(
                "authorization: Basic ".base64_encode($this->billapi->username.":".$this->billapi->optional1),
                "cache-control: no-cache",
                "content-type: application/json"
            );

            $bbpsresult = \Myhelper::curl($url, "POST", json_encode($json_data), $header, "no");
            $response = json_decode($bbpsresult['response']);
            if(isset($response[0]) && !empty($response[0]->bbps_Id)){
                \App\Models\Mahaagent::where('user_id',$post->user_id)->update(['bbps_id'=> $response[0]->bbps_Id]);
            }
        }
        return \App\Models\Mahaagent::where('user_id', $post->user_id)->first();
    } 
    public function pinCheck($data)
    {
        if(\Auth::check()){
        	$code = DB::table('pindatas')->where('user_id', \Auth::id())->where('pin', \Myhelper::encrypt($data->pin, "sdsada7657hgfh$$&7678"))->first();
        }else{
            $code = DB::table('pindatas')->where('user_id', $data->user_id)->where('pin', \Myhelper::encrypt($data->pin, "sdsada7657hgfh$$&7678"))->first();
        }
        if(!$code){ 
            return 'fail';
        }
    }
}
