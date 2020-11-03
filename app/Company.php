<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id', 'name'
    ];

    public function customer()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }
}
