<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    public $fillable = ['tag_id', 'machine_id', 'device_id'];
}
