<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assist extends Model
{
    protected $guarded = [];

    public function player()
    {
        return $this->belongsTo('App\Player');
    }
}
