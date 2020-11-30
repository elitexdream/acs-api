<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceData extends Model
{
	public $table = 'device_data';

    protected static function boot()
    {
        parent::boot();

        static::created(function ($device_data) {
            if($device_data->tag_id === 27
            	|| $device_data->tag_id === 28
            	|| $device_data->tag_id === 30
            	|| $device_data->tag_id === 31
            	|| $device_data->tag_id === 32
            	|| $device_data->tag_id === 33
            	|| $device_data->tag_id === 34
            	|| $device_data->tag_id === 35
            	|| $device_data->tag_id === 36
            	|| $device_data->tag_id === 37
            	|| $device_data->tag_id === 38
            	|| $device_data->tag_id === 39
            	|| $device_data->tag_id === 40
            	|| $device_data->tag_id === 41
            ) {
	            Alarm::create([
	            	'tag_id' => $device_data->tag_id,
	            	'machine_id' => $device_data->machine_id,
	            	'device_id' => $device_data->device_id,
	            ]);
            }
        });
    }
}
