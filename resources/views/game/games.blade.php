@extends('layouts.master')

@section('title', trans('messages.history'))

@section('active_games', 'active')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ trans('messages.games') }}</li>
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Pagination method -->
            <div class="col-md-6">
                <nav aria-label="Page navigation example" id="paginationNav">
                    <ul class="pagination">
                        <li class="page-item" id="previousPage"><a class="page-link" href="#">{{ trans('messages.previous') }}</a></li>
                        <li class="page-item" id="nextPage"><a class="page-link" href="#">{{ trans('messages.next') }}</a></li>
                        <li>
                            <select id="rowsPerPage" class="form-control justify-content-end">
                                <option value="5">{{ trans('messages.5_games') }}</option>
                                <option value="10">{{ trans('messages.10_games') }}</option>
                                <option value="15">{{ trans('messages.15_games') }}</option>
                                <option value="20">{{ trans('messages.20_games') }}</option>
                            </select>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="col-md-6">
                <!-- Create button for admin -->
                @if(auth()->user()->role === 'admin')
                    <div class="col-xs-6 d-flex mb-2 justify-content-end">
                        <p>
                            <a class="btn btn-success" 
                                href="{{ route('game.create') }}">
                                <i class="bi bi-database-add"></i> 
                                {{ trans('messages.new_game') }}
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Games list -->
        @foreach ($games_list as $game)
            <div class="row mb-3 list-row">
                <div class="col-md-12">
                    @php
                        $btnClass = in_array($game->id, $played_game_ids) ? 'btn-warning' : 'btn-dark';
                    @endphp
                    <a class="btn {{ $btnClass }} w-100 d-flex justify-content-between align-items-center text-start"
                        href="{{ route('game.show', $game->id) }}" 
                        style="font-size: 24px">
                        <span>{{ trans('messages.game_name') }} <strong>{{ $game->date }}</strong></span>
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection