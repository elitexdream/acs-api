<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
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
    public function companies()
    {
        return $this->hasMany('App\Company');
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
}
