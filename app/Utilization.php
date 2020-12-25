<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Utilization extends Model
{
    //
    public $table = 'utilizations';
    public $timestamps = false;
    public $fillable = ['id', 'device_id', 'tag_id', 'timestamp', 'values', 'customer_id', 'machine_id'];
}
