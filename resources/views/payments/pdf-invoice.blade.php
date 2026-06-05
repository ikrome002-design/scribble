<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
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
                        <h4 style="color:#2f7fc3">Bill To: </h4>
                        <p style="font-style:normal;font-size:20px;margin-bottom:0;">{{ $client->fname }}
                            {{ $client->lname }}</p>


                        <address style="font-style:normal;font-size:20px">
                            @if ($client->address1 || $client->address2)
                                {{ $client->address1 }} <br>
                                {{ $client->address2 }} <br>
                                {{ $client->state }}, {{ $client->city }} - {{ $client->postcode }},
                                {{ $client->country }}</br>
                            @endif
                            Phone: {{ $client->phone }}<br>
                            Email: {{ $client->email }}
                        </address>

                    </td>
                    <td colspan="2" valign="top">
                        <table class="table-right" cellspacing="2" cellpadding="4"
                            style="width:100%; text-align: right;font-size:20px">
                            <tr>
                                <td style="width:80%">
                                    <b>Invoice No:</b>
                                </td>
                                <td>
                                    {{ $inv->invoice_no ?? $inv->mass_invoice_no }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width:80%">
                                    <b>Invoice Status:</b>
                                </td>
                                <td>
                                    @if ($inv->status == 'Unpaid')
                                        <span class="label label-warning">Unpaid</span>
                                    @elseif($inv->status == 'Paid')
                                        <span class="label label-success">Paid</span>
                                    @elseif($inv->status == 'Cancelled')
                                        <span class="label label-danger">Cancelled</span>
                                    @else
                                        <span class="label label-info">Partially Paid</span>
                                    @endif
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
                    <tr style="background: #2f7fc3;color:#fff;text-align:right">
                        <th style="text-align: right">Qty</th>
                        <th style="text-align: right">Description</th>
                        <th style="text-align: right">Unit Price</th>
                        <th style="padding-right:16px;text-align:right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->description }}</td>
                            <td>
                                {{ $item->price }}
                            </td>
                            <td style="padding-right:16px">
                                {{ $item->amount }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" style="font-weight:bold">
                            Subtotal
                        </td>
                        <td style="padding-right:16px">
                            {{ $inv->subtotal }}
                        </td>
                    </tr>
                    <tr class="border-0 fw-bold" style="color:#ee496a">
                        <td colspan="3" style="font-weight:bold">
                            Discount {{ $inv->discount_type == 2 ? $inv->discount_amt . '%' : '' }}
                        </td>
                        <td style="padding-right:16px;color:#ee496a">
                            -{{ $inv->discount }}
                        </td>
                    </tr>
                    <tr class="border-0 fw-bold">
                        <td colspan="3" style="font-weight:bold">
                            VAT Tax
                        </td>
                        <td style="padding-right:16px">
                            +{{ $inv->tax }}
                        </td>
                    </tr>
                    <tr class="border-0 fw-bold">
                        <td colspan="3" style="font-weight:bold">
                            Transaction Fee
                        </td>
                        <td style="padding-right:16px">
                            +{{ $inv->trans_amount }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-weight:bold;color:#2f7fc3">
                            Invoice Total
                        </td>
                        <td style="padding-right:16px">
                            <b> {{ app_config('CurrencyCode') }} {{ $inv->total }}</b>
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
