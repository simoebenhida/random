<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Database\Console\Migrations\FreshCommand;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PlayController extends Controller
{
    public function index()
    {
        //start games
        Artisan::call(FreshCommand::class, ['--seed' => true]);

        return view('welcome', [
            'games' => Game::all()
        ]);
    }

    public function store(Game $game)
    {
        if($game->finished) {
            return [
                'status' => false
            ];
        }

        try {
            if($game->team_away()->is($game->lastAttacker())) {
                $role = 1; ////Give Role to Home Team
            }elseif($game->team_home()->is($game->lastAttacker())) {
                $role = 2; //Give Role to Away Team
            }
        } catch(ModelNotFoundException $exception) {
            $role = rand(1,2);
        }

        $players = $this->getPlayersByRole($game, $role);

        $goal = $game->attack($attacker = $players->random());

        if($goal->in) {
            $game->isAssistting($players->filter(function($item) use($attacker) {
                    return $item != $attacker;
            })->random());
        }

        return [
            'status' => true
        ];
    }

    public function show(Game $game)
    {
        return $game;
    }

    public function getPlayersByRole($game, $role)
    {
        switch ($role) {
            case 1 :
                return $game->team_home()->player;
            case 2 :
                return $game->team_away()->player;
        }
    }
}
