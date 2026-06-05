@extends('client')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">SMS Price Plans</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-5">
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
                                    <input type="number" value="{{ old('units') }}" class="form-control" name="units"
                                        id="units"><br>
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

                <div class="col-lg-7">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">SMS Price Plans</h3>
                        </div>
                        <div class="panel-body p-none">
                            <table class="table data-table table-hover">
                                <thead style="white-space: nowrap;">
                                    <tr>
                                        <th>{{ language_data('SL') }}#</th>
                                        <th>{{ language_data('Unit FROM') }}</th>
                                        <th>{{ language_data('Unit TO') }}</th>
                                        <th>{{ language_data('Plan type') }}</th>
                                        <th>Unit Price</th>
                                        <th>{{ language_data('Transaction Fee') }}</th>
                                        <th>{{ language_data('Discount Type') }}</th>
                                        <th>{{ language_data('Apply Discount') }}</th>
                                        <th>{{ language_data('Discount amount') }}</th>
                                        <th>{{ language_data('Goverment Charges Type') }}</th>
                                        <th>{{ language_data('Apply Goverment charges') }}</th>
                                        <th>{{ language_data('Goverment Charges Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody style="white-space: nowrap;">

                                    @foreach ($smsPlans as $airtime)
                                        <tr>
                                            <td data-label="SL">{{ $loop->iteration }}</td>
                                            <td data-label="unit_from">
                                                <p>{{ $airtime->unit_from }}</p>
                                            </td>
                                            <td data-label="unit_to">
                                                <p>{{ $airtime->unit_to }}</p>
                                            </td>

                                            <td data-label="plan_type">
                                                <p>{{ $airtime->plan->name }}</p>
                                            </td>
                                            <td data-label="price">
                                                <p>KES {{ $airtime->price }}</p>
                                            </td>
                                            <td data-label="transaction_fee">
                                                <p>{{ $airtime->transaction_fee }}%</p>
                                            </td>

                                            @if ($airtime->discount_type == 2)
                                                <td data-label="discount_type">
                                                    <p>{{ language_data('fixed') }}</p>
                                                </td>
                                            @elseif($airtime->discount_type == 3)
                                                <td data-label="discount_type">
                                                    <p>{{ language_data('No Discount') }}</p>
                                                </td>
                                            @else
                                                <td data-label="discount_type">
                                                    <p>{{ language_data('percent') }}</p>
                                                </td>
                                            @endif

                                            @if ($airtime->apply_discount == 1)
                                                <td data-label="apply_discount">
                                                    <p>{{ language_data('one time') }}</p>
                                                </td>
                                            @elseif($airtime->apply_discount == 2)
                                                <td data-label="apply_discount">
                                                    <p>{{ language_data('recurring') }}</p>
                                                </td>
                                            @else
                                                <td data-label="apply_discount">
                                                    <p>{{ language_data('first purchase only') }}</p>
                                                </td>
                                            @endif

                                            <td data-label="discount_amt">
                                                <p>KES {{ $airtime->discount_amount }}</p>
                                            </td>

                                            @if ($airtime->govt_charges_type == 2)
                                                <td data-label="govt_charges_type">
                                                    <p>{{ language_data('fixed') }}</p>
                                                </td>
                                            @else
                                                <td data-label="govt_charges_type">
                                                    <p>{{ language_data('percent') }}</p>
                                                </td>
                                            @endif

                                            @if ($airtime->apply_govt_charges == 1)
                                                <td data-label="apply_govt_charges">
                                                    <p>{{ language_data('Tax') }}</p>
                                                </td>
                                            @else
                                                <td data-label="apply_govt_charges">
                                                    <p>{{ language_data('other charges') }}</p>
                                                </td>
                                            @endif


                                            <td data-label="govt_charges_amt">
                                                <p>KES {{ $airtime->govt_charges_amt }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                $('#errors').html('')
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
                            url: "/user/sms/sms-price-plan/calculate",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                units: units,
                            },
                            success: function(res) {
                                console.log(res)
                                if (!res.error || !res) {
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
    <script>
        $(document).ready(function() {

            $('.data-table').DataTable({
                language: {
                    url: '{!! url(
                        'assets/libs/data-table/i18n/' . get_language_code(Auth::guard('client')->user()->lan_id)->language . '.lang',
                    ) !!}'
                },
                scrollX: true
            })


        });
    </script>
@endsection
