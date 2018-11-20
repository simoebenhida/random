<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            'Warriors' => [
                'Jordan Bell',
                'Quinn Cook',
                'Demarkcus Cousins',
                'Stephen Curry',
                'Marcus Derrickson'
            ],
            'Lakers' => [
                'Lonzo Ball',
                'Michael Beasley',
                'Isaac Bonga',
                'Lebron James',
                'Josh Hart'
            ],
            'Cleveland' => [
                'Jordan Clarkson',
                'Sam Dekker',
                'Channing Frye',
                'Andrew Harrison',
                'George Hill'
            ],
        ];

        collect($teams)->each(function($item, $key) {
            $team = factory('App\Team')->create([
                'name' => $key
            ]);
            collect($item)->each(function($item) use($team) {
                $team->player()->create([
                    'name' => $item
                ]);
            });
        });

        //Warriors Vs Lakers
        \App\Team::find(1)->against(\App\Team::find(2));

        //Warriors Vs Cleveland
        \App\Team::find(1)->against(\App\Team::find(3));

        //Lakers Vs Cleveland
        \App\Team::find(2)->against(\App\Team::find(3));
    }
}
