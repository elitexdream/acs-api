<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HopperClearedTime extends Model
{
    protected $table = 'hopper_cleared_time';

    protected $fillable = [
        'serial_number', 'timestamp', 'last_cleared_time', 'device_id', 'machine_id'
    ];
}
