<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Swatch extends Model
{
    //
    protected $table = 'swatches';
    public $timestamps = true;

    protected $fillable = [
        'site_url',
        'colors'
    ];
}
