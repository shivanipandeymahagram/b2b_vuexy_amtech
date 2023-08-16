<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\User;
use App\Models\Report;
use App\Models\Api;
use App\Models\Support;
use Carbon\Carbon;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    // public function index()
    // {
    //     return view('complaint');
    // }

    public function store(Request $post)
    {
        $rules = array(
            'product'    => 'required',
            'subject'    => 'sometimes|required',
            'status'    => 'required',
            'user_id'    => 'required',
            'transaction_id'=>'unique:complaints'
        );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERR','message'=>$validator->errors()]);
        }

        // if($post->id == "new"){
        //     $post['user_id'] = \Auth::id();
        // }else{
        //     $post['resolve_id'] = \Auth::id();
        // }

        $action = Complaint::updateOrCreate(['id'=> $post->id], $post->all());
        if ($action) {
            //return response()->json(['status' => "success"], 200);
            return response()->json(['statuscode' => "TXN", 'message' => "Complain Updated Successfully"],200);
        }else{
            return response()->json(['status' => "Task Failed, please try again"], 200);
        }
    }
    public function support (Request $post){
        $rules = array(
            'title'    => 'sometimes|required',
            'discrption'=> 'sometimes|required',
            'user_id'    => 'required'
            
        );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERR','message'=>$validator->errors()]);
        }
        
        $action = Support::updateOrCreate(['id'=> $post->id], $post->all());
        if ($action) {
            //return response()->json(['status' => "success"], 200);
            return response()->json(['statuscode' => "TXN", 'message' => "Support Request submited"],200);
        }else{
            return response()->json(['status' => "Task Failed, please try again"], 200);
        }
        
    }
}
