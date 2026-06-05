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
    {!! Html::style('assets/libs/bootstrap-select/css/bootstrap-select.min.css') !!}

    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    {!! Html::style('assets/css/style.css') !!}
    {!! Html::style('assets/css/admin.css') !!}
    {!! Html::style('assets/css/responsive.css') !!}
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

</head>


<body class="has-left-bar has-top-bar @if (Auth::guard('client')->user()->menu_open == 1) left-bar-open @endif">

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
                <li @if (Request::path() == '/') class="active" @endif><a href="/"><span
                            class="menu-thumb"><i class="fa fa-sun-o fa-sun"></i></span><span class="menu-text">Scibble
                            Pro</span></a>
                </li>
                {{-- Scribble Usage --}}
                <li><a class="text-white" href="//{{ env('APP_DOMAIN') }}/dashboard"><span class="menu-thumb"><i
                                class="fa fa-arrow-left"></i></span><span class="menu-text ">Scribble
                            Usage</span>
                    </a>
                </li>

                {{-- Pro Subscription --}}
                <li class="@if (preg_match('/prosubscription/i', Request::path())) active @endif"><a href="/prosubscriptions"><span
                            class="menu-thumb"><i class="fa fa-shopping-cart"></i></span><span class="menu-text ">
                            Pro Subscriptions</span>
                    </a>
                </li>

                @if (\App\Models\ProSubscription::where('cl_id', auth('client')->user()->id)->where('staff', 1)->count() > 0)
                    <li class="has-sub @if (preg_match('/staff/i', Request::path())) sub-open init-sub-open active @endif">
                        <a href="#"><span class="menu-thumb"><i class="fa fa-users"></i></span><span
                                class="menu-text">Staff & Roles</span>
                            <span class="arrow"></span></a>
                        <ul class="sub">

                            <li @if (preg_match('/staff/i', Request::path())) class="active" @endif>
                                <a href="/staff"> <span class="menu-thumb"><i class="fa fa-users"></i></span><span
                                        class="menu-text">All
                                        Staff</span>
                                </a>
                            </li>

                            <li @if (preg_match('/roles/i', Request::path())) class="active" @endif>
                                <a href='/staff/assign/roles'>
                                    <span class="menu-thumb"><i class="fa fa-user"></i></span><span
                                        class="menu-text">Assign
                                        Roles</span>
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

                @if (\App\Models\ProSubscription::where('cl_id', auth('client')->user()->id)->where('transactions', 1)->count() > 0)
                    <li class="has-sub @if (preg_match('/transactions/i', Request::path())) sub-open init-sub-open active @endif">
                        <a href="#"><span class="menu-thumb"><i class="fa fa-dollar"></i></span><span
                                class="menu-text">Transactions</span>
                            <span class="arrow"></span></a>
                        <ul class="sub">

                            <li @if (preg_match('/transactions/i', Request::path())) class="active" @endif>
                                <a href="/transactions"> <span class="menu-thumb"><i
                                            class="fa fa-dollar"></i></span><span class="menu-text">All
                                        transactions</span>
                                </a>
                            </li>

                            <li @if (preg_match('/transaction\/per\/business/i', Request::path())) class="active" @endif>
                                <a href='/transactions/per/business'>
                                    <span class="menu-thumb"><i class="fa fa-user"></i></span><span class="menu-text">
                                        Transactions per Business</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (\App\Models\ProSubscription::where('cl_id', auth('client')->user()->id)->where('visitors', 1)->count() > 0)
                    <li class="has-sub @if (preg_match('/visitor/i', Request::path())) sub-open init-sub-open active @endif">
                        <a href="#"><span class="menu-thumb"><i class="fa fa-users"></i></span><span
                                class="menu-text">Visitors</span>
                            <span class="arrow"></span></a>
                        <ul class="sub">

                            <li @if (preg_match('/visitors/i', Request::path())) class="active" @endif><a href="/visitors"> <span
                                        class="menu-thumb"><i class="fa fa-users"></i></span>
                                    <span class="menu-text">All Visitors</span>
                                </a>
                            </li>
                            <li @if (preg_match('/visitorBusiness/i', Request::path())) class="active" @endif>
                                <a href='/visitorBusiness'>
                                    <span class="menu-thumb"><i class="fa fa-user"></i></span><span
                                        class="menu-text">
                                        Visitors per Business</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                {{-- Billings & Payments --}}
                <li class="has-sub @if (preg_match('/user\/receipt/i', Request::path()) or preg_match('/user\/invoice/i', Request::path())) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-money fa-money-bill"></i>
                        </span><span class="menu-text">Billings &
                            Payments</span> <span class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (preg_match('/user\/invoice/i', Request::path())) class="active" @endif><a
                                href={{ url('user/invoices/all') }}><span class="menu-thumb"><i
                                        class="fa fa-files-o fa-file"></i></span><span
                                    class="menu-text">Invoices</span></a>
                        </li>


                        <li @if (Request::path() == 'user/receipts') class="active" @endif><a
                                href={{ url('user/receipts/all') }}><span class="menu-thumb"><i
                                        class="fa fa-file-text"></i></span><span class="menu-text">Receipts</span>
                            </a>
                        </li>

                    </ul>
                </li>
                <li @if (Request::path() == 'logout') class="active" @endif><a href="{{ url('logout') }}"><span
                            class="menu-thumb"><i class="fa fa-power-off"></i></span><span
                            class="menu-text">{{ language_data('Logout', Auth::guard('client')->user()->lan_id) }}</span>
                    </a>
                </li>

            </ul>
        </div>
    </nav>

    <main id="wrapper" class="wrapper">

        <div class="top-bar clearfix">
            <ul class="top-info-bar">
                <li class="dropdown bar-notification @if (count(latest_five_invoices(Auth::guard('client')->user()->id)) > 0) active @endif">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                        aria-expanded="false"><i class="fa fa-shopping-cart"></i></a>
                    <ul class="dropdown-menu arrow" role="menu">
                        <li class="title">
                            {{ language_data('Recent 5 Unpaid Invoices', Auth::guard('client')->user()->lan_id) }}</li>
                        @foreach (latest_five_invoices(Auth::guard('client')->user()->id) as $in)
                            <li>
                                <a href="{{ url('user/invoices/view/' . $in->id) }}">{{ language_data('Amount') }} :
                                    {{ $in->total }}</a>
                            </li>
                        @endforeach
                        <li class="footer"><a
                                href="{{ url('user/invoices/all') }}">{{ language_data('See All Invoices', Auth::guard('client')->user()->lan_id) }}</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown bar-notification @if (count(latest_five_tickets(Auth::guard('client')->user()->id)) > 0) active @endif">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                        aria-expanded="false"><i class="fa fa-envelope"></i></a>
                    <ul class="dropdown-menu arrow message-dropdown" role="menu">
                        <li class="title">
                            {{ language_data('Recent 5 Pending Tickets', Auth::guard('client')->user()->lan_id) }}</li>
                        @foreach (latest_five_tickets(Auth::guard('client')->user()->id) as $st)
                            <li>
                                <a href="{{ url('user/tickets/view-ticket/' . $st->id) }}">
                                    <div class="name">{{ $st->name }} <span>{{ $st->date }}</span></div>
                                    <div class="message">{{ $st->subject }}</div>
                                </a>
                            </li>
                        @endforeach

                        <li class="footer"><a
                                href="{{ url('user/tickets/all') }}">{{ language_data('See All Tickets', Auth::guard('client')->user()->lan_id) }}</a>
                        </li>
                    </ul>
                </li>
                <li class="bar-notification @if (proSmsNotSent() > 0) active @endif">
                    Pro Sms in Queue: <b>{{ proSmsNotSent() }}</b>
                </li>
            </ul>



            <div class="navbar-right">
                <div class="clearfix">
                    <div class="dropdown user-profile pull-right">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-expanded="false">
                            <span
                                class="text-complete text-uppercase m-r-30">{{ language_data('SMS Balance', Auth::guard('client')->user()->lan_id) }}
                                : {{ Auth::guard('client')->user()->sms_limit }}</span>


                            <span class="user-info">{{ Auth::guard('client')->user()->fname }}
                                {{ Auth::guard('client')->user()->lname }}</span>

                            <img class="user-image"
                                src="/private/profile/client/{{ Auth::guard('client')->user()->id }}/{{ Auth::guard('client')->user()->image }}"
                                alt="{{ Auth::guard('client')->user()->fname }} {{ Auth::guard('client')->user()->lname }}">

                        </a>
                        <ul class=" dropdown-menu arrow right-arrow" role="menu">
                            <li><a href="{{ url('user/edit-profile') }}"><i class="fa fa-edit"></i>
                                    {{ language_data('Update Profile', Auth::guard('client')->user()->lan_id) }}</a>
                            </li>
                            <li><a href="{{ url('user/change-password') }}"><i class="fa fa-lock"></i>
                                    {{ language_data('Change Password', Auth::guard('client')->user()->lan_id) }}</a>
                            </li>
                            <li class="bg-dark">
                                <a href="{{ url('logout') }}" class="clearfix">
                                    <span
                                        class="pull-left">{{ language_data('Logout', Auth::guard('client')->user()->lan_id) }}</span>
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
                    <img src="<?php echo asset('assets/country_flag/' . \App\Language::find(Auth::guard('client')->user()->lan_id)->icon); ?>" alt="Language">
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
            get_language_code(Auth::guard('client')->user()->lan_id)->language_code .
            '.js',
    ) !!}
    {!! Html::script('assets/js/scripts.js') !!}
    <script src="/assets/js/select2.min.js"></script>
    <script src="/assets/js/sweetalert.min.js"></script>
    {{-- Global JavaScript End --}}

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


    @yield('script')



</body>

</html>
