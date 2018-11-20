<?php

namespace App;

use App\Game;
use App\Player;
use App\Score;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($goal) {
            if(! $goal->in) {
                return;
            }

            // Update score value on game table
            tap(Game::find($goal->game_id), function($game) use($goal) {
                $team = Player::find($goal->player_id)->team;

                if($game->team_home == $team->id)
                {
                    $game->increment('score_home', $goal->point);
                }elseif($game->team_away == $team->id)
                {
                    $game->increment('score_away', $goal->point);
                }
            });
        });
    }

    public function player()
    {
        return $this->belongsTo('App\Player');
    }

    public function lastAttempt()
    {
        return $this->player->team;
    }
}
