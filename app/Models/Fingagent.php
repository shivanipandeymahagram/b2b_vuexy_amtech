<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fingagent extends Model
{
    protected $fillable = ['merchantLoginId','merchantLoginPin','merchantName','merchantAddress','merchantCityName','merchantState','merchantPhoneNumber','merchantEmail','merchantShopName','userPan','merchantPinCode','merchantAadhar', 'aadharPic','pancardPic','status','everify','via','user_id','remark','father','dob','thana','merchantalernativeNumber','passport','shoppic'];

    public $with = ['user'];

    public function user(){
         return $this->belongsTo('App\User')->select(['id', 'name', 'mobile', 'role_id','state']);
     }
}
