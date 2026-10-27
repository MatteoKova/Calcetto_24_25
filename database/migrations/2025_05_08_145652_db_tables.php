<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Players table
        Schema::create('player', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('nickname');
            $table->string('favRole');
            $table->unsignedInteger('totGames')->default(0);
            $table->unsignedInteger('totGoals')->default(0);
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        // Games table
        Schema::create('game', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('location');
            $table->time('time');
            $table->timestamps();
        });

        // Pivot table
        Schema::create('game_player', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('player_id');
            $table->unsignedTinyInteger('team_number');
            $table->unsignedInteger('goals')->default(0);
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('game')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('player')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('game_player');
        Schema::dropIfExists('game');
        Schema::dropIfExists('player');
    }
};
