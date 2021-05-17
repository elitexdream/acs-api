<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
		'address_1',
		'address_2',
		'zip',
		'state',
		'city',
		'country',
		'phone',
		'timezone'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function cities()
    {
    	return $this->hasMany(City::class, 'state', 'state');
    }
}
