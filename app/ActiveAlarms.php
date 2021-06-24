<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveAlarms extends Model
{
    protected $table = 'active_alarms';

    protected $fillable = [
        'device_id',
        'timestamp',
        'machine_id',
        'offset',
        'bytes',
        'serial_number',
        'tag_id'
    ];
}
