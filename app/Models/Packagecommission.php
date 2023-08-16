<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packagecommission extends Model
{
    protected $fillable = ['slab', 'type', 'value', 'scheme_id'];

    public $with = ['provider'];

    public function provider(){
        return $this->belongsTo('App\Models\Provider', 'slab');
    }
}
