@extends('admin-pro')

@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
    @include('partials.styles')
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">All Transactions</h2>
        </div>

        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
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
