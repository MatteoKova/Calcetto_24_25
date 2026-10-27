@extends('layouts.master')

@section('title')
    {{ trans('messages.game') }} {{ $game->id }}
@endsection

@section('active_games', 'active')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ trans('messages.games') }}</li>
    <li class="breadcrumb-item active">{{ trans('messages.show_game') }}</li>
@endsection

@section('body')
    <div class="container-fluid">
        @php
            $team1_totGoals = $team1->sum('goals');
            $team2_totGoals = $team2->sum('goals');
        @endphp

        <div class="row">
            <!-- Title for team 1 -->
            <div class="col-md-4">
                @php
                    if ($team1_totGoals > $team2_totGoals) {
                        $status = trans('messages.won');
                        $class = 'text-success';
                    } elseif ($team1_totGoals < $team2_totGoals) {
                        $status = trans('messages.lost');
                        $class = 'text-danger';
                    } else {
                        $status = trans('messages.tied');
                        $class = 'text-secondary';
                    }
                @endphp

                <h1 class="fw-bold mt-2 mb-1 {{ $class }}">
                    {{ trans('messages.team_1') }}
                </h1>
                <h2 class="fw-bold mt-2 mb-1 {{ $class }}">
                    {{ $status }}
                </h2>
                <h2 class="fw-bold mb-4 {{ $class }}">
                    {{ trans('messages.with') }} {{ $team1_totGoals }} {{ trans('messages.goals') }}
                </h2>

                <!-- Bold string for team 1 -->
                <div class="row mb-2">
                    <div class="col-md-7">
                        <h3 class="fw-bold">{{ trans('messages.name_holder') }} - {{ trans('messages.goalsM') }}</h3>
                    </div>
                </div>

                <!-- Cicle for team 1 player name and goals -->
                @foreach ($team1 as $participant)
                    <h4 class="mb-3"><strong>{{ $participant->player->name }}</strong> - {{ $participant->goals }} {{ $participant->goals == 1 ? trans('messages.goal') : trans('messages.goals') }}</h4>
                @endforeach
            </div>

            <!-- Title for team 2 -->
            <div class="col-md-4">
                @php
                    if ($team2_totGoals > $team1_totGoals) {
                        $status = trans('messages.won');
                        $class = 'text-success';
                    } elseif ($team2_totGoals < $team1_totGoals) {
                        $status = trans('messages.lost');
                        $class = 'text-danger';
                    } else {
                        $status = trans('messages.tied');
                        $class = 'text-secondary';
                    }
                @endphp

                <h1 class="fw-bold mt-2 mb-1 {{ $class }}">
                    {{ trans('messages.team_2') }}
                </h1>
                <h2 class="fw-bold mt-2 mb-1 {{ $class }}">
                    {{ $status }}
                </h2>
                <h2 class="fw-bold mb-4 {{ $class }}">
                    {{ trans('messages.with') }} {{ $team2_totGoals }} {{ trans('messages.goals') }}
                </h2>

                <!-- Bold string for team 2 -->
                <div class="row mb-2">
                    <div class="col-md-7">
                        <h3 class="fw-bold">{{ trans('messages.name_holder') }} - {{ trans('messages.goalsM') }}</h3>
                    </div>
                </div>

                <!-- Cicle for team 2 player name and goals -->
                @foreach ($team2 as $participant)
                    <h4 class="mb-3"><strong>{{ $participant->player->name }}</strong> - {{ $participant->goals }} {{ $participant->goals == 1 ? trans('messages.goal') : trans('messages.goals') }}</h4>
                @endforeach
            </div>
        
            <div class="col-md-4 mt-2 ms-auto text-end">
                <!-- Date -->
                <div class="mb-5">
                    <h3 class="fw-bold">{{ trans('messages.date') }}:</h3>
                    <h4>{{ $game->date }}</h4>
                </div>

                <!-- Time -->
                <div class="mb-5">
                    <h3 class="fw-bold">{{ trans('messages.time') }}:</h3>
                    <h4>{{ \Carbon\Carbon::parse($game->time)->format('H:i') }}</h4>
                </div>

                <!-- Location -->
                <div class="mb-5">
                    <h3 class="fw-bold">{{ trans('messages.location') }}:</h3>
                    <h4>{{ $game->location }}</h4>
                </div>
            </div>
        </div>

        <!-- Edit and Delete buttons for admin -->
        <div class="row mt-3">
            @if(auth()->user()->role === 'admin')
                <div class="col-md-4 mb-2 mb-md-0">
                    <a class="btn btn-primary w-100"
                    href="{{ route('game.edit', ['game'=>$game->id]) }}">
                        <i class="bi bi-pencil-square"></i> {{ trans('messages.edit') }}</a>
                </div>

                <div class="col-md-4 mb-2 mb-md-0">
                    <a class="btn btn-danger w-100"
                    href="{{ route('game.destroy.confirm', ['id'=>$game->id]) }}">
                        <i class="bi bi-trash"></i> {{ trans('messages.delete_button') }}</a>
                </div>
            @endif

            <!-- Go back button -->
            @php
                $btnWidth = auth()->user()->role === 'admin' ? 'col-md-4' : 'col-md-12';
            @endphp
            <div class="{{ $btnWidth }} mb-2 mb-md-0">
                <a class="btn btn-secondary w-100"
                href="{{ route('game.index') }}">
                    <i class="bi bi-box-arrow-left"></i> {{ trans('messages.go_back_button') }}</a>
            </div>
        </div>
    </div>
@endsection