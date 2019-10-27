<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Acme Evidences</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/fileinput.js') }}" ></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sticky-footer-navbar/">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    <link href="{{ asset('css/fileinput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sticky-footer-navbar.css') }}" rel="stylesheet">


    <!-- Editor -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>

    <script src="https://kit.fontawesome.com/ed0f6c0c4d.js" crossorigin="anonymous"></script>

    <!-- Selectors -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

    <!-- Search -->
    <script src="{{ asset('js/search.js') }}"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>

    <div id="app">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/home">Acme Evidences

            @guest

            @else
                @if(Auth::user()->is_administrator == 1)
                    <span class="badge badge-warning">Admin</span>
                @endif
            @endif
            </a>



            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    @guest

                        <ul class="navbar-nav mr-auto">

                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registro') }}</a>
                                </li>
                            @endif
                        </ul>

                    @else
                        <ul class="navbar-nav mr-auto">

                            <li class="nav-item  {{ (request()->is('home')) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                            </li>

                            @if(Auth::user()->is_administrator == 1)

                                <li class="nav-item {{ (request()->is('evidences/*')) ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('evidences.all') }}">{{ __('Todas evidencias') }}</a>
                                </li>

                            @else

                                <li class="nav-item {{ (request()->is('evidences/list')) || (request()->is('evidences/view/*')) ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('evidences.list') }}">{{ __('Mis evidencias') }}</a>
                                </li>

                                <li class="nav-item {{ (request()->is('evidences/new')) ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('evidences.new') }}">{{ __('Crear evidencia') }}</a>
                                </li>

                                @if(Auth::user()->is_comite == 1)

                                    <li class="nav-item {{ (request()->is('evidences/comite')) || (request()->is('evidences/comite/*')) || (request()->is('evidences/comite/view/*')) ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('evidences.comite') }}">{{ __('Evidencias de mi comité') }}</a>
                                    </li>

                                    <li class="nav-item {{ (request()->is('meetings/*')) ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('meetings.main') }}">{{ __('Reuniones de mi comité') }}</a>
                                    </li>

                                @endif

                            @endif



                        </ul>

                        <ul class="nav navbar-nav navbar-right">

                            <li class="nav-item dropdown right">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} {{ Auth::user()->surname }} ({{ Auth::user()->uvus }})<span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('account') }}">
                                        {{ __('Mi cuenta') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                        {{ __('Salir') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                        </ul>

                    @endguest





            </div>
        </nav>

        <main class="py-4 container">
            @yield('content')
        </main>

    </div>



</body>
</html>
