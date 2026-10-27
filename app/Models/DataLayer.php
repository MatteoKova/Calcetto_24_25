<?php

namespace App\Models;

class DataLayer
{
    /**
     * Return the list of games.
     */
    public function listGames()
    {
        return Game::orderBy('date')->get();
    }

    /**
     * Return the list of locations.
     */
    public function listLocations()
    {
        return ["Lonato", "Castel Venzago", "Carpenedolo"];
    }

    /**
     * Return the game with the specified ID.
     */
    public function findGameById($id)
    {
        return Game::find($id);
    }

    /**
     * Add a new game in the database.
     */
    public function addGame($date, $time, $location)
    {
        $game = Game::create(['date'=>$date, 'time'=>$time, 'location'=>$location]);
        return $game->id;
    }

    /**
     * Edit the game with the specified ID, using the input parameters.
     */
    public function editGame($id, $date, $time, $location)
    {
        Game::find($id)->update(['date'=>$date, 'time'=>$time, 'location'=>$location]);
    }

    /**
     * Delete the game associated with the specified ID.
     */
    public function deleteGame($id) 
    {
        Game::find($id)->delete();
    }

    /**
     * Return the list of players.
     */
    public function listPlayers()
    {
        return Player::orderBy('name')->get();
    }

    /**
     * Return the list of roles.
     */
    public function listRoles()
    {
        return ["Attacker", "Center", "Defender", "Goalkeeper"];
    }

    /**
     * Return the player with the specified ID specifing the user ID.
     */
    public function findOwnPlayerById($id, $userID)
    {
        return Player::where('id', $id)->where('user_id', $userID)->first();
    }

    /**
     * Return the player with the specified ID.
     */
    public function findAnyPlayerById($id)
    {
        return Player::find($id);
    }
    
    /**
     * Add a new player in the database.
     */
    public function addPlayer($name, $nickname, $favRole, $userID, $avatarPath)
    {
        Player::create(['name'=>$name, 'nickname'=>$nickname, 'favRole'=>$favRole, 'user_id'=>$userID, 'avatar'=>$avatarPath]);
    }

    /**
     * Edit the player with the specified ID, using the input parameters.
     */
    public function editPlayer($id, $name, $nickname, $favRole, $avatarPath)
    {
        Player::find($id)->update(['name'=>$name, 'nickname'=>$nickname, 'favRole'=>$favRole, 'avatar'=>$avatarPath]);
    }

    /**
     * Delete the player associated with the specified ID.
     */
    public function deletePlayer($id) 
    {
        Player::find($id)->delete();
    }

    /**
     * Create the relationship between player and game
     */
    public function addPlayerToGame($gameId, $playerId, $teamNumber, $goals)
    {
        GamePlayer::create([
            'game_id' => $gameId,
            'player_id' => $playerId,
            'team_number' => $teamNumber,
            'goals' => $goals
        ]);
    }

    /**
     * Return (from the pivot table) the players that have played the specific game
     */
    public function getPlayersFromGame($id)
    {
        return GamePlayer::where('game_id', $id)->get();
    }

    /**
     * Remove (from the pivot table) the players that have played the specific game
     */
    public function removePlayersFromGame($id)
    {
        GamePlayer::where('game_id', $id)->delete();
    }

    /**
     * Update player statistics
     */
    public function updatePlayerStats($id, int $goalsDelta = 0, int $gamesDelta = 0)
    {
        $player = $this->findAnyPlayerById($id);

        if ($goalsDelta !== 0) {
            $player->increment('totGoals', $goalsDelta);
        }

        if ($gamesDelta !== 0) {
            $player->increment('totGames', $gamesDelta);
        }
    }

    /**
     * Return the games that the specified player has played
     */
    public function getPlayerIdGames($userID)
    {
        return Game::whereHas('players', function($query) use ($userID) {
            $query->where('player_id', $userID);
        })->pluck('id')->toArray();
    }

    /**
     * Returns true if the user with the specified email exists
     */
    public function findUserByemail($email) {
        $users = User::where('email', $email)->get();

        return count($users) == 0 ? false : true;
    }

    /**
     * Returns true if the user with the specified name exists
     */
    public function findPlayerByName($name) {
        $players = Player::where("name", $name)->get();

        return count($players) == 0 ? false : true;
    }
}