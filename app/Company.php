<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id', 'name'
    ];

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

    public function materials() {
        return $this->hasMany('App\Material', 'company_id');
    }

    public function materialLocations() {
        return $this->hasMany('App\MaterialLocation', 'company_id');
    }

    public function inventoryMaterials() {
        return $this->hasMany('App\InventoryMaterial', 'company_id');
    }

    public function systemInventories() {
        return $this->hasMany('App\SystemInventory', 'company_id');
    }

    public function downtimePlans() {
        return $this->hasMany('App\DowntimePlan');
    }
}
