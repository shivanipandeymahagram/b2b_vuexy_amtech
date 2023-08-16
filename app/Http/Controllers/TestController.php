<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Report;
use App\Models\Api;
use App\User;
use Carbon\Carbon;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class TestController extends Controller
{
    protected $api;
    public function __construct()
    {
        $this->api = Api::where('code', 'recharge2')->first();
    }
    

   
     public function gethlrcheck()
    {
        $user_id=12;
        $mobile=8853694572;
        $url = "https://paysprint.in/service-api/api/v1/service/recharge/hlrapi/hlrcheck";
        $parameter = [
                    "number" =>  $mobile,
                    "type"  =>  "mobile"
                    
                ];

                $token = $this->getToken1($user_id.Carbon::now()->timestamp);
                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token:".$token['token'],
                     "Authorisedkey: MTYwYjI4ODU3NjA0MDVmZDJjYTM1ZWZkZTM5MWUxNzQ="
                   
                    
                );

                $body = json_encode($parameter);
                
        $result = \Myhelper::curl($url, "POST", $body, $header, "no");
        //return response()->json(["Request:"=>$body]);
        dd($header,$url,$body,$result);
        
        
        if($result['response'] != ''){
            $response = json_decode($result['response']);

            if(!isset($response->statuscode)){
                
                return response()->json(['status' => "success", "data" => $response], 200);
            }

            return response()->json(['status' => "failed", "message" => "Something went wrong"]);
        }else{
            return response()->json(['status' => "failed", "message" => "Something went wrongs"]);
        }
    }
    
    /* public function getplans()
    {
        $user_id=12;
        $mobile=8853694572;
        $token = $this->getToken1($user_id.Carbon::now()->timestamp);
       // dd($token['token']);
        //dd($post->all());
       // $provider = Provider::where('id', $post->operator)->first();

        // if(!$provider){
        //     return response()->json(['status' => "Operator Not Found"], 400);
        // }
        
        $url = "https://paysprint.in/service-api/api/v1/service/recharge/hlrapi/browseplan";
        $parameter = [
                    "circle" =>"UP",
                    "op"  =>  "Airtel"
                    
                ];

                $token = $this->getToken1($user_id.Carbon::now()->timestamp);
                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token:".$token['token'],
                     "Authorisedkey: MzNkYzllOGJmZGVhNWRkZTc1YTgzM2Y5ZDFlY2EyZTQ="
                   
                    
                );

                $body = json_encode($parameter);

        $result = \Myhelper::curl($url, "POST", $body, $header, "no");
        dd($result);
        if($result['response'] != ''){
            $response = json_decode($result['response']);

            if(!isset($response->statuscode)){
                
                return response()->json(['status' => "success", "data" => $response], 200);
            }

            return response()->json(['status' => "failed", "message" => "Something went wrong"]);
        }else{
            return response()->json(['status' => "failed", "message" => "Something went wrongs"]);
        }
    }*/
    
    public function getToken1($uniqueid)
    {
        $payload =  [
            "timestamp" => time(),
            "partnerId" => "PS00180",
            "reqid"     => $uniqueid
        ];
        
        $key = "UFMwMDY3OWQwODBhY2I0NGI4OTYwOWJiMjE5MzQ0ZjYwMTg1MzJl";
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
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
   
}
