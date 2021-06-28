<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }}</title>

    <link href="{{ asset('favicon.ico') }}" rel="icon">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet">
    <link href="{{ asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/core.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/colors.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fas/css/all.css') }}" rel="stylesheet">

    @yield('plugin-css')
</head>

@php
    $body_class = $classes['body'] ?? '';
    $current_user = auth()->user();
@endphp

<body class="{{ !empty($has_sticky_sidebar) ? '' : 'navbar-top' }}{{ $body_class }}">
    <div class="navbar navbar-inverse bg-indigo{{ !empty($has_sticky_sidebar) ? '' : ' navbar-fixed-top' }}">
        <div class="navbar-header">
            <a class="navbar-brand text-semibold text-white" href="{{ route('forms.index') }}">{{ config('app.name') }} </a>

            <ul class="nav navbar-nav pull-right visible-xs-block">
                <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                @if (!empty($has_sticky_sidebar))
                    <li><a class="sidebar-mobile-detached-toggle"><i class="icon-grid7"></i></a></li>
                @endif
            </ul>
        </div>

        <div class="navbar-collapse collapse text-indigo" id="navbar-mobile">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle text-white" data-toggle="dropdown">
                        <span>&nbsp;</span>{{ $current_user->full_name }}
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right text-indigo">
                        <li><a href="{{ route('profile.index') }}"><i class="fas fa-user-circle fa-lg"></i> Profil</a></li>
                        <li><a href="{{ route('logout') }}" data-method="post"><i class="fas fa-sign-out-alt fa-lg"></i> Keluar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer', ['class' => 'absolute-center-20'])

    <script src="{{ asset('assets/js/plugins/pace.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/libraries/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/blockui.min.js') }}"></script>

    @yield('plugin-scripts')

    <script src="{{ asset('assets/js/core/app.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/ripple.min.js') }}"></script>

    <script src="{{ asset('assets/js/custom/main.js') }}"></script>
    @yield('page-script')
</body>
</html>
