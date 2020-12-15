<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $fillable = ['note', 'device_id'];
}
