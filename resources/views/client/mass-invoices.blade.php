@extends('client')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Unpaid Invoices</h2>
        </div>
        <div class="p-30 p-t-none p-b-none" style="background-color: #eff9fe !important;">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="btn-group pull-right" aria-label="...">
                                @if ($inv->status != 'Paid' && $inv->duedate >= date('Y-m-d'))
                                    <a href="#" data-toggle="modal" data-target="#pay-invoice"
                                        class="btn btn-success  btn-sm pay-invoice"><i class="fa fa-check"></i>
                                        Pay for these unpaid invoices</a>
                                @endif
                                <a href="{{ url('user/invoices/mass-download-pdf/' . $inv->mass_invoice_no) }}"
                                    class="btn btn-pdf  btn-sm download-pdf"><i class="fa fa-file-pdf-o"></i>
                                    {{ language_data('PDF', Auth::guard('client')->user()->lan_id) }}
                                </a>
                            </div>
                            <h3 class="panel-title">
                                Unpaid Invoices
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @include('payments.invoice')
                </div>
            </div>

        </div>
    </section>

    <!-- pay invoice  -->
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
                                <input type="text" value="{{ $client->phone }}" class="form-control" id="phone_number"
                                    placeholder="0700000000">
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
                                    <b>{{ $inv->mass_invoice_no }}</b>
                                </li>
                                <li>Enter the Amount <b> KES {{ round($inv->total) }}</b></li>
                                <li>Enter your M-Pesa PIN and send</li>
                                <li>You will receive a confirmation SMS from M-Pesa</li>
                            </ol>
                            <div class="mb-3">
                                <button type="button" id="complete-btn" data-type="Mass" class="btn btn-info w-100 btn-sm">
                                    Check Payment
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
    <div class="modal fade" tabindex="-1" id="complete-modal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-body">

                    <h4 class="text-success" id="success-complete ">
                        The payment was successfully completed
                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- External Style Section --}}
@section('script')
    {!! Html::script('assets/libs/handlebars/handlebars.runtime.min.js') !!}
    {!! Html::script('assets/js/form-elements-page.js') !!}
    {!! Html::script('assets/libs/data-table/datatables.min.js') !!}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            $('#pay-stk').click(function() {
                $('.errors, #success-stk-push').html('')
                var data = {
                    phone_number: $('#phone_number').val(),
                }
                var btn_text = $(this).text()
                $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-pulse"></i>')
                $.ajax({
                    url: "/invoice/pay-mass/{{ $inv->mass_invoice_no }}",
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
                $('.errors, #success-stk-push').html('')
                var btn_text = $(this).text()
                $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-pulse"></i>')
                var data = {
                    invoice_type: $(this).attr('data-type'),
                }
                $.ajax({
                    url: "/invoice/complete/{{ $inv->mass_invoice_no }}",
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
