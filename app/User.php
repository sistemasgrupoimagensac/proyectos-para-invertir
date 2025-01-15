<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    // protected $table = "users_gi";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users_gi';
    protected $fillable = [
        'id','name', 'email', 'password','inversionista_id', 'can_access',
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
    public function persona()
    {
        return $this->belongsTo('App\Persona', 'inversionista_id','co_persona');
    }

    public function solicitud()
    {
        return $this->hasOne('App\PSolicitudInversionista', 'co_persona', 'inversionista_id');
    }
}
