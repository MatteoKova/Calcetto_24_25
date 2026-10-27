<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    protected $table = 'game';
    use HasFactory;

    protected $fillable=['date','time','location'];

    public function gameParticipations()
    {
        return $this->hasMany(GamePlayer::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'game_player')
                    ->withPivot('team_number', 'goals')
                    ->withTimestamps();
    }
}