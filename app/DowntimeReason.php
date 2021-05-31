<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DowntimeReason extends Model
{
    protected $table = 'downtime_reasons';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
