<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialLocation extends Model
{
    protected $table = 'material_locations';

    protected $fillable = [
        'location', 'company_id'
    ];
}
