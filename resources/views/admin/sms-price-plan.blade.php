@extends('admin')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">{{ language_data('SMS Price Plan') }}</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ language_data('SMS Price Plan') }}</h3>
                        </div>

                        <a class="btn btn-success btn-xs" style="margin-bottom : 6px; margin-left : 6px;"
                            href="{{ url('sms/add-price-plan') }}"><i class="fa fa-plus"></i>
                            {{ language_data('ADD SMS PRICE PLAN') }}</a>


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
                                        <th>{{ language_data('Client Group') }}</th>

                                        <th>{{ language_data('Status') }}</th>
                                        <th>{{ language_data('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody style="white-space: nowrap;">

                                    @if (isset($price_plan) && count($price_plan) > 0)
                                        @foreach ($price_plan as $price_plans)
                                            <tr>
                                                <td data-label="SL">{{ $loop->iteration }}</td>
                                                <td data-label="unit_from">
                                                    <p>{{ $price_plans->unit_from }}</p>
                                                </td>
                                                <td data-label="unit_to">
                                                    <p>{{ $price_plans->unit_to }}</p>
                                                </td>

                                                <td data-label="plan_type">
                                                    <p>{{ $price_plans->plan->name }}</p>
                                                </td>
                                                <td data-label="price">
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
                                                <td data-label="client_group">
                                                    @if (!$price_plans->client_group_id)
                                                        <p>All</p>
                                                    @else
                                                        <p>{{ $price_plans->clientGroup->group_name }}</p>
                                                    @endif
                                                </td>

                                                <td data-label="status">
                                                    <p>{{ $price_plans->status }}</p>
                                                </td>
                                                <td data-label="Actions">
                                                    <a class="btn btn-complete btn-xs"
                                                        href="{{ url('sms/edit-sms-price-plan/' . $price_plans->id) }}"><i
                                                            class="fa fa-edit"></i> {{ language_data('Manage') }}</a>
                                                    <a class="btn btn-danger btn-xs cdelete" id="{{ $price_plans->id }}"
                                                        href="{{ 'sms/delete-sms-price-plan/' . $price_plans->id }}"> <i
                                                            class="fa fa-trash"></i> {{ language_data('Delete') }}</a>
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

            /*For Delete Price Plan*/
            $("body").delegate(".cdelete", "click", function(e) {
                e.preventDefault();
                var id = this.id;
                bootbox.confirm("{!! language_data('Are you sure') !!}?", function(result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = _url + "/sms/delete-sms-price-plan/" + id;
                    }
                });
            });

        });
    </script>
@endsection
