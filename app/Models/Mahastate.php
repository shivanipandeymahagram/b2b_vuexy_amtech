<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahastate extends Model
{
    protected $fillable = ['stateid', 'statename'];
    public $timestamps = false;
}
