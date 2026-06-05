@extends('client')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">{{ language_data('View Invoice', Auth::guard('client')->user()->lan_id) }}</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')

            <div class="panel">
                <div class="p-20" style="background-color: #eff9fe !important;">
                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="col-lg-6 col-md-3 col-sm-3 col-xs-12">
                            </div>
                            <div class="col-lg-6 col-md-3 col-sm-3 col-xs-12">


                                <div class="btn-group pull-right" aria-label="...">

                                    @if ($inv->status == 'Unpaid' || $inv->status == 'Partially Paid')
                                        <a href="#" data-toggle="modal" data-target="#pay-invoice"
                                            class="btn btn-success  btn-sm pay-invoice"><i class="fa fa-check"></i>
                                            {{ language_data('Pay', Auth::guard('client')->user()->lan_id) }}</a>
                                    @endif

                                    <a href="{{ url('user/invoices/download-pdf/' . $inv->invoice_no) }}"
                                        class="btn btn-pdf  btn-sm download-pdf"><i class="fa fa-file-pdf-o"></i>
                                        {{ language_data('PDF', Auth::guard('client')->user()->lan_id) }}</a>

                                    <br>
                                    <br>


                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">
                            @include('payments.invoice')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- pay invoice-->
        <div class="modal fade" id="pay-invoice" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
            aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">
                            Pay Invoice
                        </h4>

                    </div>
                    <div class="modal-body">
                        <p>Please choose preffered mode of Payment</p>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="my-2">STK Push</h5>
                                <p>Enter preffered Safaricom mobile phone number below
                                    and we will
                                    invoke payment to the provided number</p>
                                <div class="input-group m-10">
                                    <input type="text" value="{{ $client->phone }}" class="form-control"
                                        id="phone_number" placeholder="0700000000">
                                </div>
                                <div class="m-10">
                                    <button type="button" id="pay-stk" class="btn w-100 btn-sm btn-success">

                                        Pay Ksh {{ round($inv->total) }}
                                    </button>
                                </div>
                                <p id="success-stk-push" class="text-success"></p>
                                <div class="text-danger errors " id="stk-errors"></div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="my-2">Pay using Mpesa Paybill</h5>
                                <ol>
                                    <li>Go to M-Pesa on your phone</li>
                                    <li>Select Pay Bill option</li>
                                    <li>Enter the Business Number
                                        <b>{{ env('MPESA_BUSINESS_SHORT_CODE') }}</b>
                                    </li>
                                    <li>Enter the Account Number
                                        <b>{{ $inv->invoice_no }}</b>
                                    </li>
                                    <li>Enter the Amount <b> KES {{ round($inv->total) }}</b></li>
                                    <li>Enter your M-Pesa PIN and send</li>
                                    <li>You will receive a confirmation SMS from M-Pesa</li>
                                </ol>
                                <div class="mb-3">
                                    <button type="button" id="complete-btn" data-type="Single"
                                        class="btn btn-info w-100 btn-sm"> Check Payment
                                    </button>
                                </div>
                                <div class="text-danger errors " id="complete-error"></div>
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- success complete -->
        <div class="modal fade" tabindex="-1" id="complete-modal" role="dialog" data-backdrop="static"
            data-keyboard="false">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-body">

                        <h4 class="text-success" id="success-complete ">The payment was successfully
                            completed</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        @if (request()->session()->exists('new-pro-sub'))
            <div class="modal fade" tabindex="-1" id="new-pro-sub-modal" role="dialog" data-backdrop="static"
                data-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered ">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p>Dear {{ auth('client')->user()->lname }} {{ auth('client')->user()->lname }},</p>
                            <p>We have received your Scribble PRO application and we're excited to work on it as
                                quickly as possible. You will receive an email and notification on Scribble as soon as
                                your application is ready. If you have any questions or concerns, please don't hesitate
                                to contact us by raising a ticket on the Scribble platform or by sending an email to
                                scribble.support@citrus.co.ke.</p>
                            <p>Thank you for subscribing to Scribble PRO for the most advanced Bulk SMS service
                                ever.</p>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            {{ request()->session()->forget('new-pro-sub') }}
        @endif


    </section>
@endsection

{{-- External Style Section --}}
@section('script')
    {!! Html::script('assets/libs/handlebars/handlebars.runtime.min.js') !!}
    {!! Html::script('assets/js/form-elements-page.js') !!}

    <script>
        if ($('#new-pro-sub-modal').length) {
            $('#new-pro-sub-modal').modal('show');
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            $('#pay-stk').click(function() {
                $('.errors').html('')
                var data = {
                    phone_number: $('#phone_number').val(),
                }
                var btn_text = $(this).text()
                $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-pulse"></i>')
                $.ajax({
                    url: "/invoice/pay-single/{{ $inv->invoice_no }}",
                    type: 'post',
                    data: data,
                    dataType: 'json',
                }).then(function(res) {
                    $('#pay-stk').prop('disabled', false).html(btn_text)
                    if (res.hasOwnProperty('errors')) {
                        for (let i in res.errors) {
                            $('#stk-errors').html(res.errors[i] + '<br>')
                        }
                    } else {
                        $('#success-stk-push').html(res);
                    }
                })
            })

            $('#complete-btn').click(function() {
                $('.errors').html('')
                var btn_text = $(this).text()
                $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-pulse"></i>')
                var data = {
                    invoice_type: $(this).attr('data-type'),
                }
                $.ajax({
                    url: "/invoice/complete/{{ $inv->invoice_no }}",
                    type: 'post',
                    data: data,
                    dataType: 'json',
                }).then(function(res) {
                    $('#complete-btn').prop('disabled', false).html(btn_text)
                    if (res.hasOwnProperty('errors')) {
                        for (let i in res.errors) {
                            $('#complete-error').html(res.errors[i] + '<br>')
                        }
                    } else {
                        $('#success-complete').html(res);
                        $('#complete-modal').modal('show');

                    }
                })
            })
            $('#complete-modal').on('hidden.bs.modal', function() {
                location.reload();
            })


        })
    </script>
@endsection
