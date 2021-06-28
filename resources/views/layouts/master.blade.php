<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/adminimgs/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/adminimgs/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        @yield('title')
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admincss/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/admincss/now-ui-dashboard.css') }}">
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ URL::asset('assets/admindemo/demo.css') }}">
</head>

<body class="">
    <div class="wrapper ">

        {{-- sidebar --}}
        <div class="sidebar" data-color="blue">
            <div class="logo">
                <a href="/dashboard" class="simple-text logo-normal">
                    <center>
                        DESForm
                    </center>
                </a>
            </div>
            <div class="sidebar-wrapper" id="sidebar-wrapper">
                <ul class="nav">
                    <li class="{{'dashboard'==request()->path()?'active':''}}">
                        <a href="/dashboard">
                            <i class="fa fa-chart-line"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="{{'UserList'==Request::segment(1)?'active':''}}">
                        <a href="/UserList">
                            <i class="fa fa-users"></i>
                            <p>Daftar Pengguna</p>
                        </a>
                    </li>
                    <li class="{{'AdminList'==Request::segment(1)?'active':''}}">
                        <a href="/AdminList">
                            <i class="fa fa-users"></i>
                            <p>Daftar Admin</p>
                        </a>
                    </li>
                    <!-- <li class="{{'about-us'==request()->path()?'active':''}}">
                        <a href="/about-us">
                            <i class="fa fa-align-justify"></i>
                            <p>About Us</p>
                        </a>
                    </li> -->
                </ul>
            </div>
        </div>

        <div class="main-panel" id="main-panel">

            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-toggle">
                            <button type="button" class="navbar-toggler">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </button>
                        </div>
                        @yield('db')
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                        aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <!-- <form>
                            <div class="input-group no-border">
                                <input type="text" value="" class="form-control" placeholder="Search...">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-search"></i>
                                    </div>
                                </div>
                            </div>
                        </form> -->
                        <ul class="navbar-nav">
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="#pablo">
                                    <i class="now-ui-icons media-2_sound-wave"></i>
                                    <p>
                                        <span class="d-lg-none d-md-block">Stats</span>
                                    </p>
                                </a>
                            </li> -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->first_name." ".Auth::user()->last_name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.indexadmin') }}">
                                    <i class="fa fa-user"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->

            @yield('dashboardpanel')
            @yield('genericpanel')
            <div class="content">

                @yield('content')

            </div>

            <footer class="footer">
                <div class=" container-fluid ">
                    <nav>
                        <ul>
                            <li>
                                <a">
                                    {{ config('app.name') }}
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright" id="copyright">
                        &copy; <script>
                            document.getElementById('copyright').appendChild(document.createTextNode(new Date()
                                .getFullYear()))

                        </script>, Made by <a href="http://www.github.com/zakialfaridzi" target="_blank">Zaki Al Faridzi</a>.
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="../assets/adminjs/core/jquery.min.js"></script>
    <script src="../assets/adminjs/core/popper.min.js"></script>
    <script src="../assets/adminjs/core/bootstrap.min.js"></script>
    <script src="../assets/adminjs/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!-- Chart JS -->
    <script src="../assets/adminjs/plugins/chartjs.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="../assets/adminjs/plugins/bootstrap-notify.js"></script>
    <script src="../assets/adminjs/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script>
    <script src="../assets/admindemo/demo.js"></script>
    @yield('scripts')
</body>

</html>
