<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_add_players_to_team()
    {
        $team = factory('App\Team')->create();

        $team->player()->createMany([
            ['name' => 'Mohamed'],
            ['name' => 'John'],
            ['name' => 'Doe'],
            ['name' => 'Ahmed'],
            ['name' => 'Taylor']
        ]);

        $this->assertEquals(5, $team->fresh()->player()->count());

        $this->assertDatabaseHas('players', [
            'name' => 'Mohamed',
            'team_id' => $team->id
        ]);
    }

    /** @test */
    public function start_a_game()
    {
        $team_home = factory('App\Team')->create();
        $team_away = factory('App\Team')->create();

        $team_home->against($team_away);

        $this->assertDatabaseHas('games', [
            'team_home' => $team_home->id,
            'team_away' => $team_away->id,
            'score_home' => 0,
            'score_away' => 0
        ]);
    }

    /** @test */
    public function player_can_score()
    {
        $team = factory('App\Team')->create();
        $game = factory('App\Game')->create([
            'team_home' => $team->id
        ]);
        $player = factory('App\Player')->create([
            'team_id' => $team->id
        ]);

        $game->attack($player);

        $game->attack($player);

        $this->assertDatabaseHas('goals', [
            'player_id' => $player->id,
            'game_id' => $game->id,
        ]);

        $this->assertEquals(2, \App\Goal::count());
    }

    /** @test */
    public function game_is_finished()
    {
        $game = factory('App\Game')->create([
            'created_at' => now()->addSeconds(244)
        ]);
        $this->assertTrue($game->finished);
    }

        /** @test */
    public function game_is_not_finished_yet()
    {
        $game = factory('App\Game')->create([
            'created_at' => now()
        ]);
        $this->assertFalse($game->finished);
    }

    /** @test */
    public function team1_vs_team2()
    {
        $team_home = factory('App\Team')->create();
        $team_home->player()->createMany([
            ['name' => 'Mohamed'],
            ['name' => 'John'],
            ['name' => 'Doe'],
            ['name' => 'Ahmed'],
            ['name' => 'Taylor']
        ]);

        $team_away = factory('App\Team')->create();
        $team_away->player()->createMany([
            ['name' => 'Mohamed2'],
            ['name' => 'John2'],
            ['name' => 'Doe2'],
            ['name' => 'Ahmed2'],
            ['name' => 'Taylor2']
        ]);

        $game = factory('App\Game')->create([
            'team_home' => $team_home->id,
            'team_away' => $team_away->id
        ]);

        while(! ($game->created_at->diffInSeconds(now()) > 20))
        {
            $game->attack($team_home->player[rand(0,4)]);
            sleep(rand(1,2));
            $game->attack($team_away->player[rand(0,4)]);
            sleep(rand(1,2));
        }

        $this->assertTrue(\App\Goal::count() > 5);
    }

    /** @test */
    public function get_players_from_game()
    {
        $team_home = factory('App\Team')->create();
        $team_home->player()->createMany([
            ['name' => 'Mohamed'],
            ['name' => 'John'],
            ['name' => 'Doe'],
            ['name' => 'Ahmed'],
            ['name' => 'Taylor']
        ]);

        $team_away = factory('App\Team')->create();
        $team_away->player()->createMany([
            ['name' => 'Mohamed2'],
            ['name' => 'John2'],
            ['name' => 'Doe2'],
            ['name' => 'Ahmed2'],
            ['name' => 'Taylor2']
        ]);

        $game = factory('App\Game')->create([
            'team_home' => $team_home->id,
            'team_away' => $team_away->id
        ]);

        $this->assertEquals($game->team_home()->player->pluck('name')->toArray(), [
            'Mohamed',
            'John',
            'Doe',
            'Ahmed',
            'Taylor'
        ]);

        $this->assertEquals($game->team_away()->player->pluck('name')->toArray(), [
            'Mohamed2',
            'John2',
            'Doe2',
            'Ahmed2',
            'Taylor2'
        ]);
    }
    /** @test */
    public function get_last_goal_attempt()
    {
        $team_home = factory('App\Team')->create();
        $team_home->player()->createMany([
            ['name' => 'Mohamed'],
            ['name' => 'John'],
            ['name' => 'Doe'],
            ['name' => 'Ahmed'],
            ['name' => 'Taylor']
        ]);

        $team_away = factory('App\Team')->create();
        $team_away->player()->createMany([
            ['name' => 'Mohamed2'],
            ['name' => 'John2'],
            ['name' => 'Doe2'],
            ['name' => 'Ahmed2'],
            ['name' => 'Taylor2']
        ]);

        $game = factory('App\Game')->create([
            'team_home' => $team_home->id,
            'team_away' => $team_away->id
        ]);

        // $this->assertNull($game->lastAttacker());


        $game->attack($team_away->player[0]);

        $this->assertTrue($game->team_away()->is($game->lastAttacker()));
        $this->assertEquals($team_away->id, $game->lastAttacker()->id);
    }
}
