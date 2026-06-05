<head>
    <meta charset="utf-8">
    <title>{{ app_config('AppTitle') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicon/site.webmanifest">

    {{-- Global StyleSheet Start --}}
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    {!! Html::style('assets/libs/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('assets/libs/bootstrap-toggle/css/bootstrap-toggle.min.css') !!}
    {!! Html::style('assets/libs/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/libs/alertify/css/alertify.css') !!}
    {!! Html::style('assets/libs/alertify/css/alertify-bootstrap-3.css') !!}
    {!! Html::style('assets/libs/bootstrap-select/css/bootstrap-select.min.css') !!}

    {!! Html::style('assets/libs/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('assets/libs/bootstrap-toggle/css/bootstrap-toggle.min.css') !!}
    {!! Html::style('assets/libs/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/libs/alertify/css/alertify.css') !!}
    {!! Html::style('assets/libs/alertify/css/alertify-bootstrap-3.css') !!}
    {!! Html::style('assets/libs/bootstrap-select/css/bootstrap-select.min.css') !!}

    {!! Html::style('assets/css/style.css') !!}
    {!! Html::style('assets/css/admin.css') !!}
    {!! Html::style('assets/css/responsive.css') !!}
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/sweetalert.css">
    @yield('style')
    <style>
        tr.border-0 td {
            border: 0 !important;
        }

        m-0 {
            margin: 0 !important
        }

        p-0 {
            padding: 0 !important
        }

        thead,
        tbody {
            white-space: nowrap;
        }

        .white-space-normal {
            white-space: normal !important
        }

        .d-none {
            display: none !important;
        }

        .thumb-right {
            float: right !important;
            margin: 0 0.5rem;
        }
    </style>
    @yield('style')
</head>



<body class="has-left-bar has-top-bar  left-bar-open">

    <nav id="left-nav" class="left-nav-bar">
        <div class="nav-top-sec">
            <div class="app-logo">
                <img src="<?php echo asset(app_config('AppLogo')); ?>" alt="logo" class="bar-logo" width="145px" height="35px">
            </div>

            <a href="#" id="bar-setting" class="bar-setting"><i class="fa fa-bars"></i></a>
        </div>
        <div class="nav-bottom-sec">
            <ul class="left-navigation" id="left-navigation">

                {{-- Dashboard --}}
                <li class="@if (preg_match('/dashboard/i', Request::path())) active @endif">
                    <a href="/dashboard"><span class="menu-thumb"><i class="fa fa-Money"></i></span><span
                            class="menu-text">Staff ({{ auth('staff')->user()->unique_id }})</span></a>
                </li>


                @if (\App\Models\ProSubscription::where('cl_id', auth('staff')->user()->cl_id)->where('staff', 1)->count() > 0)
                    <li class="has-sub @if (preg_match('/staff/i', Request::path())) sub-open init-sub-open active @endif">
                        <a href="#"><span class="menu-thumb"><i class="fa fa-shopping-cart"></i></span><span
                                class="menu-text">Staff & Roles</span>
                            <span class="arrow"></span></a>
                        <ul class="sub">

                            <li @if (preg_match('/staff/i', Request::path())) class="active" @endif>
                                <a href="/staff"> <span class="menu-thumb"><i class="fa fa-users"></i></span><span
                                        class="menu-text">All Staff</span>
                                </a>
                            </li>

                            <li @if (preg_match('/roles/i', Request::path())) class="active" @endif>
                                <a href='/staff/assign/roles'>
                                    <span class="menu-thumb"><i class="fa fa-user"></i></span><span class="menu-text">Re
                                        Assign Roles</span>
                                </a>
                            </li>
                            <li @if (preg_match('/work\/history/i', Request::path())) class="active" @endif>
                                <a href='/staff/work/history'>
                                    <span class="menu-thumb"><i class="fa-tasks fa"></i></span><span
                                        class="menu-text">Work
                                        History</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (\App\Models\ProSubscription::where('cl_id', auth('staff')->user()->cl_id)->where('transactions', 1)->count() > 0)
                    <li class="has-sub @if (preg_match('/transactions/i', Request::path())) sub-open init-sub-open active @endif">
                        <a href="#"><span class="menu-thumb"><i class="fa fa-shopping-cart"></i></span><span
                                class="menu-text">Transactions</span>
                            <span class="arrow"></span></a>
                        <ul class="sub">

                            <li @if (preg_match('/transactions/i', Request::path())) class="active" @endif>
                                <a href="/transactions"> <span class="menu-thumb"><i
                                            class="fa fa-dollar"></i></span><span class="menu-text">All
                                        Transactions</span>
                                </a>
                            </li>

                            <li @if (preg_match('/transactions\/per\/business/i', Request::path())) class="active" @endif>
                                <a href='/transactions/per/business'>
                                    <span class="menu-thumb"><i class="fa fa-user"></i></span><span class="menu-text">
                                        Transactions per Business</span>
                                </a>
                            </li>



                        </ul>
                    </li>
                @endif


                @if (\App\Models\ProSubscription::where('cl_id', auth('staff')->user()->cl_id)->where('transactions', 1)->count() > 0)
                    <li class="has-sub @if (preg_match('/visitor/i', Request::path())) sub-open init-sub-open active @endif">
                        <a href="#"><span class="menu-thumb"><i class="fa fa-users"></i></span><span
                                class="menu-text">Visitors</span>
                            <span class="arrow"></span></a>
                        <ul class="sub">

                            <li @if (preg_match('/visitors/i', Request::path())) class="active" @endif>
                                <a href="/visitors"> <span class="menu-thumb"><i class="fa fa-users"></i></span><span
                                        class="menu-text">All Visitors</span>
                                </a>
                            </li>
                            <li @if (preg_match('/visitorBusiness/i', Request::path())) class="active" @endif>
                                <a href='/visitorBusiness'>
                                    <span class="menu-thumb"><i class="fa fa-user"></i></span><span class="menu-text">
                                        Visitors per Business</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                {{-- Logout --}}
                <li @if (Request::path() == 'logout') class="active" @endif><a href="{{ url('logout') }}"><span
                            class="menu-thumb"><i class="fa fa-power-off"></i></span><span
                            class="menu-text">Logout</span>
                    </a></li>

            </ul>
        </div>
    </nav>

    <main id="wrapper" class="wrapper">

        <div class="top-bar clearfix">

            <div class="navbar-right">
                <div class="clearfix">
                    <div class="dropdown user-profile pull-right">
                        <ul class=" dropdown-menu arrow right-arrow" role="menu">
                            @if (auth('staff')->user()->role == 'Manager')
                                <li>
                                    <a href="/update/profile"><i class="fa fa-edit"></i>
                                        Update Profile
                                    </a>
                                </li>
                            @endif
                            <li class="bg-dark">
                                <a href="{{ url('logout') }}" class="clearfix">
                                    <span class="pull-left">Logout</span>
                                    <span class="pull-right"><i class="fa fa-power-off"></i></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="language-var user-info">
                <a href="#" class="dropdown-toggle text-success" data-toggle="dropdown" role="button"
                    aria-expanded="false">

                </a>
                <ul class="dropdown-menu lang-dropdown arrow right-arrow" role="menu">
                    @foreach (get_language() as $lan)
                        <li>
                            <a href="{{ url('user/language/change/' . $lan->id) }}"
                                @if ($lan->id == app_config('Language')) class="text-complete" @endif>
                                <img class="user-thumb" src="<?php echo asset('assets/country_flag/' . $lan->icon); ?>" alt="user thumb">
                                <div class="user-name">{{ $lan->language }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

        {{-- Content File Start Here --}}

        @yield('content')

        {{-- Content File End Here --}}

        <input type="hidden" id="_url" value="{{ url('/') }}">
        <input type="hidden" id="_unsubscribe_message" value="{{ app_config('unsubscribe_message') }}">
    </main>

    {{-- Global JavaScript Start --}}
    {!! Html::script('assets/libs/jquery-1.10.2.min.js') !!}
    {!! Html::script('assets/libs/jquery.slimscroll.min.js') !!}
    {!! Html::script('assets/libs/bootstrap/js/bootstrap.min.js') !!}
    {!! Html::script('assets/libs/bootstrap-toggle/js/bootstrap-toggle.min.js') !!}
    {!! Html::script('assets/libs/alertify/js/alertify.js') !!}
    {!! Html::script('assets/libs/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! Html::script(
        'assets/libs/bootstrap-select/js/i18n/' .
            get_language_code(Auth::guard('staff')->user()->lan_id)->language_code .
            '.js',
    ) !!}
    <script src="/assets/js/select2.min.js"></script>
    {!! Html::script('assets/js/scripts.js') !!}
    <script src="/assets/js/sweetalert.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        function responsiveCheck() {
            var width = $(window).width();
            if (width <= 768) {
                $('body').removeClass('left-bar-open');
                $('.menu-text').removeClass('d-none');
            } else {
                $('body').addClass('left-bar-open');
                $(document).on('click', '#bar-setting', function() {
                    if (!$("body").hasClass("left-bar-open")) {
                        $('.menu-text').addClass('d-none');
                        $('.left-nav-bar .left-navigation li a .menu-thumb').addClass('thumb-right')
                    } else {
                        $('.menu-text').removeClass('d-none');
                        $('.left-nav-bar .left-navigation li a .menu-thumb')
                            .removeClass('thumb-right')
                    }
                });
            }
        }
        responsiveCheck()
        window.addEventListener("resize", responsiveCheck())
    </script>
    {{-- Custom JavaScript Start --}}

    @yield('script')

    {{-- Custom JavaScript End Here --}}
</body>

</html>
