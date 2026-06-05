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
        body {
            background: #eff9fe !important;
            width: 100%;
            height: 100%
        }

        .container {
            padding: 0.5in !important;

        }

        .table-right tr td {
            text-align: right !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="margin-bottom: 32px;">
            <table style="border:none;width:100%">
                <tr>
                    <td colspan="2">
                        <img style="height:auto;width:200px" src="{{ env('APP_URL') }}/assets/img/citrus-invoice.png">
                        <address style="font-style:normal;font-size:20px">
                            {!! app_config('Address') !!}
                        </address>
                    </td>
                    <td style="text-align:right">

                        <img style="height:auto;width:100px;" src="{{ env('APP_URL') . '/' . app_config('AppLogo') }}">

                    </td>
                </tr>
                <tr style="font-size:20px">
                    <td>
                        <h4 style="color:#2f7fc3">Receipt To: </h4>
                        <p style="font-style:normal;font-size:20px;margin-bottom:0;">{{ $receipt->client->fname }}
                            {{ $receipt->client->lname }}</p>


                        <address style="font-style:normal;font-size:20px">
                            @if ($receipt->client->address1 || $receipt->client->address2)
                                {{ $receipt->client->address1 }} <br>
                                {{ $receipt->client->address2 }} <br>
                                {{ $receipt->client->state }}, {{ $receipt->client->city }} -
                                {{ $receipt->client->postcode }},
                                {{ $receipt->client->country }}</br>
                            @endif
                            Phone: {{ $receipt->client->phone }}<br>
                            Email: {{ $receipt->client->email }}
                        </address>

                    </td>
                    <td colspan="2" valign="top">
                        <table class="table-right" cellspacing="2" cellpadding="4"
                            style="width:100%; text-align: right;font-size:20px">
                            <tr>
                                <td style="width:80%">
                                    <b>Receipt No:</b>
                                </td>
                                <td>
                                    {{ $receipt->receipt_no }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width:80%">
                                    <b>Paid Date:</b>
                                </td>
                                <td>
                                    {{ get_date_format($receipt->datepaid) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-bottom: 32px">
            <table cellspacing="8" cellpadding="10"
                style="width:100%;text-align:right;border-collapse:collapse;font-size:16px;padding:5px;">
                <thead style="text-align:right">
                    <tr style="background: #2f7fc3;color:#fff;padding:4px;">
                        <th style="text-align:right">Qty</th>
                        <th style="text-align:right">Description</th>
                        <th style="text-align:right">Invoice No</th>
                        <th style="text-align:right">Unit Price</th>
                        <th style="text-align:right;padding-right:16px">Amount</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($items as $i)
                        <tr>
                            <td>{{ $i->quantity }}</td>
                            <td>{{ $i->description }}</td>
                            <td>{{ $i->invoice_no }}</td>
                            <td>
                                {{ $i->price }}
                            </td>
                            <td style="text-align:right;padding-right:16px">
                                {{ $i->amount }}</td>
                        </tr>
                    @endforeach
                    <tr>

                        <td colspan="4" style="font-weight:bold">
                            Subtotal
                        </td>
                        <td style="text-align:right;padding-right:16px">
                            {{ $receipt->subtotal }}
                        </td>
                    </tr>
                    <tr class="border-0 fw-bold " style="color:#ee496a">
                        <td colspan="4" style="font-weight:bold;">
                            Discount
                        </td>
                        <td style="text-align:right;padding-right:16px;color:#ee496a">
                            -{{ $receipt->discount }}
                        </td>
                    </tr>
                    <tr class="border-0 fw-bold text-danger">
                        <td colspan="4" style="font-weight:bold">
                            VAT Tax
                        </td>
                        <td style="text-align:right;padding-right:16px">
                            +{{ $receipt->tax }}
                        </td>
                    </tr>
                    </tr>
                    <tr class="border-0 fw-bold">
                        <td colspan="4" style="font-weight:bold">
                            Transaction Fee
                        </td>
                        <td style="padding-right:16px">
                            +{{ $receipt->trans_amount }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="font-weight:bold;color:#2f7fc3;">
                            Total
                        </td>
                        <td style="text-align:right;padding-right:16px">
                            <b>{{ app_config('CurrencyCode') }} {{ $receipt->total }}</b>
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
                        <p style="font-size:20px"> Payment is due immediately with total amount.</p>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>
