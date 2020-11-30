<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	public $timestamps = false;
	
	/**
    * The users that have this role.
    *
    * @return mixed
    */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_roles', 'role_id', 'user_id');
    }

    static public function getCompanyRoles() {
    	$role_keys = ['customer_manager', 'customer_operator'];
    	Role::whereIn('key', $role_keys)->get();
    }
}
