<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('page_title','')</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta property="og:url" content="{{ url()->full() }}">
        <meta property="og:type" content="website">

        @yield('metas')
        <link rel="stylesheet" href="/vendor/cscms/css/frontend/app.css">
        <style type="text/css">
            body {
                margin-top:5rem;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ route('frontend.index') }}">{{ config('app.name') }}</a>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    @if (Auth::check())
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('frontend.home') }}">Home <span class="sr-only">(current)</span></a>
                    </li>
                    @endif
                    <!--<li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Disabled</a>
                    </li>-->
                </ul>
                <ul class="navbar-nav mt-2 mt-md-0">
                    @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user"></span> {{ Auth::user()->name }}</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('frontend.profile') }}">Profile</a>
                            <a class="dropdown-item" href="{{ route('frontend.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

                            <form id="logout-form" action="{{ route('frontend.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.register') }}">Register</a>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>

        @include('cscms::frontend.default.partials.banner',[
            'success' => $success,
            'error' => $error,
            'csrf_error' => $csrf_error,
        ])

        <div class="container">
            <div class="row">
                <div class="col">
                    @yield('content')
                </div>
            </div>
        </div>

        <footer class="footer text-muted">
            <div class="container">

                <p>Codey Version 1</p>
                <p>Designed and maintained by <a href="https://www.coderstudios.com" target="_blank">Coder Studios</a></p>

            </div>
        </footer>
        <script type="text/javascript" src="/vendor/cscms/js/frontend/manifest.js"></script>
        <script type="text/javascript" src="/vendor/cscms/js/frontend/vendor.js"></script>
        <script type="text/javascript" src="/vendor/cscms/js/frontend/app.js"></script>
    </body>
</html>
