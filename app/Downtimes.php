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
        'reason',
        'comment'
    ];
}
