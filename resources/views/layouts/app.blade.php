<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>daPos Cafe</title>

    <!-- Styles -->
    <script src="{{ asset('js/libs/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/libs/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/libs/moment.min.js') }}"></script>
    <script src="{{ asset('js/libs/bootstrap-datepicker.min.js') }}"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script>
      $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
      })
    </script>
    <link rel="stylesheet" href="{{ asset('css/preloader.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-16x16.png') }}" sizes="16x16">
    <script type="text/javascript" src="{{ asset('js/libs/jquery.preloader.min.js') }}"></script>
    <script src="{{ asset('js/libs/jquery-sortable.min.js') }}"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-static-top navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">

                  <!-- Collapsed Hamburger -->
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                      <span class="sr-only">Toggle Navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  </button>

                  <!-- Branding Image -->
                  <a class="navbar-brand" href="{{ url('/sales') }}">
                      <img src="{{ asset('images/logo-dapos.svg') }}" width="150" />
                  </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    @auth
                      <ul class="nav navbar-nav fs16">
                        <li><button type="button" class="btn btn-default" style="margin-top:8px;margin-left:10px" onclick="openSalesSummaryPopup();">Sales Summary</button></li>
                        <li><button type="button" class="btn btn-warning" style="margin-top:8px;margin-left:10px" onclick="dailyClosing();">Daily Closing</button></li>
                        <li><a>Cash: <span class="navboard" id="nav-cash">0</span> €</a></li>
                      </ul>
                    @endauth
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li><a style="font-weight:bold;">{{ Auth::user()->name }}</a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();localStorage.clear();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
        <div class="dapos-loading"></div>
    </div>

    <!-- Scripts -->
    @yield('scripts')
</body>
</html>
