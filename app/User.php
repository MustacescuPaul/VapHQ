<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'prenume', 'nume', 'activ', 'bonus', 'banca',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }
    public function Users_permisiuni()
    {
        return $this->hasOne('App\Users_permisiuni', 'id', 'id');
    }
    public function Users_vapoint()
    {
        return $this->hasOne('App\Users_vapoint', 'id_vapoint', 'id_vapoint');
    }
    public function Lista_reselleri()
    {
        return $this->hasMany('App\Lista_reselleri', 'id_vapoint', 'id_client');
    }
}
