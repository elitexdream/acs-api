<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlarmStatus extends Model
{
    protected $table = 'alarm_status';

    protected $fillable = [
        'device_id',
        'tag_id',
        'offset',
        'timestamp',
        'machine_id',
        'is_activate'
    ];
}
