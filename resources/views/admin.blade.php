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


<body class="has-left-bar has-top-bar @if (Auth::user()->menu_open == 1) left-bar-open @endif">

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
                <li @if (Request::path() == 'admin/dashboard') class="active" @endif><a href="{{ url('admin/dashboard') }}"
                        class="text-white"><span class="menu-thumb"><i class="fa fa-dashboard"></i></span><span
                            class="menu-text">{{ language_data('Scribble Usage') }}</span></a></li>
                {{-- Pro --}}
                <li class="btn-success"><a class="text-white" href="//{{ 'admin-pro.' . env('APP_DOMAIN') }}"><span
                            class="menu-thumb"><i class="fa fa-sun-o fa-sun" aria-hidden="true"></i></span><span
                            class="menu-text ">Scribble
                            Pro</span>
                    </a></li>

                {{-- Clients --}}
                <li class="has-sub @if (Request::path() == 'clients/all' or
                        Request::path() == 'clients/add' or
                        Request::path() == 'clients/view/' . view_id() or
                        Request::path() == 'clients/export-n-import' or
                        Request::path() == 'clients/groups') sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-user"></i></span><span
                            class="menu-text">{{ language_data('Clients') }}</span><span class="arrow"></span> </a>
                    <ul class="sub">

                        <li @if (Request::path() == 'clients/all' or Request::path() == 'clients/view/' . view_id()) class="active" @endif>
                            <a href={{ url('clients/all') }}><span class="menu-thumb"><i
                                        class="fa fa-user"></i></span><span
                                    class="menu-text">{{ language_data('All Clients') }}</span> </a>
                        </li>

                        <li @if (Request::path() == 'clients/add') class="active" @endif><a
                                href={{ url('clients/add') }}><span class="menu-thumb"><i
                                        class="fa fa-user-plus"></i></span><span
                                    class="menu-text">{{ language_data('Add New Client') }}</span> </a></li>

                        <li @if (Request::path() == 'clients/groups') class="active" @endif><a
                                href="{{ url('clients/groups') }}"><span class="menu-thumb"><i
                                        class="fa fa-users"></i></span><span
                                    class="menu-text">{{ language_data('Clients Groups') }}</span> </a></li>

                        <li @if (Request::path() == 'clients/export-n-import') class="active" @endif><a
                                href={{ url('clients/export-n-import') }}><span class="menu-thumb"><i
                                        class="fa fa-file-excel-o"></i></span><span
                                    class="menu-text">{{ language_data('Export and Import Clients') }}</span> </a></li>



                    </ul>
                </li>


                {{-- accounts and packages --}}
                <li class="has-sub @if (preg_match('/admin\/account/i', Request::path()) || preg_match('/admin\/package/i', Request::path())) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-list-alt"></i></span><span
                            class="menu-text">Accounts & Packages</span> <span class="arrow"></span></a>
                    <ul class="sub">
                        <li @if (preg_match('/admin\/package/i', Request::path())) class="active" @endif><a
                                href={{ url('admin/packages') }}><span class="menu-thumb">
                                    <i class="fa fa-check-square"></i></span><span class="menu-text">Packages</span>
                            </a>
                        </li>

                        <li @if (preg_match('/admin\/account/i', Request::path())) class="active" @endif><a
                                href={{ url('admin/accounts') }}><span class="menu-thumb">
                                    <i class="fa fa-list-ul"></i></span><span class="menu-text">Accounts</span> </a>
                        </li>


                    </ul>
                </li>

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



                {{-- Invoices
                <li class="has-sub @if (Request::path() == 'invoices/all' or Request::path() == 'invoices/add' or Request::path() == 'invoices/recurring' or Request::path() == 'invoices/view/' . view_id() or Request::path() == 'invoices/edit/' . view_id()) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-credit-card"></i></span><span
                            class="menu-text">{{ language_data('Invoices') }}</span> <span class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (Request::path() == 'invoices/all' or Request::path() == 'invoices/view/' . view_id() or Request::path() == 'invoices/edit/' . view_id()) class="active" @endif>
                            <a href={{ url('invoices/all') }}><span class="menu-thumb"><i
                                        class="fa fa-list"></i></span><span
                                    class="menu-text">{{ language_data('All Invoices') }}</span>
                            </a>
                        </li>

                        <li @if (Request::path() == 'invoices/recurring') class="active" @endif><a
                                href={{ url('invoices/recurring') }}><span class="menu-thumb"><i
                                        class="fa fa-list"></i></span><span
                                    class="menu-text">{{ language_data('Recurring') }}
                                    {{ language_data('Invoices') }}</span>
                            </a></li>

                        <li @if (Request::path() == 'invoices/add') class="active" @endif><a
                                href={{ url('invoices/add') }}><span class="menu-thumb"><i
                                        class="fa fa-plus"></i></span><span
                                    class="menu-text">{{ language_data('Create New Invoice') }}</span></a></li>

                    </ul>
                </li> --}}






                {{-- Contacts --}}
                <li class="has-sub @if (Request::path() == 'sms/phone-book' or
                        Request::path() == 'sms/import-contacts' or
                        Request::path() == 'sms/view-contact/' . view_id() or
                        Request::path() == 'sms/blacklist-contacts' or
                        Request::path() == 'sms/add-contact/' . view_id() or
                        Request::path() == 'sms/edit-contact/' . view_id() or
                        Request::path() == 'sms/spam-words') sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-book"></i></span><span
                            class="menu-text">{{ language_data('Contacts') }}</span> <span class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (Request::path() == 'sms/phone-book' or
                                Request::path() == 'sms/view-contact/' . view_id() or
                                Request::path() == 'sms/add-contact/' . view_id() or
                                Request::path() == 'sms/edit-contact/' . view_id()) class="active" @endif>
                            <a href={{ url('sms/phone-book') }}><span class="menu-thumb"><i
                                        class="fa fa-book"></i></span><span class="menu-text">
                                    {{ language_data('Phone Book') }}</span> </a>
                        </li>

                        <li @if (Request::path() == 'sms/import-contacts') class="active" @endif><a
                                href={{ url('sms/import-contacts') }}><span class="menu-thumb"><i
                                        class="fa fa-plus"></i></span><span class="menu-text">
                                    {{ language_data('Import Contacts') }}</span> </a></li>

                        <li @if (Request::path() == 'sms/blacklist-contacts') class="active" @endif><a
                                href={{ url('sms/blacklist-contacts') }}><span class="menu-thumb"><i
                                        class="fa fa-remove"></i></span><span class="menu-text">
                                    {{ language_data('Blacklist Contacts') }}</span> </a></li>

                        <li @if (Request::path() == 'sms/spam-words') class="active" @endif><a
                                href={{ url('sms/spam-words') }}><span class="menu-thumb"><i
                                        class="fa fa-stop"></i></span><span class="menu-text">
                                    {{ language_data('Spam Words') }}</span> </a></li>

                    </ul>
                </li>

                {{-- Recharge --}}
                <li class="has-sub @if (Request::path() == 'sms/price-plan' or
                        Request::path() == 'sms/add-price-plan' or
                        Request::path() == 'sms/add-plan-feature/' . view_id() or
                        Request::path() == 'sms/manage-price-plan/' . view_id() or
                        Request::path() == 'sms/view-plan-feature/' . view_id() or
                        Request::path() == 'sms/manage-plan-feature/' . view_id() or
                        Request::path() == 'sms/price-bundles') sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-shopping-cart"></i></span><span
                            class="menu-text">Airtime & Bundles Settings</span> <span class="arrow"></span></a>
                    <ul class="sub">

                        {{-- 
                        <li @if (Request::path() == 'sms/price-bundles' or Request::path() == 'sms/manage-price-bundles/' . view_id()) class="active" @endif>
                            <a href={{ url('sms/price-bundles') }}><span class="menu-thumb"><i
                                        class="fa fa-shopping-cart"></i></span><span
                                    class="menu-text">{{ language_data('Price Bundles') }}</span> </a>
                        </li> --}}

                        <li @if (Request::path() == 'sms/price-plan' or
                                Request::path() == 'sms/add-plan-feature/' . view_id() or
                                Request::path() == 'sms/manage-price-plan/' . view_id() or
                                Request::path() == 'sms/view-plan-feature/' . view_id() or
                                Request::path() == 'sms/manage-plan-feature/' . view_id()) class="active" @endif>
                            <a href={{ url('sms/price-plan') }}><span class="menu-thumb"><i
                                        class="fa fa-money"></i></span><span
                                    class="menu-text">{{ language_data('SMS Price Plan') }}</span> </a>
                        </li>

                        {{-- <li @if (Request::path() == 'sms/add-price-plan') class="active" @endif><a
                                    href={{url('sms/add-price-plan')}}> <span
                                        class="menu-thumb"><i class="fa fa-plus"></i></span><span
                                        class="menu-text">{{language_data('Add SMS Price Plan')}}</span></a></li> --}}



                        {{-- <li @if (Request::path() == 'sms/add-plan-name') class="active" @endif><a
                            href={{url('sms/add-plan-name')}}> <span
                                class="menu-thumb"><i class="fa fa-plus"></i></span><span
                                class="menu-text">{{language_data('Add Plan Name')}}</span></a></li> --}}

                        <li @if (Request::path() == 'sms/airtime-bundle') class="active" @endif>
                            <a href={{ url('sms/airtime-bundle') }}>
                                <span class="menu-thumb"><i class="fa fa-money"></i></span>
                                <span class="menu-text">{{ language_data('Airtime Bundle Plan') }}</span>
                            </a>
                        </li>



                        {{-- <li @if (Request::path() == 'sms/add-airtime-bundle') class="active" @endif>
                            <a href={{url('sms/add-airtime-bundle')}}><span class="menu-thumb"><i class="fa fa-plus"></i></span><span class="menu-text">{{language_data('Add Airtime Bundle')}}</span> </a>
                        </li> --}}

                    </ul>
                </li>




                {{-- Bulk SMS --}}
                <li class="has-sub @if (Request::path() == 'sms/quick-sms' or
                        Request::path() == 'sms/send-sms' or
                        Request::path() == 'sms/send-sms-file' or
                        Request::path() == 'sms/send-schedule-sms' or
                        Request::path() == 'sms/send-schedule-sms-file' or
                        Request::path() == 'sms/update-schedule-sms' or
                        Request::path() == 'sms/manage-update-schedule-sms/' . view_id() or
                        Request::path() == 'sms/campaign-reports' or
                        Request::path() == 'sms/manage-campaign/' . view_id()) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-mobile"></i></span><span
                            class="menu-text">{{ language_data('Bulk SMS') }}</span> <span class="arrow"></span></a>
                    <ul class="sub">


                        <li @if (Request::path() == 'sms/quick-sms') class="active" @endif><a
                                href={{ url('sms/quick-sms') }}><span class="menu-thumb"><i
                                        class="fa fa-space-shuttle"></i></span><span
                                    class="menu-text">{{ language_data('Send Quick SMS') }}</span></a></li>

                        <li @if (Request::path() == 'sms/send-sms') class="active" @endif><a
                                href={{ url('sms/send-sms') }}><span class="menu-thumb"><i
                                        class="fa fa-send"></i></span><span
                                    class="menu-text">{{ language_data('Send Bulk SMS') }}</span></a></li>

                        <li @if (Request::path() == 'sms/send-schedule-sms') class="active" @endif><a
                                href={{ url('sms/send-schedule-sms') }}><span class="menu-thumb"><i
                                        class="fa fa-send-o"></i></span><span
                                    class="menu-text">{{ language_data('Send') }}
                                    {{ language_data('Schedule SMS') }}</span>
                            </a></li>

                        <li @if (Request::path() == 'sms/send-sms-file') class="active" @endif><a
                                href={{ url('sms/send-sms-file') }}><span class="menu-thumb"><i
                                        class="fa fa-file-text"></i></span><span
                                    class="menu-text">{{ language_data('Send SMS From File') }}</span></a></li>

                        <li @if (Request::path() == 'sms/send-schedule-sms-file') class="active" @endif><a
                                href={{ url('sms/send-schedule-sms-file') }}><span class="menu-thumb"><i
                                        class="fa fa-file-text-o"></i></span><span
                                    class="menu-text">{{ language_data('Schedule SMS From File') }}</span></a></li>

                        <li @if (Request::path() == 'sms/campaign-reports' or
                                Request::path() == 'sms/manage-campaign/' . view_id() or
                                Request::path() == 'sms/manage-update-schedule-sms/' . view_id()) class="active" @endif>
                            <a href={{ url('sms/campaign-reports') }}><span class="menu-thumb"><i
                                        class="fa fa-line-chart"></i></span><span
                                    class="menu-text">{{ language_data('Campaign Reports') }}</span></a>
                        </li>


                    </ul>
                </li>



                {{-- Recurring SMS --}}
                <li class="has-sub @if (Request::path() == 'sms/recurring-sms' or
                        Request::path() == 'sms/send-recurring-sms' or
                        Request::path() == 'sms/send-recurring-sms-file' or
                        Request::path() == 'sms/update-recurring-sms/' . view_id() or
                        Request::path() == 'sms/add-recurring-sms-contact/' . view_id() or
                        Request::path() == 'sms/update-recurring-sms-contact/' . view_id() or
                        Request::path() == 'sms/update-recurring-sms-contact-data/' . view_id()) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-clock-o fa-clock"></i></span><span
                            class="menu-text">{{ language_data('Recurring SMS') }}</span> <span
                            class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (Request::path() == 'sms/recurring-sms' or
                                Request::path() == 'sms/update-recurring-sms/' . view_id() or
                                Request::path() == 'sms/add-recurring-sms-contact/' . view_id() or
                                Request::path() == 'sms/update-recurring-sms-contact/' . view_id() or
                                Request::path() == 'sms/update-recurring-sms-contact-data/' . view_id()) class="active" @endif>
                            <a href={{ url('sms/recurring-sms') }}><span class="menu-thumb"><i
                                        class="fa fa-list"></i></span><span
                                    class="menu-text">{{ language_data('All') }}
                                    {{ language_data('Recurring SMS') }}</span>
                            </a>
                        </li>

                        <li @if (Request::path() == 'sms/send-recurring-sms') class="active" @endif><a
                                href={{ url('sms/send-recurring-sms') }}><span class="menu-thumb"><i
                                        class="fa fa-send"></i></span><span
                                    class="menu-text">{{ language_data('Send') }}
                                    {{ language_data('Recurring SMS') }}</span>
                            </a></li>

                        <li @if (Request::path() == 'sms/send-recurring-sms-file') class="active" @endif><a
                                href={{ url('sms/send-recurring-sms-file') }}><span class="menu-thumb"><i
                                        class="fa fa-file-text"></i></span><span
                                    class="menu-text">{{ language_data('Send Recurring SMS File') }}</span> </a></li>

                    </ul>
                </li>


                <li @if (Request::path() == 'sms/sender-id-management' or
                        Request::path() == 'sms/add-sender-id' or
                        Request::path() == 'sms/view-sender-id/' . view_id()) class="active" @endif>
                    <a href={{ url('sms/sender-id-management') }}><span class="menu-thumb"><i
                                class="fa fa-user-secret"></i></span><span
                            class="menu-text">{{ language_data('Sender ID Management') }}</span> </a>
                </li>


                <li @if (Request::path() == 'sms/sms-templates' or
                        Request::path() == 'sms/create-sms-template' or
                        Request::path() == 'sms/manage-sms-template/' . view_id()) class="active" @endif>
                    <a href={{ url('sms/sms-templates') }}><span class="menu-thumb"><i
                                class="fa fa-file-code-o fa-file-code"></i></span><span
                            class="menu-text">{{ language_data('SMS Templates') }}</span>
                    </a>
                </li>


                {{-- SMS Gateways --}}
                <li class="has-sub @if (Request::path() == 'sms/http-sms-gateway' or
                        Request::path() == 'sms/smpp-sms-gateway' or
                        Request::path() == 'sms/add-sms-gateways' or
                        Request::path() == 'sms/gateway-manage/' . view_id() or
                        Request::path() == 'sms/custom-gateway-manage/' . view_id() or
                        Request::path() == 'sms/add-smpp-sms-gateways' or
                        Request::path() == 'sms/smpp-gateway-manage/' . view_id()) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-server"></i></span><span
                            class="menu-text">{{ language_data('SMS Gateway') }}</span> <span
                            class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (Request::path() == 'sms/http-sms-gateway' or
                                Request::path() == 'sms/add-sms-gateways' or
                                Request::path() == 'sms/custom-gateway-manage/' . view_id()) class="active" @endif>
                            <a href={{ url('sms/http-sms-gateway') }}><span class="menu-thumb"><i
                                        class="fa fa-code"></i></span><span class="menu-text"> HTTP
                                    {{ language_data('SMS Gateway') }}</span> </a>
                        </li>

                        <li @if (Request::path() == 'sms/smpp-sms-gateway' or
                                Request::path() == 'sms/add-smpp-sms-gateways' or
                                Request::path() == 'sms/smpp-gateway-manage/' . view_id()) class="active" @endif>
                            <a href={{ url('sms/smpp-sms-gateway') }}><span class="menu-thumb"><i
                                        class="fa fa-server"></i></span><span class="menu-text"> SMPP
                                    {{ language_data('SMS Gateway') }}</span> </a>
                        </li>

                    </ul>
                </li>



                <li @if (Request::path() == 'sms/chat-box') class="active" @endif><a href={{ url('sms/chat-box') }}><span
                            class="menu-thumb"><i class="fa fa-comments"></i></span><span
                            class="menu-text">{{ language_data('Chat SMS') }}</span>

                    </a>
                </li>


                {{-- History --}}
                <li class="has-sub @if (Request::path() == 'sms/history' or
                        Request::path() == 'sms/view-inbox/' . view_id() or
                        Request::path() == 'sms/reports/download' or
                        Request::path() == 'sms/reports/delete' or
                        Request::path() == 'sms/block-message' or
                        Request::path() == 'sms/view-block-message/' . view_id()) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-list"></i></span><span
                            class="menu-text">{{ language_data('Reports') }}</span> <span class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (Request::path() == 'sms/history' or Request::path() == 'sms/view-inbox/' . view_id()) class="active" @endif>
                            <a href={{ url('sms/history') }}><span class="menu-thumb"><i
                                        class="fa fa-list"></i></span><span
                                    class="menu-text">{{ language_data('SMS History') }}</span>
                            </a>
                        </li>


                        <li @if (Request::path() == 'sms/block-message' or Request::path() == 'sms/view-block-message/' . view_id()) class="active" @endif>
                            <a href={{ url('sms/block-message') }}><span class="menu-thumb"><i
                                        class="fa fa-remove"></i></span><span
                                    class="menu-text">{{ language_data('Block Message') }}</span> </a>
                        </li>

                    </ul>
                </li>

                {{-- SMS API --}}
                <li class="has-sub @if (Request::path() == 'sms-api/info' or Request::path() == 'sms-api/sdk') sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-plug"></i></span><span
                            class="menu-text">{{ language_data('SMS Api') }}</span> <span class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (Request::path() == 'sms-api/info') class="active" @endif><a
                                href={{ url('sms-api/info') }}><span class="menu-thumb"><i
                                        class="fa fa-cog"></i></span><span
                                    class="menu-text">{{ language_data('SMS Api') }}</span>
                            </a></li>

                        <li @if (Request::path() == 'sms-api/sdk') class="active" @endif><a
                                href={{ url('sms-api/sdk') }}><span class="menu-thumb"><i
                                        class="fa fa-download"></i></span><span
                                    class="menu-text">{{ language_data('SMS Api') }} SDK</span> </a></li>

                    </ul>
                </li>


                {{-- Support Ticket --}}
                <li class="has-sub @if (Request::path() == 'support-tickets/all' or
                        Request::path() == 'support-tickets/create-new' or
                        Request::path() == 'support-tickets/department' or
                        Request::path() == 'support-tickets/view-department/' . view_id() or
                        Request::path() == 'support-tickets/view-ticket/' . view_id()) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-envelope"></i></span><span
                            class="menu-text">{{ language_data('Support Tickets') }}</span> <span
                            class="arrow"></span></a>
                    <ul class="sub">
                        <li @if (Request::path() == 'support-tickets/all' or Request::path() == 'support-tickets/view-ticket/' . view_id()) class="active" @endif>
                            <a href={{ url('support-tickets/all') }}><span class="menu-thumb"><i
                                        class="fa fa-list"></i></span><span
                                    class="menu-text">{{ language_data('All') }}
                                    {{ language_data('Support Tickets') }}</span>
                            </a>
                        </li>

                        <li @if (Request::path() == 'support-tickets/create-new') class="active" @endif><a
                                href={{ url('support-tickets/create-new') }}><span class="menu-thumb"><i
                                        class="fa fa-plus"></i></span><span
                                    class="menu-text">{{ language_data('Create New Ticket') }}</span> </a></li>

                        <li @if (Request::path() == 'support-tickets/department') class="active" @endif><a
                                href={{ url('support-tickets/department') }}><span class="menu-thumb"><i
                                        class="fa fa-support"></i></span><span
                                    class="menu-text">{{ language_data('Support Department') }}</span> </a></li>

                    </ul>
                </li>


                {{-- Keywords --}}
                <li class="has-sub @if (Request::path() == 'keywords/all' or
                        Request::path() == 'keywords/add' or
                        Request::path() == 'keywords/view/' . view_id() or
                        Request::path() == 'keywords/settings') sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i
                                class="fa fa-keyboard-o fa-keyboard"></i></span><span
                            class="menu-text">{{ language_data('Keywords') }}</span> <span class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (Request::path() == 'keywords/all' or Request::path() == 'keywords/view/' . view_id()) class="active" @endif>
                            <a href={{ url('keywords/all') }}><span class="menu-thumb"><i
                                        class="fa fa-list"></i></span><span
                                    class="menu-text">{{ language_data('All Keywords') }}</span>
                            </a>
                        </li>

                        <li @if (Request::path() == 'keywords/add') class="active" @endif><a
                                href={{ url('keywords/add') }}><span class="menu-thumb"><i
                                        class="fa fa-plus"></i></span><span
                                    class="menu-text">{{ language_data('Add New Keyword') }}</span> </a></li>


                        <li @if (Request::path() == 'keywords/settings') class="active" @endif><a
                                href={{ url('keywords/settings') }}><span class="menu-thumb"><i
                                        class="fa fa-cog"></i></span><span
                                    class="menu-text">{{ language_data('Keyword Settings') }}</span> </a></li>

                    </ul>
                </li>


                {{-- coverage --}}
                <li @if (Request::path() == 'sms/coverage' or
                        Request::path() == 'sms/manage-coverage/' . view_id() or
                        Request::path() == 'sms/add-operator/' . view_id() or
                        Request::path() == 'sms/view-operator/' . view_id() or
                        Request::path() == 'sms/manage-operator/' . view_id()) class="active" @endif>
                    <a href={{ url('sms/coverage') }}><span class="menu-thumb"><i class="fa fa-wifi"></i></span><span
                            class="menu-text">{{ language_data('Coverage') }}
                            / {{ language_data('Routing') }}</span> </a>
                </li>




                {{-- Administrators --}}
                <li class="has-sub @if (Request::path() == 'administrators/all' or
                        Request::path() == 'administrators/manage/' . view_id() or
                        Request::path() == 'administrators/role' or
                        Request::path() == 'administrators/set-role/' . view_id()) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-user"></i></span><span
                            class="menu-text">{{ language_data('Administrators') }}</span> <span
                            class="arrow"></span></a>
                    <ul class="sub">
                        <li @if (Request::path() == 'administrators/all' or Request::path() == 'administrators/manage/' . view_id()) class="active" @endif>
                            <a href={{ url('administrators/all') }}><span class="menu-thumb"><i
                                        class="fa fa-user"></i></span><span
                                    class="menu-text">{{ language_data('Administrators') }}</span> </a>
                        </li>

                        <li @if (Request::path() == 'administrators/role' or Request::path() == 'administrators/set-role/' . view_id()) class="active" @endif>
                            <a href={{ url('administrators/role') }}><span class="menu-thumb"><i
                                        class="fa fa-user-secret"></i></span><span
                                    class="menu-text">{{ language_data('Administrator Roles') }}</span> </a>
                        </li>

                    </ul>
                </li>




                {{-- Setting --}}
                <li class="has-sub @if (Request::path() == 'settings/general' or
                        Request::path() == 'settings/localization' or
                        Request::path() == 'settings/language-settings' or
                        Request::path() == 'settings/language-settings-translate/' . view_id() or
                        Request::path() == 'settings/language-settings-manage/' . view_id() or
                        Request::path() == 'settings/payment-gateways' or
                        Request::path() == 'settings/payment-gateway-manage/' . view_id() or
                        Request::path() == 'settings/background-jobs' or
                        Request::path() == 'settings/purchase-code') sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-thumb"><i class="fa fa-cogs"></i></span><span
                            class="menu-text">{{ language_data('Settings') }}</span> <span class="arrow"></span></a>
                    <ul class="sub">

                        <li @if (Request::path() == 'settings/general') class="active" @endif><a
                                href={{ url('settings/general') }}><span class="menu-thumb"><i
                                        class="fa fa-cog"></i></span><span
                                    class="menu-text">{{ language_data('System Settings') }}</span></a></li>


                        <li @if (Request::path() == 'settings/localization') class="active" @endif><a
                                href={{ url('settings/localization') }}><span class="menu-thumb"><i
                                        class="fa fa-globe"></i></span><span
                                    class="menu-text">{{ language_data('Localization') }}</span> </a></li>


                        <li @if (Request::path() == 'settings/language-settings' or
                                Request::path() == 'settings/language-settings-manage/' . view_id() or
                                Request::path() == 'settings/language-settings-translate/' . view_id()) class="active" @endif>
                            <a href={{ url('settings/language-settings') }}><span class="menu-thumb"><i
                                        class="fa fa-language"></i></span><span
                                    class="menu-text">{{ language_data('Language Settings') }}</span> </a>
                        </li>

                        <li @if (Request::path() == 'settings/payment-gateways' or Request::path() == 'settings/payment-gateway-manage/' . view_id()) class="active" @endif>
                            <a href={{ url('settings/payment-gateways') }}><span class="menu-thumb"><i
                                        class="fa fa-paypal"></i></span><span
                                    class="menu-text">{{ language_data('Payment Gateways') }}</span> </a>
                        </li>

                        <li @if (Request::path() == 'settings/background-jobs') class="active" @endif><a
                                href={{ url('settings/background-jobs') }}><span class="menu-thumb"><i
                                        class="fa fa-clock-o"></i></span><span
                                    class="menu-text">{{ language_data('Background Jobs') }}</span> </a></li>



                    </ul>
                </li>

                {{-- Update Application --}}




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
    {{-- Global JavaScript End --}}
    <script src="/assets/js/sweetalert.min.js"></script>
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
    </script>
    {{-- Custom JavaScript Start --}}

    @yield('script')

    {{-- Custom JavaScript End Here --}}
</body>

</html>
