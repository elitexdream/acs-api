<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SerialNumberUnit extends Model
{
    protected $table = 'serial_number_unit';

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
