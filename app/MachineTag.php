<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineTag extends Model
{
    protected $table = 'machine_tags';

    protected $fillable = [
        'name',
        'configuration_id',
        'tag_id',
        'type',
        'offset',
        'divided_by',
    ];

    public $timestamps = false;
}
