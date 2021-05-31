<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Idle extends Model
{
    protected $table = 'idle';

    protected $fillable = [
        'device_id',
        'machine_id',
        'tag_id',
        'timestamp',
        'values',
        'serial_number'
    ];

    public $timestamps = false;
}
