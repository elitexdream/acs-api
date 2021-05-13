<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Graph extends Model
{
    protected $table = 'graphs';

    protected $fillable = [
        'machine_id', 'graph_id', 'graph_name',
    ];
}
