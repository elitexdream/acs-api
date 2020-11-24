<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	public $timestamps = false;
	
    static public function getCompanyRoles() {
    	$role_keys = ['customer_manager', 'customer_operator'];
    	Role::whereIn('key', $role_keys)->get();
    }
}
