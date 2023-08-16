<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utiid;
use App\Models\Report;
use App\Models\Provider;
use App\Models\Circle;
use App\User;
use Carbon\Carbon;
use App\Models\Nsdlpan;
use App\Models\Api;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class SpancardController extends Controller
{
    protected $api;
    
    public function __construct()
    {
        $this->api = Api::where('code', 'pancard')->first();
    }
    
    public function index()
    {
        return view("service.sprintpancard");
    }
    
    public function indexnsdl()
    {
        return view("service.snsdlpancard");
    }

    public function payment(Request $post)
    {
      
        if(!$post->has('user_id')){
            $post['user_id'] = \Auth::id();
        }
        dd($post->all()) ; exit ;
        switch ($post->actiontype)
        {
            case "snsdlintiate":
                
                if(!$this->api || $this->api->status == 0){
                    return response()->json(['statuscode' => "ERR", "message" => "Service Down"]);
                }
                
                do {
                    $post['txnid'] = "EB".rand(1111111111, 9999999999);
                } while (Utiid::where("txnid", "=", $post->txnid)->first() instanceof Utiid);
                    
                $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".$token['token'],
                    "Authorisedkey: ".$this->api->optional1
                );
                  
                $url="https://paysprint.in/service-api/api/v1/service/pan/V2/generateurl";
                
                $parameter['refid']=$post->txnid;
                $parameter['title']=$post->title;
                $parameter['firstname']=$post->f_name;
                $parameter['middlename']=$post->m_name;
                $parameter['lastname']=$post->l_name;
                $parameter['mode']=$post->panmode;  
                $parameter['gender']=$post->gender;
                $parameter['redirect_url']= "http://login.quickrrpay.com"; 
                $parameter["'email i'd'"]=$post->email;
                
                $result = \Myhelper::curl($url, "POST", json_encode($parameter), $header, "yes", 'App\Models\Utiid' , $post->txnid);
                dd($url,$result,$header,json_encode($parameter));
                \DB::table('rp_log')->insert([
                    'ServiceName' => "NSDL Pan",
                    'header' => json_encode($header),
                    'body' => json_encode([$parameter]),
                    'response' => $result['response'],
                    'url' => $url,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $response=json_decode($result['response']);
                
                if(isset($response->status) && $response->status==true && $response->response_code=="1"){
                    return response()->json(["status"=>"TXN","message"=>"fetch url","data"=>$response->data]);
                }else{
                    return response()->json(["status"=>"TXF","message"=>"somthing went wrong!"]);
                }
            break;
            
            case "sutiintiate":
                
                if(!$this->api || $this->api->status == 0){
                    return response()->json(['statuscode' => "ERR", "message" => "Service Down"]);
                }
                
                do {
                    $post['txnid'] = "EB".rand(1111111111, 9999999999);
                } while (Utiid::where("txnid", "=", $post->txnid)->first() instanceof Utiid);
                    
                $token = $this->getToken($post->user_id.Carbon::now()->timestamp);
                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".$token['token'],
                    "Authorisedkey: ".$this->api->optional1
                );
                  
                 //$url=$this->api->url;  
                $url = "https://api.paysprint.in/api/v1/service/pan/generateurl" ;
             //   $url="https://paysprint.in/service-api/api/v1/service/pan/generateurl";
                $parameter['merchantcid'] = "MB2110282";
                $parameter['refid']   = $post->txnid;
                $parameter['redirect_url']   = url()->current() ;
                $result = \Myhelper::curl($url, "POST", json_encode($parameter), $header, "yes", 'App\Models\Utiid' , $post->txnid);
                \DB::table('rp_log')->insert([
                    'ServiceName' => "UTI Pan",
                    'header' => json_encode($header),
                    'body' => json_encode([$parameter]),
                    'response' => $result['response'],
                    'url' => $url,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                 $response=json_decode($result['response']);
                if(isset($response->status) && $response->status==true && $response->response_code=="1"){
                    \DB::table('uti_orders')->insert([
                        'txnid' => $post->txnid,
                        'user_id' => $post->user_id
                    ]);
                    return response()->json(["statuscode"=>"TXN","message"=>"fetch url","data"=>$response->data]);
                }else{
                    return response()->json(["statuscode"=>"TXF","message"=>"somthing went wrong!"]);
                }
                
            break;
        }
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
    
    public function initiatesnsdlcallback(Request $post){
        
        \DB::table('microlog')->insert(['product'=>'snsdlinitiate','response'=>json_encode($post->all())]); 
        
    }
    
}
