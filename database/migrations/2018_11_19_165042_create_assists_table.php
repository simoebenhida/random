<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assist')->default(0);
            $table->unsignedInteger('player_id');
            $table->unsignedInteger('game_id');

            $table->foreign('player_id')
            ->references('id')->on('players')
            ->onDelete('cascade');

            $table->foreign('game_id')
            ->references('id')->on('games')
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
        Schema::dropIfExists('assists');
    }
}
