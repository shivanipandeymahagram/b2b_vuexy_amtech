<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprintpayoutuser extends Model
{
    protected $fillable = ['user_id','account','ifsc','name','bene_id','doc_upload','acc_status','remark'];

    public $appends = ['username'];
    
    public function getUsernameAttribute()
    {
        $data = '';
        if($this->user_id){
            $user = \App\User::where('id' , $this->user_id)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.") <br>(".$user->role->name.")";
        }
        return $data;
    }
}
