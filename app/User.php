<?php

namespace App;

use Illuminate\Notifications\Notifiable;
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
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function tokens(){
        return $this->hasOne('App\LoginToken','user_id');
    }
    public function boards(){
        return $this->hasMany('App\Board','creator_id');
    }
    public function manyBoards(){
        return $this->belongsToMany('App\Board','board_members','user_id','board_id')->withTimestamps();
    }
}
