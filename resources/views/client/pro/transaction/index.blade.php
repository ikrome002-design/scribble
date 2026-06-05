@extends('pro')

@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/datatables.net-select-bs5/css/select.bootstrap5.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.2.0/css/dataTables.dateTime.min.css">
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            @if (isset($sub->business_name))
                <h2 class="page-title"> {{ $sub->business_name }} ({{ $sub->shortcode }})</h2>
            @elseif (isset($staff))
                <h2 class="page-title"> Work History for Transactions - {{ $staff->fname }} {{ $staff->lname }}
                    ({{ $staff->email }}) </h2>
            @else
                <h2 class="page-title">All Transactions</h2>
            @endif
        </div>

        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="text-right text-primary">
                <p class="fs-5"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    Only shows data for active subscriptions
                </p>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    @if (isset($staff))
                    @else
                        <div class="card text-center"
                            style="width: 25rem; margin: 0 auto; margin-bottom: 15px; border: 1px solid black; padding: 12px; background: aliceblue;">
                            <h3 class="card-title">Summary</h3>
                            <div class="card-body">
                                <h4>Today Sales</h4>
                                <h5>KES {{ number_format($todaySales, 2) }}</h5>
                                <hr>
                                <h4>This Month Sales</h4>
                                <h5>KES {{ number_format($monthSales, 2) }}</h5>
                                <hr>
                                <h4>Total Sales</h4>
                                <h5>KES {{ number_format($totalSales, 2) }}</h5>
                            </div>
                    @endif
                </div>
                <div class="panel">
                    <div class="panel-heading">
                        <h4>Search by dates</h4>
                        <table border="0" cellspacing="5" cellpadding="5">
                            <tbody>
                                <tr>
                                    <form id="search-date">
                                        <td>Start date:</td>
                                        <td><input type="datetime-local" id="start_date" name="start_date"></td>
                                        <td>End date:</td>
                                        <td><input type="datetime-local" id="end_date" name="end_date"></td>
                                        <td><input type="reset" class="btn-complete" value="Reset Dates"></td>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-body p-none">

                        {{ $dataTable->table() }}

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
    @include('partials.scripts')

    {{ $dataTable->scripts() }}

    <script>
        $(document).ready(function() {


            // Refilter the table

            $('#start_date, #end_date').on('change', function() {
                $('#ShortCodeTransaction-table').DataTable().ajax.reload();

            });
        });
    </script>
@endsection
