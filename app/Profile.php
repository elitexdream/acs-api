<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
		'address_1',
		'address_2',
		'zip',
		'state',
		'city',
		'country',
		'phone'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
