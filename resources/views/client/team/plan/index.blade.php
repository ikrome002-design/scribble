@extends('client')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Team Link Pricing</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Team Pricing </h3>
                        </div>
                        @if (count($sub) == 0)
                            <a class="btn btn-success btn-xs" style="margin-bottom : 6px; margin-left : 6px;"
                                href="/team/subscription/create"><i class="fa fa-plus"></i>
                                Add Team Link Subscription</a>
                        @endif
                        <div class="panel-body p-none">
                            <table class="table data-table table-hover">
                                <thead style="white-space: nowrap;">
                                    <tr>
                                        <th>SL#</th>
                                        <th>Name</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Transaction Fee %</th>
                                        <th>Discount Type</th>
                                        <th>Apply Discount</th>
                                        <th>Discount amount</th>
                                        <th>Goverment Charges Type</th>
                                        <th>Apply Goverment charges</th>
                                        <th>Goverment Charges Amount</th>
                                        <th>Digital Tax (%)</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody style="white-space: nowrap;">

                                    @foreach ($teamplans as $price_plans)
                                        <tr>
                                            <td data-label="SL">{{ $loop->iteration }}</td>
                                            <td data-label="name">
                                                <p>{{ $price_plans->name }}</p>
                                            </td>
                                            <td data-label="price">
                                                <p>{{ $price_plans->plan->name ?? '-' }}</p>
                                            </td>
                                            <td data-label="price">
                                                <p>KES {{ $price_plans->price }}</p>
                                            </td>
                                            <td data-label="transaction_fee">
                                                <p>{{ $price_plans->transaction_fee }}%</p>
                                            </td>

                                            @if ($price_plans->discount_type == 2)
                                                <td data-label="discount_type">
                                                    <p>fixed</p>
                                                </td>
                                            @elseif($price_plans->discount_type == 3)
                                                <td data-label="discount_type">
                                                    <p>No Discount</p>
                                                </td>
                                            @else
                                                <td data-label="discount_type">
                                                    <p>percent</p>
                                                </td>
                                            @endif

                                            @if ($price_plans->apply_discount == 1)
                                                <td data-label="apply_discount">
                                                    <p>one time</p>
                                                </td>
                                            @elseif($price_plans->apply_discount == 2)
                                                <td data-label="apply_discount">
                                                    <p>recurring</p>
                                                </td>
                                            @else
                                                <td data-label="apply_discount">
                                                    <p>first purchase only</p>
                                                </td>
                                            @endif

                                            <td data-label="discount_amt">
                                                <p>{{ $price_plans->discount_amount }}</p>
                                            </td>

                                            @if ($price_plans->govt_charges_type == 2)
                                                <td data-label="govt_charges_type">
                                                    <p>fixed</p>
                                                </td>
                                            @else
                                                <td data-label="govt_charges_type">
                                                    <p>percent</p>
                                                </td>
                                            @endif

                                            @if ($price_plans->apply_govt_charges == 1)
                                                <td data-label="apply_govt_charges">
                                                    <p>Tax</p>
                                                </td>
                                            @else
                                                <td data-label="apply_govt_charges">
                                                    <p>other charges</p>
                                                </td>
                                            @endif


                                            <td data-label="govt_charges_amt">
                                                <p>{{ $price_plans->govt_charges_amt }}</p>
                                            </td>

                                            <td data-label="total">
                                                <p>{{ $price_plans->digital_tax }}%</p>
                                            </td>
                                            <td data-label="total">
                                                <p>{{ $price_plans->notes }}</p>
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

            $('.data-table').DataTable({
                language: {
                    url: '{!! url('assets/libs/data-table/i18n/English.lang') !!}'
                },
                responsive: true
            });



        });
    </script>
@endsection
