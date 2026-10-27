<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataLayer;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Facades\Redirect;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dl = new DataLayer();
        $gamesList = $dl->listGames();

        $user = auth()->user();

        if($user->role === 'admin') {
            $playedGameIds = [];
        } else {
            $playerId = $user->player->id ?? null;
            $playedGameIds = $playerId ? $dl->getPlayerIdGames($playerId) : [];
        }

        return view('game.games', ['games_list' => $gamesList, 'played_game_ids' => $playedGameIds]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $players_list = Player::orderBy('name')->get();
        $dl = new DataLayer();
        $locations_list = $dl->listLocations();
        return view('game.editGame', compact('players_list', 'locations_list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dl = new DataLayer();
        $gameId = $dl->addGame($request->input('date'), $request->input('time'), $request->input('location'));

        $team1Players = $request->input('player_id_team1', []);
        $team1Goals = $request->input('goals_team1', []);

        foreach ($team1Players as $index => $playerId) {
            $goals = $team1Goals[$index] ?? 0;
            $dl->addPlayerToGame($gameId, $playerId, 1, $goals);

            $dl->updatePlayerStats($playerId, $goals, 1);
        }

        $team2Players = $request->input('player_id_team2', []);
        $team2Goals = $request->input('goals_team2', []);

        foreach ($team2Players as $index => $playerId) {
            $goals = $team2Goals[$index] ?? 0;
            $dl->addPlayerToGame($gameId, $playerId, 2, $goals);
            
            $dl->updatePlayerStats($playerId, $goals, 1);
        }
        
        return Redirect::to(route('game.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $game = Game::with(['gameParticipations.player'])->findOrFail($id);

        $team1 = $game->gameParticipations->where('team_number', 1);
        $team2 = $game->gameParticipations->where('team_number', 2);

        if ($game !== null) {
            return view('game.showGame', compact('game', 'team1', 'team2'));
        } else {
            return view('errors.wrongID')->with('message', 'Wrong game ID');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $game = Game::with('gameParticipations.player')->findOrFail($id);
        $players_list = Player::orderBy('name')->get();
        $dl = new DataLayer();
        $locations_list = $dl->listLocations();

        $team1 = $game->gameParticipations->where('team_number', 1)->values();
        $team2 = $game->gameParticipations->where('team_number', 2)->values();

        return view('game.editGame', compact('game', 'players_list', 'locations_list', 'team1', 'team2'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dl = new DataLayer();
        $dl->editGame($id, $request->input('date'), $request->input('time'), $request->input('location'));
        
        // Remove all the players that played this game (to update completely)
        $dl->removePlayersFromGame($id);
        
        $team1Players = $request->input('player_id_team1', []);
        $team1Goals = $request->input('goals_team1', []);

        foreach ($team1Players as $index => $playerId) {
            $goals = $team1Goals[$index] ?? 0;
            if ($playerId) {
                $dl->addPlayerToGame($id, $playerId, 1, $goals);
                
                $goalDelta = $goals - $dl->findAnyPlayerById($playerId)->totGoals;
                $dl->updatePlayerStats($playerId, $goalDelta, 0);
            }
        }

        $team2Players = $request->input('player_id_team2', []);
        $team2Goals = $request->input('goals_team2', []);

        foreach ($team2Players as $index => $playerId) {
            $goals = $team2Goals[$index] ?? 0;
            if ($playerId) {
                $dl->addPlayerToGame($id, $playerId, 2, $goals);

                $goalDelta = $goals - $dl->findAnyPlayerById($playerId)->totGoals;
                $dl->updatePlayerStats($playerId, $goalDelta, 0);
            }
        }

        return Redirect::to(route('game.index'));
    }

    /**
     * Show the message to confirm resource removal.
     */
    public function confirmDestroy($id) 
    {
        $dl = new DataLayer();
        $game = $dl->findGameById($id);
        if($game !== null)
        {
            return view('game.deleteGame')->with('game', $game);
        } else {
            return view('errors.wrongID')->with('message', 'Wrong game ID');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dl = new DataLayer();

        $gamePlayers = $dl->getPlayersFromGame($id);

        foreach ($gamePlayers as $gp) {
            $dl->updatePlayerStats($gp->player_id, -$gp->goals, -1);
        }

        $dl->removePlayersFromGame($id);

        $dl->deleteGame($id);
        return Redirect::to(route('game.index'));
    }
}