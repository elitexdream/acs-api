<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DowntimePlan extends Model
{
    public $table = 'downtime_plans';
    public $timestamps = false;
    public $fillable = ['machine_id', 'company_id', 'date_from', 'date_to', 'time_from', 'time_to', 'reason'];
}
