@extends('layouts.master')

@php $disableNav = !isset($player); @endphp

@section('title')
    @if (isset($player))
        {{ trans('messages.edit') }} {{ $player->name }}
    @else
        {{ trans('messages.add_player') }}
    @endif
@endsection

@section('active_players', 'active')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ trans('messages.players') }}</li>
    <li class="breadcrumb-item active">
        @if (isset($player))
            {{ trans('messages.edit_player') }}
        @else
            {{ trans('messages.add_player') }}
        @endif
    </li>
@endsection

@section('body')
    <script>
        $(document).ready(function() {
            $("form[name='player']").submit(function(event) {
                var name = $("input[name='name']").val();
                var nickname = $("input[name='nickname']").val();
                var error = false;

                // Checks if the nickname field is blank
                if (nickname.trim() === "") {
                    error = true;
                    $("#invalid-nickname").text("The nickname is mandatory.");
                    event.preventDefault();
                    $("input[name='nickname']").focus();
                } else {
                    $("#invalid-nickname").text("");
                }

                // Checks if the name field is blank
                if (name.trim() === "") {
                    error = true;
                    $("#invalid-name").text("The name is mandatory.");
                    event.preventDefault();
                    $("input[name='name']").focus();
                } else {
                    $("#invalid-name").text("");
                }

                if(!error)
                {
                    // Makes the ajax call to verify that the name isn't already in the DB
                    var metodoHttp = $("input[name='_method']").val();
                    if(metodoHttp === undefined) // Creating a new player
                    {
                        $.ajax({
                            type: 'GET',
                            url: '/ajaxPlayer',
                            data: {name: name.trim()},
                            success: function(data) {
                                if(data.found)
                                {
                                    error = true;
                                    $("#invalid-name").text("The name already exists in the database.");
                                } else {
                                    $("form[name='player']")[0].submit();
                                }
                            }
                        });

                        return false;
                    }
                }
            });
        });

        function updateFileName() {
            const input = document.getElementById('avatar');
            const fileNameSpan = document.getElementById('file-chosen');
            fileNameSpan.textContent = input.files.length > 0 ? input.files[0].name : '{{ __("messages.no_file_selected") }}';
        }
    </script>

    <div class="container-fluid">
        <div class="row d-flex justify-content-between">
            <div class="col-md-8">
                @if (isset($player))
                    <form class="form-horizontal" name="player" method="post" enctype="multipart/form-data"
                        action="{{ route('player.update', ['player' => $player->id]) }}">
                        @method('PUT')
                @else
                    <form class="form-horizontal" name="player" method="post" enctype="multipart/form-data" action="{{ route('player.store') }}">
                @endif
                @csrf

                <!-- Player name -->
                <div class="form-group mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold">{{ trans('messages.name') }}:</h5>
                        </div>
                        <div class="col-md-6">
                            @if(isset($player))
                                <input class="form-control" type="text" name="name" placeholder="{{ trans('messages.name_holder') }}"
                                    value="{{ $player->name }}">
                            @else
                                <input class="form-control" type="text" name="name" placeholder="{{ trans('messages.name_holder') }}">
                            @endif
                            <span class="invalid-input" id="invalid-name"></span>
                        </div>
                    </div>
                </div>

                <!-- Player nickname -->
                <div class="form-group mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold">{{ trans('messages.nickname')}}:</h5>
                        </div>
                        <div class="col-md-6">
                            @if(isset($player))
                                <input class="form-control" type="text" name="nickname" placeholder="{{ trans('messages.nickname_holder') }}"
                                    value="{{ $player->nickname }}">
                            @else
                                <input class="form-control" type="text" name="nickname" placeholder="{{ trans('messages.nickname_holder') }}">
                            @endif
                            <span class="invalid-input" id="invalid-nickname"></span>
                        </div>
                    </div>
                </div>

                <!-- Player favorite role -->
                <div class="form-group mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold">{{ trans('messages.role') }}:</h5>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="favRole">
                                @foreach ($roles_list as $role)
                                    @php
                                        $isSelected = isset($player) && $player->favRole == $role;
                                    @endphp
                                    <option value="{{ $role }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ trans('messages.roles.' . Str::lower($role)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Player total games -->
                <div class="form-group mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold">{{ trans('messages.total_games') }}:</h5>
                        </div>
                        <div class="col-md-6">
                            @if(isset($player))
                                <input class="form-control" type="text" name="totGames" placeholder="Total number of gamees played"
                                    value="{{ $player->totGames }}" disabled>
                            @else
                                <input class="form-control" type="text" name="totGames" value="0" disabled>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Player total goals -->
                <div class="form-group mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold">{{ trans('messages.total_goals') }}:</h5>
                        </div>
                        <div class="col-md-6">
                            @if(isset($player))
                                <input class="form-control" type="text" name="totGoals" placeholder="Total number of goals scored"
                                    value="{{ $player->totGoals }}" disabled>
                            @else
                                <input class="form-control" type="text" name="totGoals" value="0" disabled>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Player goals per game -->
                <div class="form-group mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold">{{ trans('messages.goals_per_game') }}:</h5>
                        </div>
                        <div class="col-md-6">
                            @if(isset($player))
                                <input class="form-control" type="text" name="GoalsPerGame" placeholder="Number of goals per game"
                                    @if ($player->totGames !== 0)
                                        value="{{ number_format($player->totGoals/$player->totGames, 2) }}"
                                    @else
                                        value="0"
                                    @endif
                                disabled>
                            @else
                                <input class="form-control" type="text" name="GoalsPerGame" value="0" disabled>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Player avatar -->
            <div class="col-md-2">
                <div class="form-group mb-4">
                    <div class="row">
                        <h5 class="fw-bold">Avatar:</h5>
                        
                        @if (isset($player) && $player->avatar)
                            <img src="{{ asset('storage/' . $player->avatar) }}" alt="Avatar" style="max-width: 150px;">
                        @endif
                        
                        <div class="custom-file my-3 d-flex flex-column align-items-start">
                            <input type="file" class="form-control d-none" id="avatar" name="avatar" onchange="updateFileName()">
                            
                            <label for="avatar" class="btn btn-dark">
                                {{ trans('messages.choose_file') }}
                            </label>

                            <span id="file-chosen" class="mt-2 text-start">
                                {{ trans('messages.no_file') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save or create button -->
        <div class="form-group row mb-2">
            <div class="col-md-12">
                @if(isset($player))
                    <label for="mySubmit" class="btn btn-primary w-100"><i class="bi bi-floppy2-fill"></i>
                        {{ trans('messages.save') }}</label>
                    <input id="mySubmit" class="d-none" type="submit" value="Save">
                @else
                    <label for="mySubmit" class="btn btn-success w-100"><i class="bi bi-floppy2-fill"></i>
                        {{ trans('messages.create') }}</label>
                    <input id="mySubmit" class="d-none" type="submit" value="Create">
                @endif
            </div>
        </div>

        <!-- Cancel button -->
        @if(isset($player))
            <div class="form-group row">
                <div class="col-md-12">
                    <a class="btn btn-secondary w-100" href="{{ route('player.index') }}">
                        <i class="bi bi-box-arrow-left"></i> {{ trans('messages.cancel') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection