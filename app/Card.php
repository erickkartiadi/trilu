<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function list(){
        return $this->belongsTo('App\BoardList')->orderBy('order','ASC');
    }
}
