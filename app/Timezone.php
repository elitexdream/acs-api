<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $table = 'timezones';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
