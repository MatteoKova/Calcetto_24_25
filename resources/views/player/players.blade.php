@extends('layouts.master')

@section('title', trans('messages.players_list'))

@section('active_players', 'active')

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ trans('messages.players') }}</li>
@endsection

@section('body')
    <script>
        $(document).ready(function () {
            $('#sortPlayers').on('change', function () {
                const sortKey = $(this).val();
                const $rows = $('.list-row');
                const rowsArray = $rows.get(); // ottieni array JS puro per sort

                // Ordina i cloni per evitare side effects
                const sortedRows = rowsArray.sort(function (a, b) {
                    const valA = $(a).data(sortKey.toLowerCase());
                    const valB = $(b).data(sortKey.toLowerCase());

                    if (typeof valA === 'string') {
                        return valA.localeCompare(valB);
                    } else {
                        return valB - valA;
                    }
                });

                // Ripopola la lista ordinata
                $('#playerList').html(sortedRows);

                // Mostra/nasconde statistiche
                $('.list-row').each(function () {
                    const $row = $(this);
                    const $statSpan = $row.find('.sort-stat');

                    if (sortKey === 'name') {
                        $statSpan.text('');
                    } else {
                        const value = $row.data(sortKey.toLowerCase());
                        const formatted = typeof value === 'number'
                            ? value.toFixed(2).replace(/\.00$/, '')
                            : value;
                        $statSpan.text(` · ${formatted}`);
                    }
                });

                // Reinizzializza la paginazione dopo l’ordinamento
                if (typeof initPagination === 'function') {
                    initPagination();
                }
            });
        });
    </script>

    <div class="container-fluid">
        <!-- Sorting method -->
        <div class="row mb-3">
            <!-- Pagination method -->
            <div class="col-md-6">
                <nav aria-label="Page navigation example" id="paginationNav">
                    <ul class="pagination">
                        <li class="page-item" id="previousPage"><a class="page-link" href="#">{{ trans('messages.previous') }}</a></li>
                        <li class="page-item" id="nextPage"><a class="page-link" href="#">{{ trans('messages.next') }}</a></li>
                        <li>
                            <select id="rowsPerPage" class="form-control justify-content-end">
                                <option value="5">{{ trans('messages.5_players') }}</option>
                                <option value="10">{{ trans('messages.10_players') }}</option>
                                <option value="15">{{ trans('messages.15_players') }}</option>
                                <option value="20">{{ trans('messages.20_players') }}</option>
                            </select>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3 offset-2">
                        <label for="sortPlayers" class="form-label fw-bold">{{ trans('messages.order') }}</label>
                    </div>
                    <div class="col-md-7">
                        <select id="sortPlayers" class="form-select">
                            <option value="name">{{ trans('messages.name') }}</option>
                            <option value="favRole">{{ trans('messages.role') }}</option>
                            <option value="totGames">{{ trans('messages.total_games') }}</option>
                            <option value="totGoals">{{ trans('messages.total_goals') }}</option>
                            <option value="totGoalsPerGame">{{ trans('messages.goals_per_game') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Players list -->
        <div id="playerList">
            @foreach ($players_list as $player)
                @php
                    $btnClass = $player->id == $player_id ? 'btn-warning' : 'btn-dark';
                    $isCurrent = $player->id === auth()->user()->player?->id;
                    $name = $isCurrent ? "$player->name (You)" : $player->name;
                @endphp

                <div class="row mb-3 list-row"
                    data-name="{{ strtolower($player->name) }}"
                    data-favRole="{{ strtolower($player->favRole) }}"
                    data-totGoals="{{ $player->totGoals }}"
                    data-totGames="{{ $player->totGames }}"
                    data-totGoalsPerGame="{{ $player->totGames == 0 ? 0 : $player->totGoals / $player->totGames }}">
                    <div class="col-md-12">
                        <a class="btn {{ $btnClass }} w-100 d-flex justify-content-between align-items-center text-start"
                           href="{{ route('player.show', $player->id) }}"
                           style="font-size: 24px">
                            <span>
                                {{ $name }}
                                <span class="sort-stat fw-normal" style="font-size: 16px;"></span>
                            </span>
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection