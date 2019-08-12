<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{
    protected $fillable = [
        'token'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }


}
