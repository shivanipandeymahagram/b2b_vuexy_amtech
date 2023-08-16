<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Api extends Model
{
	// // use LogsActivity;
    protected $fillable = ['product', 'name', 'url', 'username', 'password', 'optional1', 'status', 'code', 'type','commissionCharge','commissiontype'];

    protected static $logAttributes = ['product', 'name', 'url', 'username', 'password', 'optional1', 'status', 'code', 'type'];
    protected static $logOnlyDirty = true;
}
