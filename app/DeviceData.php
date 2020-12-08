<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceData extends Model
{
	public $table = 'device_data';
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($device_data) {
        });
    }
}
