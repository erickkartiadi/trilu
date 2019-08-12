<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    protected $fillable = [
        'board_id','user_id'
    ];
}
