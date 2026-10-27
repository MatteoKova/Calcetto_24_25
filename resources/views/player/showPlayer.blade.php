@extends('layouts.master')

@section('title')
    {{ $player->name }}
@endsection

@section('active_profile', 'active')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ trans('messages.players') }}</li>
    <li class="breadcrumb-item active">{{ trans('messages.show_player') }}</li>
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <!-- Player name -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ trans('messages.name') }}:</h3>
                    </div>
                    <div class="col-md-4">
                        <h3>{{ $player->name }}</h>
                    </div>
                </div>

                <!-- Player nickname -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ trans('messages.nickname') }}:</h3>
                    </div>
                    <div class="col-md-4">
                        <h3>{{ $player->nickname }}</h3>
                    </div>
                </div>

                <!-- Player favorite role -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ trans('messages.role') }}:</h3>
                    </div>
                    <div class="col-md-4">
                        <h3>{{ trans('messages.roles.' . Str::lower($player->favRole)) }}</h3>
                    </div>
                </div>

                <!-- Player total games -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ trans('messages.total_games') }}:</h3>
                    </div>
                    <div class="col-md-4">
                        <h3>{{ $player->totGames }}</h3>
                    </div>
                </div>

                <!-- Player total goals -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ trans('messages.total_goals') }}:</h3>
                    </div>
                    <div class="col-md-4">
                        <h3>{{ $player->totGoals }}</h3>
                    </div>
                </div>

                <!-- Player goals per game -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h3 class="fw-bold">{{ trans('messages.goals_per_game') }}:</h3>
                    </div>
                    <div class="col-md-4">
                        <h3>
                            @if ($player->totGames !== 0)
                                {{ number_format($player->totGoals / $player->totGames, 2) }}
                            @else
                                0
                            @endif
                        </h>
                    </div>
                </div>  
            </div>
            
            <!-- Player avatar -->
            <div class="col-md-3">
                <h3 class="fw-bold">Avatar:</h3>
            
            @if ($player->avatar)
                <img src="{{ asset('storage/' . $player->avatar) }}" alt="Avatar" class="rounded avatar-img">
            @endif
            </div>
        </div>

        <!-- Edit button for the right user -->
        @if($player_id !== null && $player->id == $player_id)
            <div style="margin-bottom: 10px">
                <a class="btn btn-primary w-100"
                href="{{ route('player.edit',['player'=>$player->id]) }}">
                <i class="bi bi-pencil-square"></i> {{ trans('messages.edit') }}</a>
            </div>
        @endif

        <!-- Go back button -->
        <div class="form-group row mb-3">
            <div class="col-md-12">
                <a class="btn btn-secondary w-100"
                href="{{ route('player.index') }}">
                <i class="bi bi-box-arrow-left"></i> {{ trans('messages.go_back_button') }}</a>
            </div>
        </div>
    </div>
@endsection
