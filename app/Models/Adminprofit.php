<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Adminprofit extends Model
{
    protected $fillable = ['type', 'api_id', 'provider_id', 'commissiontype', 'commission'];
    
    public $appends = ["fundbank", 'apicode', 'apiname', 'username', 'sendername', 'providername'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function sender(){
        return $this->belongsTo('App\User', 'credit_by');
    }

    public function api(){
        return $this->belongsTo('App\Models\Api');
    }

    public function provider(){
        return $this->belongsTo('App\Models\Provider');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }
    public function getCreatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }

    public function getAmountAttribute($value)
    {
        return round($value, 2);
    }

    public function getFundbankAttribute($value)
    {
        $data = '';
        if($this->product == "fund request"){
            $data = Fundbank::where('id', $this->option1)->first();
        }
        return $data;
    }

    public function getApicodeAttribute()
    {
        $data = Api::where('id' , $this->api_id)->first(['code']);
        return isset($data->code)?$data->code:"";
    }

    public function getApinameAttribute()
    {
        $data = Api::where('id' , $this->api_id)->first(['name']);
        return isset($data->name)?$data->name:"";
    }

    public function getProvidernameAttribute()
    {
        $data = '';
        if($this->provider_id){
            $provider = Provider::where('id' , $this->provider_id)->first(['name']);
            $data = $provider->name;
        }
        return $data;
    }

    public function getUsernameAttribute()
    {
        $data = '';
        if($this->user_id){
            $user = \App\User::where('id' , $this->user_id)->first(['name', 'id', 'role_id','mobile']);
            $data = $user->name." (".$user->id.") <br>(".$user->mobile.") <br>(".$user->role->name.")";
        }
        return $data;
    }

    public function getSendernameAttribute()
    {
        $data = '';
        if($this->credit_by){
            $user = \App\User::where('id' , $this->credit_by)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.")<br>(".$user->role->name.")";
        }
        return $data;
    }
}
