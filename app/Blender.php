<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blender extends Model
{
    protected $fillable = [
        'blender_id', 'values', 'timestamp'
    ];

    public $timestamps = false;
}
