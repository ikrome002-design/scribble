@extends('staff')

@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
    @include('partials.styles')
@endsection

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            @if (isset($sub->business_name))
                <h2 class="page-title">Visitors ({{ $sub->business_name }})</h3>
                @elseif(isset($bus))
                    <h2 class="page-title">Visitors ({{ $bus->business_name }} {{ $bus->proSubscription->business_name }})
                        </h3>
                    @elseif (isset($staff))
                        Work History for Visitors - {{ $staff->fname }} {{ $staff->lname }} ({{ $staff->email }})
                    @else
                        <h2 class="page-title">All Visitors</h3>
            @endif

        </div>
        <div class="p-30 p-t-none p-b-none">
            <div class="text-right text-primary">
                <p class="fs-5"><i class="fa fa-info-circle" aria-hidden="true"></i>Only shows data for active subscriptions
                </p>
            </div>
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <form id="search-date">
                            <h4>Search by dates</h4>
                            <table border="0" cellspacing="5" cellpadding="5">
                                <tbody>
                                    <tr>
                                        <td>Check In</td>
                                        <td>Start date:</td>
                                        <td><input type="datetime-local" id="check_in_start_date"
                                                name="check_in_start_date">
                                        </td>
                                        <td>End date:</td>
                                        <td><input type="datetime-local" id="check_in_end_date" name="check_in_end_date">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Check Out</td>
                                        <td>Start date:</td>
                                        <td><input type="datetime-local" id="check_out_start_date"
                                                name="check_out_start_date">
                                        </td>
                                        <td>End date:</td>
                                        <td><input type="datetime-local" id="check_out_end_date" name="check_in_end_date">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><input type="reset" class="btn-complete" value="Reset Dates"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>


                        <div class="panel-heading">
                            <h3 class="panel-title">Visitors</h3>
                        </div>



                        <a class="btn btn-success btn-sm btn-xs" style="margin-bottom : 6px; margin-left : 6px;"
                            href="{{ '/visitors/create' }}{{ isset($sub->id) ? '?sub_id=' . $sub->id : '' }}">
                            <i class="fa fa-plus"></i>
                            Add Visitor
                        </a>

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
        // $('body').delegate(".delete", "click", function(e) {
        //     e.preventDefault();
        //     var url = this.href;
        //     Swal.fire({
        //         text: 'Are you sure, you want to delete this visitor',
        //         confirmButtonText: 'Ok',
        //         showCancelButton: true,

        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $(`<form method="post" action=${url}>
    //                        @csrf
    //                        @method('DELETE')
    //                     </form>`).appendTo('body').submit();
        //         }
        //     })

        // })
        $('body').delegate(".checkout-visitor", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            Swal.fire({
                text: 'Are you sure, you want to checkout this visitor',
                confirmButtonText: 'Submit',
                showCancelButton: true,
                html: `<form action='${url}' method='post' id="checkout-visitor-form">
                <label>Check out time</label>
                <input type="datetime-local" name="check_out_time" class="swal2-input" value=${convertToLocal()}><br>
                </form>
                  `,

            }).then((result) => {
                if (result.isConfirmed) {
                    $('#checkout-visitor-form').submit()
                }
            })

        })
    </script>

    <script>
        $(document).ready(function() {
            $('#check_in_start_date, #check_in_end_date,#check_out_start_date, #check_out_end_date').on('change',
                function() {
                    $('#visitor-table').DataTable().ajax.reload();
                });
        });
    </script>
@endsection
