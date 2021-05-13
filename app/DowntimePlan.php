<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DowntimePlan extends Model
{
    protected $table = 'downtime_plans';

    protected $fillable = [
        'company_id',
        'machine_id',
        'date_from',
        'date_to',
        'time_from',
        'time_to',
        'reason'
    ];

    public $timestamps = false;
}
