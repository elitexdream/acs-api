<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id', 'name'
    ];

    public function customer() {
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function devices() {
    	return $this->hasMany('App\Device', 'company_id');
    }

    public function users() {
    	return $this->hasMany('App\User', 'company_id');
    }
}
