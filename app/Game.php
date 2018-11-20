<?php

namespace App;

use App\Traits\PlayGame;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use PlayGame;

    protected $guarded = [];

    protected $with = ['goal'];

    protected $appends = [
        'players_home',
        'players_away',
        'finished',
        'attack_count'
    ];

    public function score()
    {
        return $this->hasMany('App\Score');
    }

    public function goal()
    {
        return $this->hasMany('App\Goal');
    }

    public function assist()
    {
        return $this->hasMany('App\Assist');
    }

    public function getFinishedAttribute()
    {
        return $this->created_at->diffInSeconds(now()) > 240;
    }

    //get players score
    public function getPlayersHomeAttribute()
    {
        return $this->team_home()->player->filter(function($player, $key) {
            $player['score'] = $this->getScoreByPlayer($player);
            $player['assist'] = $this->getAssistScore($player);
            $player['pts'] = $this->getRatePoints($player);
            return $player;
        });
    }

    public function getPlayersAwayAttribute()
    {
        return $this->team_away()->player->filter(function($player, $key) {
            $player['score'] = $this->getScoreByPlayer($player);
            $player['assist'] = $this->getAssistScore($player);
            $player['pts'] = $this->getRatePoints($player);
            return $player;
        });
    }

    public function getAttackCountAttribute()
    {
        return [
            'home' => $this->goal()
                    ->whereIn('player_id', $this->team_away()->player->pluck('id'))
                    ->get()
                    ->count(),
            'away' => $this->goal()
                    ->whereIn('player_id', $this->team_home()->player->pluck('id'))
                    ->get()
                    ->count()
        ];
    }

    public function toArray()
    {
        return array_merge(parent::toArray() ,[
            'team_away' => $this->team_away(),
            'team_home' => $this->team_home(),
            $this->getGlobalScoreOfEachTeam()
        ]);
    }
}
