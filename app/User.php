<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use DB;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create([
            ]);

            // Mail::to($user->email)->send(new NewUserWelcomeMail());
        });
    }
    
    /**
    * The roles that belong to the user.
    *
    * @return mixed
    */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
    }

    /**
    * The companies that belong to the user.
    *
    * @return mixed
    */

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    /**
    * The profile that belong to the user.
    *
    * @return mixed
    */
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    /**
    * Check if the user has a specific role.
    *
    * @param mixed $roles The roles to ckeck on.
    * @return boolean
    */
    public function hasRole($roles = [])
    {
        $roles = (array) $roles;

        foreach ($this->roles as $role) {
            if (in_array($role->key, $roles)) {
                return true;
            }
        }

        return false;
    }

    public function locations()
    {
        return $this->belongsToMany('App\Location', 'user_locations', 'user_id', 'location_id');
    }

    public function customerLocations() {
        return $this->hasMany('App\Location', 'customer_id');
    }

    public function customerZones() {
        return $this->hasMany('App\Zone', 'customer_id');
    }

    /*
        return locations depending on the role
        if acs user, return all locations
        else if customer user, return customer locations
    */
    public function getMyLocations() {
        if($this->hasRole(['acs_admin', 'acs_manager', 'acs_viewer'])) {
            return Location::get();
        } else if($this->hasRole(['customer_admin'])) {
            return $this->customerLocations;
        } else {
            return $this->locations;
        }
    }

    /*
        return zones depending on the role
        if acs user, return all zones
        else if customer user, return customer zones
    */
    public function getMyzones() {
        if($this->hasRole(['acs_admin', 'acs_manager', 'acs_viewer'])) {
            return Zone::get();
        } else if($this->hasRole(['customer_admin'])) {
            return $this->customerZones;
        } else {
            return $this->zones;
        }
    }

    public function getMyDevices($location = 0, $zone = 0) {
        if($this->hasRole(['acs_admin', 'acs_manager', 'acs_viewer'])) {
            if($location) {
                if($zone)
                    return Device::where('location_id', $location)->where('zone_id', $zone)->get();
                else
                    return Device::where('location_id', $location)->get();
            }
            else
                return Device::get();
        } else {
            if($location) {
                if($zone)
                    return $this->company->devices()->where('location_id', $location)->where('zone_id', $zone)->get();
                else
                    return $this->company->devices()->where('location_id', $location)->get();
            }
            else
                return $this->company->devices;
        }
    }

    public function zones()
    {
        return $this->belongsToMany('App\Zone', 'user_zones', 'user_id', 'zone_id');
    }

    public function enabledProperties()
    {
        return DB::table('enabled_properties')->where('user_id', $this->id)->get();
    }
}
