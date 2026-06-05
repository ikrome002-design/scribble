<style>
    .table tbody tr td {
        border-bottom: 0px solid rgba(230, 230, 230, 0.7);
        font-size: 20px;
        padding: 4px;
        background: none;
    }
</style>


<div style="background: #eff9fe !important; width:100%;">
    <div>
        <table class="table table-ultra-responsive" style="border:none;background: #eff9fe ">
            <tr>
                <td colspan="2">
                    <img style="height:auto;width:200px" src="/assets/img/citrus-invoice.png">
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
                    <h4 style="color:#2f7fc3">Receipt To: </h4>
                    <p style="font-style:normal;font-size:20px;margin-bottom:0;">{{ $receipt->client->fname }}
                        {{ $receipt->client->lname }}</p>


                    <address style="font-style:normal;">
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
                <td colspan="2">
                    <table cellspacing="0" cellpadding="0" style="width:100%; text-align: right;font-size:20px">
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
            style="width:100%;text-align:right;border-collapse:collapse;font-size:20px;padding:5px;">
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
                <tr class="border-0 fw-bold ">
                    <td colspan="4" style="font-weight:bold;color:#ee496a">
                        Discount
                    </td>
                    <td style="text-align:right;padding-right:16px;color:#ee496a">
                        -{{ $receipt->discount }}
                    </td>
                </tr>
                <tr class="border-0 fw-bold">
                    <td colspan="4" style="font-weight:bold">
                        VAT Tax
                    </td>
                    <td style="text-align:right;padding-right:16px">
                        +{{ $receipt->tax }}
                    </td>
                </tr>
                <tr class="border-0 fw-bold">
                    <td colspan="4" style="font-weight:bold">
                        Transaction Fee
                    </td>
                    <td style="text-align:right;padding-right:16px">
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
