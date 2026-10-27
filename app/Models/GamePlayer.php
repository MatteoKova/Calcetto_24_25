<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    protected $table = 'game_player';

    protected $fillable = ['game_id', 'player_id', 'team_number', 'goals'];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function match()
    {
        return $this->belongsTo(Game::class);
    }
}
