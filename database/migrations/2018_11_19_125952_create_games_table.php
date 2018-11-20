<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *  - Play Game
        - Begin the game
        - But Score

     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_home');
            $table->unsignedInteger('team_away');
            $table->integer('score_home')->default(0);
            $table->integer('score_away')->default(0);

            $table->foreign('team_home')
            ->references('id')->on('teams')
            ->onDelete('cascade');

            $table->foreign('team_away')
            ->references('id')->on('teams')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
