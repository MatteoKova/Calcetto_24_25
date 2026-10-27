<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, 
              user-scalable=no">

        <!-- Fogli di stile -->
        <link rel="stylesheet" href="{{ url('/') }}/css/bootstrap.min.css">
        <link href="{{ url('/') }}/css/style.css" rel="stylesheet">

        <!-- jQuery e plugin JavaScript  -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="{{ url('/') }}/js/bootstrap.min.js"></script>

        <!-- Custom jQuery and Javascript scripts -->
        <script src="{{ url('/') }}/js/paginationScript.js"></script>

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>

    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="navbar-brand @yield('active_home') {{ isset($disableNav) && $disableNav ? 'disabled-link' : '' }}" 
                        href="{{ route('home') }}"> {{ trans('messages.games') }}</a>
                  </li>
                  
                  @if(auth()->check())
                    <!-- Games button for logged users -->
                    <li class="nav-item">
                        <a class="nav-link @yield('active_games') {{ isset($disableNav) && $disableNav ? 'disabled-link' : '' }}" 
                        href="{{ route('game.index') }}"><i class="bi bi-clock-history"></i> {{ trans('messages.games') }}</a>
                    </li>

                    <!-- Players button for logged users -->
                    <li class="nav-item">
                        <a class="nav-link @yield('active_players') {{ isset($disableNav) && $disableNav ? 'disabled-link' : '' }}" 
                        href="{{ route('player.index') }}"><i class="bi bi-person"></i> {{ trans('messages.players') }}</a>
                    </li>
                  @endif

                  <!-- Language buttons -->
                  <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> {{ trans('messages.language') }}</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('setLang', ['lang' => 'en']) }}"><span>{{ trans('messages.english') }}</span><img src="{{ url('/') }}/img/flags/en.png" width="35"/></a></li>
                            <li><a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('setLang', ['lang' => 'it']) }}"><span>{{ trans('messages.italian') }}</span><img src="{{ url('/') }}/img/flags/it.png" width="35"/></a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <!-- Logout button for logged users -->
                    @if (auth()->check())
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf

                                <button type="submit" class="btn btn-danger {{ isset($disableNav) && $disableNav ? 'disabled-link' : '' }}" 
                                        {{ isset($disableNav) && $disableNav ? 'disabled' : '' }}>
                                    {{ auth()->user()->name }}
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </li>

                    <!-- Login/Register button for non-logged users -->
                    @else
                        <li class="nav-item">
                            <a class="btn btn-primary" 
                            href="{{ route('login') }}">
                            <i class="bi bi-person-lock"></i>
                             {{ trans('messages.access') }}
                            </a>
                        </li>
                    @endif
                </ul>
              </div>
            </div>
        </nav>

        <!-- Breadcrumb -->
        <div class="container-fluid d-flex justify-content-end">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        <!-- Title -->
        <div class="container-fluid">
            <header class="header-sezione">
                <h1>
                    @yield('title')
                </h1>
            </header>
        </div>

        <!-- Body -->
        @yield('body')
    </body>
</html>