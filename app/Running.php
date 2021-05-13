<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Running extends Model
{
    protected $table = 'runnings';

    protected $fillable = [
        'device_id',
        'tag_id',
        'timestamp',
        'values',
        'machine_id',
        'serial_number'
    ];

    public $timestamps = false;
}
