<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    public function index()
    {

        if (\Myhelper::hasRole('admin') || \Myhelper::can('invesment_banner')) {
            return view('banner.banner');
        }
      
        return \Response::json(['statuscode' => 'ERR', 'status' => "Permission not allowed", 'message' => "Permission not allowed"], 400);
    }
    

    
    public function video()
    {
        if (\Myhelper::hasRole('admin') || \Myhelper::can('invesment_video')) {
            return view('banner.video');
        }
      
        return \Response::json(['statuscode' => 'ERR', 'status' => "Permission not allowed", 'message' => "Permission not allowed"], 400);
    }
    
    public function store(Request $post)
    {
        $rules = array(
            'title' => 'required|string',
            'slides' => 'sometimes|required|mimes:jpg,JPG,jpeg,png|max:500'
        );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }
        $insert = $post->all();
        $insert['user_id'] = Auth::user()->id;
        if($post->hasFile('slides')){ 
           
            $filename = time().'.'.$post->file('slides')->guessExtension();
            $post->file('slides')->move(public_path('banner/'), $filename);
            $insert['slides'] = $filename;

        }
        $action = Banner::updateOrCreate(['id'=> $post->id], $insert);
        if ($action) {
            return response()->json(['status' => "success"], 200);
        }else{
            return response()->json(['status' => "Task Failed, please try again"], 200);
        }
    }

    public function storeVideo(Request $post)
    {
        $rules = array(
            'title' => 'required|string',
            'video' => 'sometimes|required|mimes:mp4,gif|max:5000000'
        );
        
        $validator = \Validator::make($post->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 200);
        }
        $insert = $post->all();
        $insert['user_id'] = Auth::user()->id;
        if($post->hasFile('video')){ 
           
            $filename = time().'.'.$post->file('video')->guessExtension();
            $post->file('video')->move(public_path('banner/'), $filename);
            $insert['slides'] = $filename;

        }
        $action = Video::updateOrCreate(['id'=> $post->id], $insert);
        if ($action) {
            return response()->json(['status' => "success"], 200);
        }else{
            return response()->json(['status' => "Task Failed, please try again"], 200);
        }
    }
}
