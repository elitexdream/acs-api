<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceConfiguration extends Model
{
    protected $table = "device_configurations";

    protected $fillable = [
        'teltonika_id',
        'plc_type',
        'plc_serial_number',
        'plc_status',
        'tcu_type',
        'tcu_serial_number',
        'tcu_status',
        'body'
    ];
}







