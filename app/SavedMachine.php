<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedMachine extends Model
{
    protected $table = 'saved_machines';

    protected $fillable = [
        'user_id', 'device_id'
    ];
}
