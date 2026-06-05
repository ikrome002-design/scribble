<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ app_config('AppTitle') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicon/site.webmanifest">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Global StyleSheet Start --}}
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    {!! Html::style('assets/libs/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('assets/libs/bootstrap-toggle/css/bootstrap-toggle.min.css') !!}
    {!! Html::style('assets/libs/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/libs/alertify/css/alertify.css') !!}
    {!! Html::style('assets/libs/alertify/css/alertify-bootstrap-3.css') !!}
    {!! Html::style('assets/libs/bootstrap-select/css/bootstrap-select.min.css') !!}
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    {{-- Custom StyleSheet Start --}}

    @yield('style')

    {{-- Global StyleSheet End --}}
    {!! Html::style('assets/css/style.css') !!}
    {!! Html::style('assets/css/admin.css') !!}
    {!! Html::style('assets/css/responsive.css') !!}
    <link rel="stylesheet" href="/assets/css/sweetalert.css">
    <style>
        thead,
        tbody {
            white-space: nowrap;
        }

        .d-none {
            display: none !important;
        }

        .white-space-normal {
            white-space: normal !important
        }

        .thumb-right {
            float: right !important;
            margin: 0 0.5rem;
        }
    </style>

</head>


<body class="has-left-bar has-top-bar left-bar-open">

    <nav id="left-nav" class="left-nav-bar">
        <div class="nav-top-sec">
            <div class="app-logo">
                <img src="<?php echo asset(app_config('AppLogo')); ?>" alt="logo" class="bar-logo" width="145px" height="35px">
            </div>

            <a href="#" id="bar-setting" class="bar-setting"><i class="fa fa-solid fa-bars"></i></a>
        </div>
        <div class="nav-bottom-sec">
            <ul class="left-navigation" id="left-navigation">

                {{-- Dashboard --}}
                <li @if (Request::path() == '/') class="active" @endif><a
                        href="//{{ 'admin-pro.' . env('APP_DOMAIN') }}" class="text-white"><span class="menu-thumb"><i
                                class="fa fa-sun-o fa-sun"></i></span><span class="menu-text">Scibble
                            Pro</span></a>
                </li>
                {{-- Scribble Usage --}}
                <li><a class="text-white" href="//{{ 'admin.' . env('APP_DOMAIN') }}"><span class="menu-thumb"><i
                                class="fa fa-arrow-left"></i></span><span class="menu-text ">Scribble
                            Usage</span>
                    </a></li>


                {{-- Plans --}}
                <ul class="sub">
                    <li @if (preg_match('/proplans/i', Request::path())) class="active" @endif><a href={{ url('proplans') }}><span
                                class="menu-thumb">
                                <i class="fa fa-check-square"></i></span><span class="menu-text">Pro Plans</span>
                        </a>
                    </li>
                </ul>

                {{-- subs --}}
                <ul class="sub">
                    <li @if (preg_match('/prosubscription/i', Request::path())) class="active" @endif><a href="/prosubscriptions"><span
                                class="menu-thumb">
                                <i class="fa fa-shopping-cart"></i></span><span class="menu-text">Pro
                                Subscription</span>
                        </a>
                    </li>
                </ul>

                {{-- staff --}}
                <ul class="sub">
                    <li @if (preg_match('/staff/i', Request::path())) class="active" @endif><a href="/staff"><span
                                class="menu-thumb">
                                <i class="fa fa-users"></i></span><span class="menu-text">All Staff</span>
                        </a>
                    </li>
                </ul>

                {{-- transactions --}}
                <ul class="sub">
                    <li @if (preg_match('/transactions/i', Request::path())) class="active" @endif><a href="/transactions"><span
                                class="menu-thumb">
                                <i class="fa fa-dollar"></i></span><span class="menu-text">Transactions</span>
                        </a>
                    </li>
                </ul>

                {{-- visitors --}}
                <ul class="sub">
                    <li @if (preg_match('/visitors/i', Request::path())) class="active" @endif><a href="/visitors"><span
                                class="menu-thumb">
                                <i class="fa fa-users"></i></span><span class="menu-text">Visitors</span>
                        </a>
                    </li>
                </ul>




                {{-- Billings & Payments --}}
                <li class="has-sub @if (preg_match('/receipt/i', Request::path()) or preg_match('/invoice/i', Request::path())) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-money fa-money-bill"></i></span><span
                            class="menu-text">Billings &
                            Payments</span><span class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (preg_match('/invoice/i', Request::path())) class="active" @endif><a
                                href={{ url('/invoices/all') }}><span class="menu-thumb"><i
                                        class="fa fa-files-o fa-file"></i></span><span
                                    class="menu-text">Invoices</span></a>
                        </li>


                        <li @if (preg_match('/receipt/i', Request::path())) class="active" @endif><a
                                href={{ url('/receipts/all') }}><span class="menu-thumb"><i
                                        class="fa fa-file-text"></i></span><span class="menu-text">Receipts</span>
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- Logout --}}
                <li @if (Request::path() == 'admin/logout') class="active" @endif><a href="{{ url('admin/logout') }}">
                        <span class="menu-thumb"><i class="fa fa-power-off"></i></span><span
                            class="menu-text">{{ language_data('Logout') }}</span></a></li>

            </ul>
        </div>
    </nav>

    <main id="wrapper" class="wrapper">

        <div class="top-bar clearfix">
            <ul class="top-info-bar">

                <li class="dropdown bar-notification @if (count(latest_five_invoices(0)) > 0) active @endif">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                        aria-expanded="false"><i class="fa fa-shopping-cart"></i></a>
                    <ul class="dropdown-menu arrow" role="menu">
                        <li class="title">{{ language_data('Recent 5 Unpaid Invoices') }}</li>
                        @foreach (latest_five_invoices(0) as $in)
                            <li>
                                <a href="{{ url('invoices/view/' . $in->id) }}">{{ language_data('Amount') }} :
                                    {{ $in->total }}</a>
                            </li>
                        @endforeach
                        <li class="footer"><a
                                href="{{ url('invoices/all') }}">{{ language_data('See All Invoices') }}</a></li>
                    </ul>
                </li>


                <li class="dropdown bar-notification @if (count(latest_five_tickets(0)) > 0) active @endif">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                        aria-expanded="false"><i class="fa fa-envelope"></i></a>
                    <ul class="dropdown-menu arrow message-dropdown" role="menu">
                        <li class="title">{{ language_data('Recent 5 Pending Tickets') }}</li>
                        @foreach (latest_five_tickets(0) as $st)
                            <li>
                                <a href="{{ url('support-tickets/view-ticket/' . $st->id) }}">
                                    <div class="name">{{ $st->name }} <span>{{ $st->date }}</span></div>
                                    <div class="message">{{ $st->subject }}</div>
                                </a>
                            </li>
                        @endforeach

                        <li class="footer"><a
                                href="{{ url('support-tickets/all') }}">{{ language_data('See All Tickets') }}</a>
                        </li>
                    </ul>
                </li>
            </ul>



            <div class="navbar-right">

                <div class="clearfix">
                    <div class="dropdown user-profile pull-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-expanded="false">
                            <span class="user-info">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</span>

                            <img class="user-image"
                                src="/private/profile/admin/{{ Auth::guard('admin')->user()->image }}"
                                alt="{{ Auth::user()->fname }} {{ Auth::user()->lname }}">


                        </a>
                        <ul class="dropdown-menu arrow right-arrow" role="menu">
                            <li><a href="{{ url('admin/edit-profile') }}"><i class="fa fa-edit"></i>
                                    {{ language_data('Update Profile') }}</a></li>
                            <li><a href="{{ url('admin/change-password') }}"><i class="fa fa-lock"></i>
                                    {{ language_data('Change Password') }}</a></li>
                            <li class="bg-dark">
                                <a href="{{ url('admin/logout') }}" class="clearfix">
                                    <span class="pull-left">{{ language_data('Logout') }}</span>
                                    <span class="pull-right"><i class="fa fa-power-off"></i></span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="top-info-bar m-r-10">

                        <div class="dropdown pull-right bar-notification">
                            <a href="#" class="dropdown-toggle text-success" data-toggle="dropdown"
                                role="button" aria-expanded="false">
                                <img src="<?php echo asset('assets/country_flag/' . \App\Language::find(app_config('Language'))->icon); ?>" alt="Language">
                            </a>
                            <ul class="dropdown-menu lang-dropdown arrow right-arrow" role="menu">
                                @foreach (get_language() as $lan)
                                    <li>
                                        <a href="{{ url('language/change/' . $lan->id) }}"
                                            @if ($lan->id == app_config('Language')) class="text-complete" @endif>
                                            <img class="user-thumb" src="<?php echo asset('assets/country_flag/' . $lan->icon); ?>" alt="user thumb">
                                            <div class="user-name">{{ $lan->language }}</div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- Content File Start Here --}}

        @yield('content')

        {{-- Content File End Here --}}

        <input type="hidden" id="_url" value="{{ url('/') }}">
        <input type="hidden" id="_language_code" value="{{ get_language_code() }}">
        <input type="hidden" id="_sms_gateway_count" value="{{ active_sms_gateway() }}">
        <input type="hidden" id="_unsubscribe_message" value="{{ app_config('unsubscribe_message') }}">
    </main>

    {{-- Global JavaScript Start --}}
    {!! Html::script('assets/libs/jquery-1.10.2.min.js') !!}
    {!! Html::script('assets/libs/jquery.slimscroll.min.js') !!}
    {!! Html::script('assets/libs/bootstrap/js/bootstrap.min.js') !!}
    {!! Html::script('assets/libs/bootstrap-toggle/js/bootstrap-toggle.min.js') !!}
    {!! Html::script('assets/libs/alertify/js/alertify.js') !!}
    {!! Html::script('assets/libs/bootstrap-select/js/bootstrap-select.min.js') !!}
    {!! Html::script('assets/libs/bootstrap-select/js/i18n/' . get_language_code()->language_code . '.js') !!}
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
        var _url = $('#_url').val();

        var _active_gateway = $('#_sms_gateway_count').val();

        if (_active_gateway == 0) {
            alertify.log("<i class='fa fa-times-circle'></i> <span>There is no active sms gateway yet. <a href=" + _url +
                '/sms/http-sms-gateway' + "> Click </a>  to configure one.</span>", "warning", 0);
        }
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
    </script>{{-- Custom JavaScript Start --}} @yield('script') {{-- Custom JavaScript End Here --}}
</body>

</html>
