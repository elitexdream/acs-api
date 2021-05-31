<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DowntimeType extends Model
{
    protected $table = 'downtime_type';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
