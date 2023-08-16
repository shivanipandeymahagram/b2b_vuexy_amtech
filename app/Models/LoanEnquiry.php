<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanEnquiry extends Model
{
    protected $fillable = ['loanamount', 'mobile', 'email', 'c_name', 'pan', 'adhar', 'address', 'city', 'state', 'earningtype', 'loantype', 'remarks', 'user_id','pincode','refby'];

    public $appends = ['username',"ref"];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }
    public function getCreatedAtAttribute($value)
    {
        return date('d M - H:i', strtotime($value));
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
    
     public function getRefAttribute()
    {
        $data = '';
        if($this->refby){
            $user = \App\User::where('id' , $this->refby)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.") (".$user->role->name.")";
        }
        return $data;
    }
}
