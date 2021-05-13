<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SerialNumberMonth extends Model
{
    protected $table = 'serial_number_month';

    protected $fillable = [
        'device_id',
        'tag_id',
        'timestamp',
        'values',
        'machine_id',
        'serial_number',
    ];

    public $timestamps = false;
}
