<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\AlarmCreatedEvent;

class DeviceData extends Model
{
	public $table = 'device_data';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
    }
}
