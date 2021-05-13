<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Utilization extends Model
{
    protected $table = 'utilizations';

    protected $fillable = [
        'device_id',
        'tag_id',
        'timestamp',
        'values',
        'serial_number',
        'machine_id',
        'timedata'
    ];

    public $timestamps = false;
}
