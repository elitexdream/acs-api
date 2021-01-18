<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\AlarmCreatedEvent;
use Rennokki\QueryCache\Traits\QueryCacheable;

class DeviceData extends Model
{
	// use QueryCacheable;

	// protected $cacheFor = 60;
	
	public $table = 'device_data';
    public $timestamps = false;

    protected $fillable = [
        'created_at'
    ];

    protected static function boot()
    {
        parent::boot();
    }
}
