<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id', 'name'
    ];

    public function customer() {
    	return $this->hasMany('App\User');
    }

    public function devices() {
    	return $this->hasMany('App\Device', 'company_id');
    }

    public function users() {
    	return $this->hasMany('App\User', 'company_id');
    }

    public function customerAdmins() {
        $admin_ids = UserRole::where('role_id', ROLE_CUSTOMER_ADMIN)->pluck('user_id');

        return User::where('company_id', $this->id)->whereIn('id', $admin_ids)->get();
    }
}
