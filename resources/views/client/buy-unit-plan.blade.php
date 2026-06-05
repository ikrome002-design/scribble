@extends('client')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">{{ language_data('Buy Unit', Auth::guard('client')->user()->lan_id) }}</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{ language_data('Recharge your account Online', Auth::guard('client')->user()->lan_id) }}
                            </h3>
                        </div>
                        <div class="panel-body">

                            <form role="form" method="post" action="">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>{{ language_data('Number of Units', Auth::guard('client')->user()->lan_id) }}</label>
                                    <input type="number" class="form-control" name="units" id="units">
                                </div>
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" readonly class="form-control" name="amount" id="amount">
                                </div>
                                <div class="form-group">
                                    <label>Discount</label>
                                    <input type="hidden" id="discount_type">

                                    <input type="text" class="form-control" name="discount" readonly id="discount">

                                </div>

                                <div class="form-group">
                                    <label>Goverment Charges</label>
                                    <input type="text" class="form-control" name="govt_charges" readonly
                                        id="govt_charges">
                                </div>
                                <div class="form-group">
                                    <label>{{ language_data('Transaction Fee', Auth::guard('client')->user()->lan_id) }}</label>
                                    <input type="text" class="form-control" readonly name="trans_fee" id="trans_fee">
                                </div>
                                <div class="form-group">
                                    <label>{{ language_data('Amount to Pay', Auth::guard('client')->user()->lan_id) }}</label>
                                    <input type="text" readonly class="form-control" readonly name="total"
                                        id="total">
                                </div>



                                <button type="submit" class="btn btn-success btn-sm pull-right purchase_button"><i
                                        class="fa fa-plus"></i>
                                    {{ language_data('Purchase Now', Auth::guard('client')->user()->lan_id) }} </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="panel p-30">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Summary
                            </h3>
                        </div>
                        <div class="panel-body ">
                            @if ($price_plan->bundle_name)
                                <p>Bundle Name: {{ $price_plan->bundle_name }}</p>
                            @endif

                            <p>Unit From: {{ $price_plan->unit_from }}</p>

                            <p>Unit To: {{ $price_plan->unit_to }}</p>

                            <p>Unit Price: {{ app_config('CurrencyCode') }} {{ $price_plan->price }}</p>

                            <p>Transaction Fee : {{ $price_plan->transaction_fee }}%</p>

                            <p> Discount Type:
                                @if ($price_plan->discount_type == 2)
                                    {{ language_data('fixed') }}
                            </p>
                        @elseif($price_plan->discount_type == 3)
                            {{ language_data('No Discount') }}</p>
                        @else
                            {{ language_data('percent') }}</p>
                            @endif

                            <td data-label="discount_amt">
                                <p> Discount Amount: {{ $price_plan->discount_amount }}
                                </p>
                            </td>
                            <p> Goverment Charges
                                {{ $price_plan->govt_charges_amt }} %</p>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
@endsection

{{-- External Style Section --}}
@section('script')
    {!! Html::script('assets/libs/handlebars/handlebars.runtime.min.js') !!}
    {!! Html::script('assets/js/form-elements-page.js') !!}
    {!! Html::script('assets/libs/data-table/datatables.min.js') !!}
    {!! Html::script('assets/js/bootbox.min.js') !!}

    <script>
        $(document).ready(function() {

            /*Transaction Loading*/

            var timer;



            // cal on the basis of purchase unit
            $('#units').on('keyup', function() {
                var units = $(this).val();
                timeout = setTimeout(function() {
                    if (units == "") {
                        $('#trans_fee').val("");
                        $('#discount').val("");
                        $('#govt_charges').val("");
                        $('#total').val("");
                        $('#amount').val("");
                    } else {

                        $.ajax({
                            type: 'get',
                            url: "{{ $url }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                units: units,
                            },
                            success: function(res) {
                                if (!res) {
                                    error: fun
                                    $('#trans_fee').val("");
                                    $('#discount').val("");
                                    $('#govt_charges').val("");
                                    $('#total').val("");
                                    $('#amount').val("");
                                }
                                $('#trans_fee').val(res.trans_amount);
                                $('#discount').val(res.discount);
                                $('#govt_charges').val(res.tax);
                                $('#total').val(res.price);
                                $('#amount').val(res.amount);

                            }

                        })

                    }
                }, 200);

            })

        });
    </script>
@endsection
