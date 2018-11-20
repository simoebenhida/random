<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $guarded = [];

    public function player()
    {
        return $this->hasMany('App\Player');
    }

    public function game()
    {
        return $this->hasMany('App\Game', 'team_home');
    }

    public function against(Team $team)
    {
        return $this->game()->create([
            'team_away' => $team->id
        ]);
    }
}
