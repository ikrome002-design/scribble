<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ app_config('AppTitle') }}</title>
    <link rel="icon" type="image/x-icon" href="<?php echo asset(app_config('AppFav')); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ asset('/assets/home_pic/images/favicon-32x32.png') }}">
    <style>
        @page {
            background-color: #eff9fe;
            margin: 0;
        }

        body {
            background-color: #eff9fe;
            margin: 0;
            padding: 1in;
        }
    </style>
</head>

<body style="width:100%;height:100%;">

    <div style="margin-bottom: 32px">
        <table style="border:none;width:100%">
            <tr>
                <td colspan="2">
                    <img style="height:auto;width:200px" src="{{ public_path('assets/img/citrus-invoice.png') }}">
                    <address style="font-style:normal;font-size:20px">
                        {!! app_config('Address') !!}
                    </address>
                </td>
                <td style="text-align:right">

                    <img style="height:auto;width:100px;" src="<?php echo asset(app_config('AppLogo')); ?>">

                </td>

            </tr>
            <tr style="font-size:20px">
                <td>
                    <h4 style="color:#2f7fc3">Bill To: </h4>
                    <p style="font-style:normal;font-size:20px;margin-bottom:0;">{{ $client->fname }}
                        {{ $client->lname }}</p>

                    @if ($client->address1 || $client->address2)
                        <address style="font-style:normal;font-size:20px">
                            {{ $client->address1 }} <br>
                            {{ $client->address2 }} <br>
                            {{ $client->state }}, {{ $client->city }} - {{ $client->postcode }},
                            {{ $client->country }}</br>
                            Phone: {{ $client->phone }}<br>
                            Email: {{ $client->email }}
                        </address>
                    @endif
                </td>
                <td colspan="2" valign="top">
                    <table cellspacing="2" cellpadding="4" style="width:100%; text-align: right;font-size:20px">
                        <tr>
                            <td style="width:80%">
                                <b>Invoice No:</b>
                            </td>
                            <td>
                                {{ $inv->invoice_no }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width:80%">
                                <b>Invoice Status:</b>
                            </td>
                            <td>
                                {{ $inv->status }}
                            </td>
                        </tr>

                        @if ($inv->status == 'Paid')
                            <tr>
                                <td style="width:80%">
                                    <b>Paid Date:</b>
                                </td>
                                <td>
                                    {{ get_date_format($inv->datepaid) }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td style="width:80%">
                                <b>Invoice Date:</b>
                            </td>

                            <td>
                                {{ get_date_format($inv->created) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width:80%">
                                <b>Due Date:</b>
                            </td>
                            <td>
                                {{ get_date_format($inv->duedate) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-bottom: 32px">
        <table cellspacing="8" cellpadding="10"
            style="width:100%;text-align:right;border-collapse:collapse;font-size:20px;">
            <thead>
                <tr style="background: #2f7fc3;color:#fff">
                    <th>Qty</th>
                    <th>Description</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $inv->quantity }}</td>
                    <td>{{ $inv->description }}</td>
                    <td>{{ app_config('CurrencyCode') }}
                        {{ number_format($inv->total - 0.16 * $inv->total, 2, '.', '') }}
                    </td>

                    <td>
                        {{ number_format($inv->total - 0.16 * $inv->total, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight:bold">
                        Subtotal
                    </td>
                    <td>
                        {{ number_format($inv->total - 0.16 * $inv->total, 2, '.', '') }}
                    </td>
                </tr>
                <tr class="border-0 fw-bold">
                    <td colspan="3" style="font-weight:bold">
                        VAT Tax 16.0%
                    </td>
                    <td>
                        {{ number_format(0.16 * $inv->total, 2, '.', '') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight:bold;color:#2f7fc3">
                        Invoice Total
                    </td>
                    <td>
                        <b> {{ app_config('CurrencyCode') }} {{ number_format($inv->total, 2, '.', '') }}</b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <table>
            <tr>
                <td>
                    <h3 style="font-weight:bold;color:#2f7fc3;margin-bottom:3px">Terms & Conditions</h3>
                    <p style="font-size:20px"> Payment is due immediately.</p>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
