<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SerialNumberYear extends Model
{
    protected $table = 'serial_number_year';

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
