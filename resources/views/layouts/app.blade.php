<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Manager') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/vex.combined.min.js') }}"></script>
    <script>vex.defaultOptions.className = 'vex-theme-os'</script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/vex.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/vex-theme-os.css') }}"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}"/>
</head>
<body>
<div id="app">

    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Manager') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('search') }}"><i class="fas fa-search"></i> Search</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('companies.index') }}"><i class="fas fa-building"></i> My Companies</a></li>
                    <li class="nav-item"> <a class="nav-link"  href="{{ route('projects.index') }}"><i class="fas fa-briefcase"></i> My Projects</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('tasks.index') }}"><i class="fas fa-tasks"></i> My Tasks</a></li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre><i class="fas fa-user-circle"></i>
                            Social <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            @if (!empty(Auth::user()->id))
                                <a class="dropdown-item" href="/users/{{ Auth::user()->id }}"><i class="fas fa-id-badge"></i> My Profile</a>
                                <a class="dropdown-item" href="/friends"><i class="fas fa-user-friends"></i> My Friends</a>
                            @endif
                            <a class="dropdown-item" href="/messages"><i class="fas fa-envelope-open-text"></i> My Messages</a>
                            <a class="dropdown-item" href="/messages/create"><i class="fas fa-reply"></i> New Message</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        @if (!empty(Auth::user()->name))
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre><i class="fas fa-id-badge"></i>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                        @endif
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            @if (!empty(Auth::user()->role_id) && Auth::user()->role_id == 1)
                                <a class="dropdown-item" href="{{ route('admin.companies') }}"><i class="fas fa-building"></i> All Companies</a>
                                <a class="dropdown-item" href="{{ route('admin.projects') }}"><i class="fas fa-briefcase"></i> All Projects</a>
                                <a class="dropdown-item" href="{{ route('admin.tasks') }}"><i class="fas fa-tasks"></i> All Tasks</a>
                                <a class="dropdown-item" href="{{ route('admin.comments') }}"><i class="fas fa-comments"></i> All Comments</a>
                                <a class="dropdown-item" href="{{ route('admin.users') }}"><i class="fas fa-user-tag"></i> All Users</a>
                            @endif

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> {{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> {{ __('Register') }}</a>
                            </li>
                        @endif
                    @endguest
                </ul>

            </div>
        </div>
    </nav>
</div>

<main class="py-4">
    <div class="container">
        @include('partials.errors')
        @include('partials.success')
        <div class="row">
            @yield('content')
        </div>
    </div>
</main>

</body>
</html>
