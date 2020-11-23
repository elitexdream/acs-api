<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    static public function getCompanyRoles() {
    	$role_keys = ['customer_manager', 'customer_operator'];
    	Role::whereIn('key', $role_keys)->get();
    }
}
