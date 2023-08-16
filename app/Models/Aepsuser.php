<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aepsuser extends Model
{
    protected $fillable = ['merchantLoginId','merchantLoginPin','merchantName','merchantAddress','merchantCityName','merchantState','merchantPhoneNumber','userPan','merchantPinCode','merchantAadhar','aadharPic','pancardPic','status','user_id','bank1','bank2','bank3','via', 'merchantEmail', 'remark','merchantShopname'];

    public $with = ['user'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public $appends = ['username'];
    
    public function getUsernameAttribute()
    {
        $data = '';
        if($this->user_id){
            $user = \App\User::where('id' , $this->user_id)->first(['name', 'id', 'role_id']);
            if($user){
            $data = $user->name." (".$user->id.") <br>(".$user->role->name.")";
            }
        }
        return $data;
    }
}
