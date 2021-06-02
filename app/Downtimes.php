<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Downtimes extends Model
{
    protected $table = 'downtimes';

    protected $fillable = [
        'device_id',
        'start_time',
        'end_time',
        'timestamp',
        'type',
        'reason_id',
        'comment',
        'running_start_id',
        'running_end_id',
        'idle_start_id',
        'idle_end_id',
        'foreign_type'
    ];
}
