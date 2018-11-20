<?php

namespace App\Traits;

use App\Player;
use App\Team;

trait PlayGame {

    public function attack(Player $player)
    {
        return $this->goal()->create([
            'player_id' => $player->id,
            'in' => rand(0,1),
            'point' => rand(2,3)
        ]);
    }

    public function isAssistting(Player $player)
    {
        return $this->assist()->firstOrCreate([
            'player_id' => $player->id,
        ])->increment('assist');
    }

    public function team_home()
    {
        return Team::find($this->team_home);
    }

    public function team_away()
    {
        return Team::find($this->team_away);
    }

    public function lastAttacker()
    {
        return $this->goal()->latest()->firstorFail()->lastAttempt();
    }

    public function getScoreByPlayer($player)
    {
        return $this->successfullGoals($player)->sum('point');
    }

    public function successfullGoals($player)
    {
        return $this->goal()
            ->where('player_id', $player->id)
            ->get()
            ->filter(function($goal, $key) {
                return $goal->in;
            });
    }

    public function getRatePoints($player)
    {
        $total = $this->successfullGoals($player)->count();

        return [
            '2pts' => $this->getPercentageofPoints($this->successfullGoals($player)->where('point', 2)->count(), $total),
            '3pts' => $this->getPercentageofPoints($this->successfullGoals($player)->where('point', 3)->count(), $total)
        ];
        // Total Succesfull Shoots type 2pts
    }

    public function getAssistScore($player)
    {
        return $this->assist()
            ->where('player_id', $player->id)
            ->get()
            ->sum('assist');
    }

    public function getPercentageofPoints($points, $total)
    {
        if($total == 0) {
            return 0;
        }
        return intval(($points * 100) / $total);
    }

    public function getGlobalScoreOfEachTeam()
    {
        return [
            'score_home' => $this->players_home->sum('score'),
            'score_away' => $this->players_away->sum('score'),
        ];
    }
}
