<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\LangController;

/*Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/
require __DIR__.'/auth.php';

Route::get('/lang/{lang}', [LangController::class, 'changeLanguage'])->name('setLang');

Route::middleware('lang')->group(function () {
    Route::get('/', [FrontController::class,'getHome'])->name('home');

    Route::middleware(['auth', 'hasNoPlayerYet'])->group(function () {
        Route::get('/player/create', [PlayerController::class, 'create'])->name('player.create');
        Route::post('/player', [PlayerController::class, 'store'])->name('player.store');
    });

    Route::middleware(['auth', 'isAdmin'])->group(function () {
        Route::get('/game/create', [GameController::class, 'create'])->name('game.create');
        Route::post('/game', [GameController::class, 'store'])->name('game.store');

        Route::get('/game/{game}/edit', [GameController::class, 'edit'])->name('game.edit');
        Route::put('/game/{game}', [GameController::class, 'update'])->name('game.update');

        Route::get('/game/{id}/destroy/confirm', [GameController::class, 'confirmDestroy'])->name('game.destroy.confirm');
        Route::delete('/game/{game}', [GameController::class, 'destroy'])->name('game.destroy');
    });

    Route::middleware(['auth', 'isRegisteredUser'])->group(function () {
        Route::get('/player/{player}/edit', [PlayerController::class, 'edit'])->name('player.edit');
        Route::put('/player/{player}', [PlayerController::class, 'update'])->name('player.update');

        Route::get('/ajaxPlayer', [PlayerController::class, 'ajaxCheckForPlayers']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('/game', [GameController::class, 'index'])->name('game.index');
        Route::get('/game/{game}', [GameController::class, 'show'])->name('game.show');

        Route::get('/player', [PlayerController::class, 'index'])->name('player.index');
        Route::get('/player/{player}', [PlayerController::class, 'show'])->name('player.show');
    });
});