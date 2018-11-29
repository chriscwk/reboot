<?php

namespace App;

// use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];

    public function getPublishedArticlesAttribute()
    {
        return $this->hasMany('App\Article')->whereUserId($this->id)->count();
    }

    public function getOrganizedMeetupsAttribute()
    {
        return $this->hasMany('App\Event')->whereUserId($this->id)->count();
    }
}
