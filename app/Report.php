<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $table = 'reports';

    protected $fillable = [
        'filename', 'from', 'to'
    ];
}
