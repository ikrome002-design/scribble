@extends('client')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Packages</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Packages</h3>
                        </div>
                        <div class="panel-body p-none">
                            <table class="table data-table table-hover">
                                <thead style="white-space: nowrap;">
                                    <tr>
                                        <th>{{ language_data('SL') }}#</th>
                                        <th>Name</th>
                                        <th>Current Plan Status</th>
                                        <th>Recurring Date</th>
                                        <th>Amount</th>
                                        <th>Transaction Fee %</th>
                                        <th>{{ language_data('Discount Type') }}</th>
                                        <th>{{ language_data('Apply Discount') }}</th>
                                        <th>{{ language_data('Discount amount') }}</th>
                                        <th>{{ language_data('Goverment Charges Type') }}</th>
                                        <th>{{ language_data('Apply Goverment charges') }}</th>
                                        <th>{{ language_data('Goverment Charges Amount') }}</th>
                                        <th>Price(total)</th>
                                        <th>{{ language_data('Popular') }}</th>
                                        <th>{{ language_data('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody style="white-space: nowrap;">

                                    @if ($packages)
                                        @foreach ($packages as $price_plans)
                                            <tr>
                                                <td data-label="SL">{{ $loop->iteration }}</td>
                                                <td data-label="name">
                                                    <p>{{ $price_plans->name }}</p>
                                                </td>
                                                <td>
                                                    @if ($price_plans->id == $client->plan_id)
                                                        {{ $client->plan_status }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($price_plans->id == $client->plan_id)
                                                        {{ $client->plan_recurring_date }}
                                                    @endif
                                                </td>
                                                <td data-label="amount">
                                                    <p>KES {{ $price_plans->price }}</p>
                                                </td>
                                                <td data-label="transaction_fee">
                                                    <p>{{ $price_plans->transaction_fee }}%</p>
                                                </td>

                                                @if ($price_plans->discount_type == 2)
                                                    <td data-label="discount_type">
                                                        <p>{{ language_data('fixed') }}</p>
                                                    </td>
                                                @elseif($price_plans->discount_type == 3)
                                                    <td data-label="discount_type">
                                                        <p>{{ language_data('No Discount') }}</p>
                                                    </td>
                                                @else
                                                    <td data-label="discount_type">
                                                        <p>{{ language_data('percent') }}</p>
                                                    </td>
                                                @endif
                                                @if ($price_plans->apply_discount == 1)
                                                    <td data-label="apply_discount">
                                                        <p>{{ language_data('one time') }}</p>
                                                    </td>
                                                @elseif($price_plans->apply_discount == 2)
                                                    <td data-label="apply_discount">
                                                        <p>{{ language_data('recurring') }}</p>
                                                    </td>
                                                @else
                                                    <td data-label="apply_discount">
                                                        <p>{{ language_data('first purchase only') }}</p>
                                                    </td>
                                                @endif
                                                <td data-label="discount_amt">
                                                    <p>{{ $price_plans->discount_amount }}</p>
                                                </td>

                                                @if ($price_plans->govt_charges_type == 2)
                                                    <td data-label="govt_charges_type">
                                                        <p>{{ language_data('fixed') }}</p>
                                                    </td>
                                                @else
                                                    <td data-label="govt_charges_type">
                                                        <p>{{ language_data('percent') }}</p>
                                                    </td>
                                                @endif

                                                @if ($price_plans->apply_govt_charges == 1)
                                                    <td data-label="apply_govt_charges">
                                                        <p>{{ language_data('Tax') }}</p>
                                                    </td>
                                                @else
                                                    <td data-label="apply_govt_charges">
                                                        <p>{{ language_data('other charges') }}</p>
                                                    </td>
                                                @endif


                                                <td data-label="govt_charges_amt">
                                                    <p>{{ $price_plans->govt_charges_amt }}</p>
                                                </td>
                                                <td data-label="total">
                                                    <p>{{ $price_plans->total }}</p>
                                                </td>

                                                <td data-label="popular">
                                                    <p>{{ $price_plans->popular }}</p>
                                                </td>

                                                <td data-label="Actions">
                                                    @if ($price_plans->id == $client->plan_id)
                                                        @if ($days_remaining < 2)
                                                            <a class="btn btn-complete btn-xs change-package"
                                                                href="/user/package/change/{{ $price_plans->id }}"><i
                                                                    class="fa fa-shopping-cart"></i>Click
                                                                to generate new invoice</a>
                                                        @else
                                                            <a class="btn btn-complete btn-xs disabled"
                                                                href="javascript:void(0)"></i>
                                                                Current package
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a class="btn btn-complete btn-xs change-package"
                                                            href="/user/package/change/{{ $price_plans->id }}"><i
                                                                class="fa fa-shopping-cart"></i> Change package</a>
                                                    @endif
                                                    <a class="btn btn-primary btn-xs "
                                                        href="{{ url('user/package/features/' . $price_plans->id) }}"><i
                                                            class="fa fa-eye"></i>View Features</a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif





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
                    url: '{!! url('assets/libs/data-table/i18n/' . get_language_code()->language . '.lang') !!}'
                },
                scrollX: true
            });

            /*For change package*/
            $("body").delegate(".change-package", "click", function(e) {
                e.preventDefault();
                url = this.href
                bootbox.confirm(`Are you sure you want to change the package or generate new invoice? 
                Remember if you have other Scribble subcriptions which have not opted out will be included.`,
                    function(result) {
                        if (result) {

                            window.location.href = url;
                        }
                    });
            });

        });
    </script>
@endsection
