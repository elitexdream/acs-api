<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvailabilityPlanTime extends Model
{
    protected $table = 'availability_plan_time';

    protected $fillable = [
        'timestamp',
        'company_id',
        'hours'
    ];

    public $timestamps = false;
}
