<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoardList extends Model
{
    public function cards(){
        return $this->hasMany('App\Card','list_id');
    }
}
