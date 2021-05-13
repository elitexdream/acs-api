<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceCheckin extends Model
{
    protected $table = "device_checkins";

    protected $fillable = [
        'device_id',
        'ts',
        'sdk',
        'acs_sha1',
        'config_hash',
        'status'
    ];
}
