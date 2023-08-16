<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = ['title', 'discrption','user_id'];

   // public $with = ['user', 'resolver', 'complaintsubject'];
   public $appends = ['username'];

    public function user(){
        return $this->belongsTo('App\User');
    }

   public function resolver(){
        return $this->belongsTo('App\User', 'resolve_id');
    }

    public function getUsernameAttribute()
    {
        $data = '';
        if($this->user_id){
            $user = \App\User::where('id' , $this->user_id)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.") (".$user->role->name.")";
        }
        return $data;
    }

    public function getResolvernameAttribute()
    {
        $data = '';
        if($this->resolve_id){
            $user = \App\User::where('id' , $this->resolve_id)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.") (".$user->role->name.")";
        }
        return $data;
    }

    public function complaintsubject(){
        return $this->belongsTo('App\Models\Complaintsubject', 'subject');
    }

}
