<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataLayer;
use Illuminate\Support\Facades\Redirect;
use Storage;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dl = new DataLayer();
        $playersList = $dl->listPlayers();

        $playerId = auth()->user()->player->id ?? null;

        return view('player.players')->with('players_list',$playersList)->with('player_id', $playerId);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dl = new DataLayer();
        $rolesList = $dl->listRoles();
        return view('player.editPlayer')->with('roles_list', $rolesList);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $dl = new DataLayer();
        $dl->addPlayer($request->input('name'),
        $request->input('nickname'),
        $request->input('favRole'),
        auth()->id(),
        $avatarPath
        );

        return Redirect::to(route('player.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dl = new DataLayer();
        $player = $dl->findAnyPlayerById($id);

        if ($player !== null) {
            $currentUserPlayerId = auth()->user()->player->id ?? null;

            return view('player.showPlayer')->with('player', $player)->with('player_id', $currentUserPlayerId);
        } else {
            return view('errors.wrongID')->with('message', 'Wrong player ID');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dl = new DataLayer();
        $rolesList = $dl->listRoles();
        $user_id = auth()->id();
        $player = $dl->findOwnPlayerById($id, $user_id);

        if($player !== null)
            return view('player.editPlayer')->with('roles_list', $rolesList)->with('player',$player);
        else
            return view('errors.wrongID')->with('message', 'Wrong player ID');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dl = new DataLayer();
        $player = $dl->findAnyPlayerById($id);

        $avatarPath = $player->avatar ?? null;

        if ($request->hasFile('avatar')) {
            if ($player->avatar) {
                Storage::disk('public')->delete($player->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $dl->editPlayer($id, 
        $request->input('name'), 
        $request->input('nickname'), 
        $request->input('favRole'),
        $avatarPath
        );

        return Redirect::to(route('player.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dl = new DataLayer();
        $dl->deletePlayer($id);
        return Redirect::to(route('player.index'));
    }

    /**
     * Show the message to confirm resource removal.
     */
    public function confirmDestroy($id) {
        $dl = new DataLayer();
        $user_id = auth()->id();
        $player = $dl->findOwnPlayerById($id, $user_id);
        return view('player.deletePlayer')->with('player', $player);
    }

    /**
     * Checks if there is a player with a specific name in the DB
     */
    public function ajaxCheckForPlayers(Request $request) {
        $dl = new DataLayer();

        $response = $dl->findPlayerByName($request->input('name')) ? ['found'=>true] : ['found'=>false];

        return response()->json($response);
    }
}