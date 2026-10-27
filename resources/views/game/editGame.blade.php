@extends('layouts.master')

@section('title')
    {{ isset($game) ? trans('messages.edit_game_id', ['id' => $game->id]) : trans('messages.add_game') }}
@endsection

@section('active_games', 'active')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ trans('messages.games') }}</li>
    <li class="breadcrumb-item active">{{ isset($game) ? trans('messages.edit_game') : trans('messages.add_game') }}</li>
@endsection

@section('body')
    <script>
        $(document).ready(function () {
            function updateDisabledOptions() {
                const selectedValues = [];

                // Gathers each form value
                $('.player-select').each(function () {
                    const val = $(this).val();
                    if (val) selectedValues.push(val);
                });

                // Enables every option before restrictions
                $('.player-select option').prop('disabled', false);

                // Disables options already selected
                $('.player-select').each(function () {
                    const currentSelect = $(this);
                    const currentVal = currentSelect.val();

                    currentSelect.find('option').each(function () {
                        const optVal = $(this).val();
                        if (optVal && optVal !== currentVal && selectedValues.includes(optVal)) {
                            $(this).prop('disabled', true);
                        }
                    });
                });
            }

            // Updates the disabled options when a selection changes
            $('.player-select').on('change', function () {
                updateDisabledOptions();
            });

            $('#form-horizontal').submit(function (event) {
                let error = false;
                let firstInvalid = null;
                let selectedPlayers = [];

                // Check if every form has a valid value
                $('.player-select').each(function () {
                    const $select = $(this);
                    const val = $select.val();
                    const $errorDiv = $select.closest('.row').find('#invalid-player');

                    $errorDiv.text('');

                    if (!val || val.trim() === '') {
                        $errorDiv.text('Select a player');
                        if (!firstInvalid) firstInvalid = $select;
                        error = true;
                    } else {
                        selectedPlayers.push(val);
                    }
                });

                if (error) {
                    event.preventDefault();
                    if (firstInvalid) firstInvalid.focus();
                }
            });

            updateDisabledOptions();
        });
    </script>

    <div class="container-fluid">
        <div class="row"> 
            <form id="form-horizontal" method="post"
                action="{{ isset($game) ? route('game.update', $game->id) : route('game.store') }}">
            @csrf
            @if (isset($game)) @method('PUT') @endif

            @if (isset($game))
                @php
                    $team1_totGoals = $team1->sum('goals');
                    $team2_totGoals = $team2->sum('goals');
                @endphp
            @endif      
            <div class="row">
                <!-- Title for team 1 -->
                <div class="col-md-4">
                    @if (isset($game))
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
                    @else
                        <h1 class="fw-bold mt-2 mb-4">
                            {{ trans('messages.team_1') }}
                        </h1>
                    @endif


                    <!-- Bold string for team 1 -->
                    <div class="row mb-2">
                        <div class="col-md-7">
                            <h5 class="fw-bold">{{ trans('messages.name_holder') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <h5 class="fw-bold">{{ trans('messages.goalsM') }}</h5>
                        </div>
                    </div>

                    <!-- Cicle for team 1 player name and goals -->
                    @for ($i = 0; $i < 5; $i++)
                        <div class="row align-items-start mb-3">
                            <div class="col-md-7">
                                <select class="form-control player-select" name="player_id_team1[]" data-index="{{ $i }}">
                                    <option value="">-- {{ trans('messages.select') }} --</option>
                                    @foreach ($players_list as $player)
                                        <option value="{{ $player->id }}"
                                            @if(isset($team1[$i]) && $team1[$i]->player_id == $player->id) selected @endif>
                                            {{ $player->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-input" id="invalid-player"></span>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="goals_team1[]"
                                    value="{{ isset($team1[$i]) ? $team1[$i]->goals : 0 }}" min="0">
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Title for team 2 -->
                <div class="col-md-4">
                    @if (isset($game))
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
                    @else
                        <h1 class="fw-bold mt-2 mb-4">
                            {{ trans('messages.team_2') }}
                        </h1>
                    @endif

                    <!-- Bold string for team 2 -->
                    <div class="row mb-2">
                        <div class="col-md-7">
                            <h5 class="fw-bold">{{ trans('messages.name_holder') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <h5 class="fw-bold">{{ trans('messages.goalsM') }}</h5>
                        </div>
                    </div>

                    <!-- Cicle for team 2 player name and goals -->
                    @for ($i = 0; $i < 5; $i++)
                        <div class="row align-items-start mb-3">
                            <div class="col-md-7">
                                <select class="form-control player-select" name="player_id_team2[]" data-index="{{ $i }}">
                                    <option value="">-- {{ trans('messages.select') }} --</option>
                                    @foreach ($players_list as $player)
                                        <option value="{{ $player->id }}"
                                            @if(isset($team2[$i]) && $team2[$i]->player_id == $player->id) selected @endif>
                                            {{ $player->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-input" id="invalid-player"></span>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="goals_team2[]"
                                    value="{{ isset($team2[$i]) ? $team2[$i]->goals : 0 }}" min="0">
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Space visible only on medium devices -->
                <div class="d-none d-md-block col-md-1"></div>

                <div class="col-12 col-md-3 mt-3">
                    <!-- Date -->
                    <div class="form-group mb-4">
                        <h5 class="fw-bold">{{ trans('messages.date') }}:</h5>
                        <input class="form-control" type="date" name="date"
                            value="{{ isset($game) ? $game->date : \Carbon\Carbon::now()->format('Y-m-d') }}">

                    </div>

                    <!-- Time -->
                    <div class="form-group mb-4">
                        <h5 class="fw-bold">{{ trans('messages.time') }}:</h5>
                        <input class="form-control" type="time" name="time"
                            value="{{ isset($game) ? \Carbon\Carbon::parse($game->time)->format('H:i') : \Carbon\Carbon::now()->format('H:i') }}">

                    </div>

                    <!-- Location -->
                    <div class="form-group mb-4">
                        <h5 class="fw-bold">{{ trans('messages.location') }}:</h5>
                            <select class="form-control" name="location">
                                @foreach ($locations_list as $location)
                                    <option value="{{ $location }}"
                                        @if (isset($game) && $game->location == $location) selected @endif>
                                        {{ $location }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Save or create button -->
            <div class="form-group row mt-3" style="margin-bottom: 10px">
                <div class="col-md-12">
                    <label for="submitBtn" class="btn btn-{{ isset($game) ? 'primary' : 'success' }} w-100">
                        <i class="bi bi-floppy2-fill"></i> {{ isset($game) ? trans('messages.save') : trans('messages.create') }}
                    </label>
                    <input id="submitBtn" type="submit" class="d-none">
                </div>
            </div>
            
            <!-- Cancel button -->
            <div class="form-group row">
                <div class="col-md-12">
                    <a class="btn btn-secondary w-100" href="{{ route('game.index') }}">
                        <i class="bi bi-box-arrow-left"></i> {{ trans('messages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection