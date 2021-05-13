<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Swatch extends Model
{
    protected $table = 'swatches';

    protected $fillable = [
        'site_url', 'colors'
    ];

    public $timestamps = true;
}
