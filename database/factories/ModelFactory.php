<?php

use App\Game;
use App\Player;
use App\Team;
use Faker\Generator as Faker;

$factory->define(Team::class, function (Faker $faker) {
    return [
        'name' => $faker->city
    ];
});

$factory->define(Player::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'team_id' => function() {
            return factory('App\Team')->create()->id;
        }
    ];
});

$factory->define(Game::class, function (Faker $faker) {
    return [
        'team_home' => function() {
            return factory('App\Team')->create()->id;
        },
        'team_away' => function() {
            return factory('App\Team')->create()->id;
        }
    ];
});
