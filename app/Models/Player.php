<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    protected $table = 'player';
    use HasFactory;

    protected $fillable=['name', 'nickname', 'favRole', 'user_id', 'avatar'];

    public function gameParticipations()
    {
        return $this->hasMany(GamePlayer::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_player')
                    ->withPivot('team_number', 'goals')
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}