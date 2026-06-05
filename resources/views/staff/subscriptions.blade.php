@extends('staff')

@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Pro Subscriptions & Mpesa Paybill/Till Number</h2>
        </div>
        <div class="card text-center"
            style="width: 25rem; margin: 0 auto; margin-bottom: 15px; border: 1px solid black; padding: 12px; background: aliceblue;">
            <h3 class="card-title">Summary</h3>
            <div class="card-body">
                <h4>Today Sales</h4>
                <h5>KES {{ number_format($todaySales, 2) }}</h5>
                <hr>
                <h4>This Month Sales</h4>
                <h5>KES {{ number_format($monthSales, 2) }}</h5>
                @if (auth('staff')->user()->role == 'Manager')
                    <hr>
                    <h4>Total Sales</h4>
                    <h5>KES {{ number_format($totalSales, 2) }}</h5>
                @endif
            </div>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Pro Subscriptions & Mpesa Paybill/Till Number</h3>
                        </div>
                        <div class="panel-body p-none">
                            <table class="table data-table table-hover">
                                <thead style="white-space: nowrap;">
                                    <tr>
                                        <th>SL#</th>
                                        <th>Business Name</th>
                                        <th>Shortcode</th>
                                        <th>Type</th>
                                        <th>Subscription Status</th>
                                        <th>Mpesa Integration Status</th>
                                        <th>Opted Out</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody style="white-space: nowrap;">

                                    @foreach ($subs as $s)
                                        <tr>
                                            <td data-label="SL">{{ $loop->iteration }}</td>
                                            </td>
                                            <td data-label="email">
                                                <p>{{ $s->business_name }}</p>
                                            </td>
                                            <td data-label="email">
                                                <p>{{ $s->shortcode }}</p>
                                            </td>
                                            <td data-label="shortcode">
                                                <p>{{ $s->type }}</p>
                                            </td>
                                            <td data-label="shortcode">
                                                <p>{{ $s->sub_status }}</p>
                                            </td>
                                            <td data-label="shortcodd_status">
                                                <p>{{ $s->shortcode_status }}</p>
                                            </td>
                                            <td data-label="opted_out">
                                                <p>{{ $s->opted_out === 'Yes' ? $s->opted_out . '( ' . $s->opted_out_date . ' )' : $s->opted_out }}
                                                </p>
                                            </td>

                                            <td data-label="Actions">
                                                <a class="btn btn-complete btn-xs"
                                                    href="/prosubscriptions/{{ $s->id }}"><i
                                                        class="fa fa-money"></i>Financial</a>
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
                    url: '{!! url('assets/libs/data-table/i18n/' . get_language_code()->language . '.lang') !!}'
                },
                scrollX: true
            });

            $("body").delegate(".generate-invoice", "click", function(e) {

                e.preventDefault();
                var url = this.href;
                bootbox.confirm("Are sure you want generate invoice", function(result) {
                    if (result) {
                        window.location.href = url;
                    }
                });
            });
            /*For Delete Price Plan*/
            $("body").delegate(".cdelete", "click", function(e) {
                e.preventDefault();
                var url = this.href;
                bootbox.confirm(
                    "Are you sure, you want to Deactivate your Scribble PRO Account? When successfully done you shall not be able to access it",
                    function(result) {
                        $.ajax({
                            url: '/user/generate/otp',
                            type: 'POST',
                            success: function(response) {}
                        })
                        if (result) {
                            bootbox.confirm(
                                ` <form action="${url}" id="otp-out" method="post">
                                @csrf
                                <div class="form-group">
                                            <label>Enter OTP Sent to email</label>
                                            <input type="number" placeholder="OTP"  class="form-control" required name="otp"
                                                >
                                        </div>
                                        <div class="form-group">
                                            <label>When do you plan be to be Inactive. 
                                                If choose now, all activities for this plan will stop now. Otherwise all activities will stop at end of the billing period</label> <br>
                                            <select name="opt_out_when" 
                                                required>
                                                <option value="now">
                                                  Now
                                                </option>
                                                <option value="end">
                                                    End of billing of period
                                                </option>
                                            </select>
                                        </div>
                                        </form>`,
                                function(result) {
                                    if (result)
                                        $('#otp-out').submit();

                                });
                        }
                    })
            });
        })
    </script>
@endsection
