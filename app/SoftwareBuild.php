<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoftwareBuild extends Model
{
    protected $table = 'software_builds';

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
