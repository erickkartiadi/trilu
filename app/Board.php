<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = [
        'name'
    ];

    public function lists(){
        return $this->hasMany('App\BoardList')->orderBy('order','ASC');
    }
    public function users(){
        return $this->belongsToMany('App\User','board_members','user_id');
    }
}
