@extends('layouts.master')

@section('title', trans('messages.delete_game'))

@section('active_games', 'active')

@section('breadcrumb')
<div class="container-fluid d-flex justify-content-end">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">{{ trans('messages.games') }}</li>
                <li class="breadcrumb-item active">{{ trans('messages.delete_game') }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('body')
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-md-12">
                <header>
                    <h1>
                        {{ trans('messages.delete_game_id', ['id' => $game->id]) }}
                    </h1>
                </header>
                <p class="confirm">
                    {{ trans('messages.delete_confirm') }}
                </p>
            </div>
        </div>
    </div>

    <div class="container-fluid text-center">
        <div class="row">
            <!-- Cancel elimination -->
            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-header">
                        {{ trans('messages.revert') }}
                    </div>
                    <div class="card-body">
                        <p>
                            {!! trans('messages.revert_warning') !!}
                        </p>
                        <a class="btn btn-secondary" href="{{ route('game.index') }}"><i class="bi bi-box-arrow-left"></i> {{ trans('messages.cancel') }}</a>
                    </div>
                </div>
            </div>

            <!-- Confirm elimination -->
            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-header">
                        {{ trans('messages.confirm') }}
                    </div>
                    <div class="card-body">
                        <p>
                            {!! trans('messages.delete_warning') !!}
                        </p>
                        <form name="game.delete" method="post" action="{{ route('game.destroy', ['game'=>$game->id]) }}">
                            @method('DELETE')
                            @csrf
                            <label for="mySubmit" class="btn btn-danger"><i class="bi bi-trash"></i> {{ trans('messages.delete_button') }}</label>
                            <input id="mySubmit" class="d-none" type="submit" value="Create">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection