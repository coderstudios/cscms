<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('page_title','Admin')</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @yield('metas')
        <link rel="stylesheet" href="{{ mix('css/backend/app.css','vendor/cscms') }}">
        <style type="text/css">
            body {
                margin-top:2.5rem;
            }

            button {
                cursor:pointer;
            }

            nav {
                margin-bottom:1rem;
            }

            .footer {
                margin-top:50px;
                min-height:50px;
            }
        </style>

        <script>
            window.Laravel = { csrfToken: '{{ csrf_token() }}' };
        </script>
    </head>
    <body>

        <div class="container">
            <div class="row">
                <div class="col">
                    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <a class="navbar-brand" href="{{ route('backend.index') }}">Admin</a>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                @if (Auth::check())
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Features</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('backend.articles') }}">Articles</a>
                                        <a class="dropdown-item" href="{{ route('backend.images') }}">Images</a>
                                        <a class="dropdown-item" href="{{ route('backend.notifications') }}">Notifications</a>
                                        <a class="dropdown-item" href="{{ route('backend.uploads') }}">Uploads</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Emails</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('backend.emails') }}">Emails</a>
                                        <a class="dropdown-item" href="{{ route('backend.email_groups') }}">Email groups</a>
                                        <a class="dropdown-item" href="{{ route('backend.emails.send') }}">Send</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Users</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('backend.users') }}">Users</a>
                                        <a class="dropdown-item" href="{{ route('backend.user_roles') }}">User Roles</a>
                                        <a class="dropdown-item" href="{{ route('backend.capabilities') }}">Capabilities</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">System</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('backend.export') }}">Import</a>
                                        <a class="dropdown-item" href="{{ route('backend.export') }}">Export</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('backend.settings') }}">Settings</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('backend.article_types') }}">Article Types</a>
                                        <a class="dropdown-item" href="{{ route('backend.languages') }}">Languages</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('backend.cache') }}">Cache</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('backend.log') }}">Error log</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('backend.backups') }}">Backups</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('backend.phpinfo') }}">phpinfo</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Help</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="http://codey.coderstudios.com">Homepage</a>
                                        <a class="dropdown-item" href="http://codey.coderstudios.com/docs">Documentation</a>
                                        <a class="dropdown-item" href="http://codey.coderstudios.com/forum">Support forum</a>
                                    </div>
                                </li>
                                @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                                @endif
                            </ul>
                            <ul class="navbar-nav">
                                @if (Auth::check())
                                <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form></li>
                                @endif
                                <li class="nav-item"><a class="nav-link" href="/" target="_blank">Site homepage</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        @include('cscms::backend.partials.banner',[
            'success' => $success,
            'error' => $error,
            'csrf_error' => $csrf_error,
        ])

        @yield('content')

        <div class="container footer">
            <div class="row">
                <div class="col">
                    <p>&copy; {{ date('Y') }} Copyright Coder Studios Ltd. All rights reserved.</p>
                </div>
            </div>
        </div>

        <script src="{{ mix('js/backend/app.js','vendor/cscms') }}"></script>
        @yield('footer')
    </body>
</html>