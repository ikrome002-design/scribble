@extends('register')


@section('body')
    <div class="container">
        <div class="panel py-5">
            <div class="panel-body wizard-content">
                <div class="row">
                    <div class="col-md-8 m-auto">
                        @if (!$inv)
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @else
                            <h3 class="text-info">Pay for this invoice for your plan to be effective</h3>
                            <div class="mb-3 text-end">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#pay-invoice"
                                    class="btn btn-success  btn-sm pay-invoice"><i class="fa fa-check"></i>Pay</a>
                            </div>
                            @include('payments.invoice')

                            <!-- push for payment -->
                            <div class="modal fade" id="pay-invoice" tabindex="-1" role="dialog" data-backdrop="static"
                                data-keyboard="false" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">
                                                Pay Invoice
                                            </h4>
                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Please choose preffered mode of Payment</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="my-2">STK Push</h5>
                                                    <p>Enter preffered Safaricom mobile phone number below
                                                        and we will
                                                        invoke payment to the provided number</p>
                                                    <div class="input-group mb-3">

                                                        <input type="text" value="{{ $client->phone }}"
                                                            class="form-control" id="phone_number" placeholder="0700000000">
                                                    </div>
                                                    <div class="mb-3">
                                                        <button type="button" id="pay-stk"
                                                            class="btn btn-sm btn-success">

                                                            Pay Ksh {{ round($inv->total) }}
                                                        </button>
                                                    </div>
                                                    <p id="success-stk-push" class="text-success"></p>
                                                    <div class="text-danger errors " id="stk-errors"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5 class="my-2">Pay using Mpesa Paybill</h5>
                                                    <ol class="listy-style">
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
                                                            class="btn btn-info  btn-sm"> Check Payment
                                                        </button>
                                                    </div>
                                                    <div class="text-danger errors " id="complete-error"></div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-warning btn-sm"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- success complete -->
                            <div class="modal fade" tabindex="-1" id="complete-modal" data-bs-backdrop="static"
                                data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered ">
                                    <div class="modal-content p-3">
                                        <div class="mb-3">
                                            <h4 class="text-success" id="success-complete ">The payment was successfully
                                                completed</h4>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @if ($inv)
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
                    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-pulse"></i>')
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
                    $('.errors, #success-stk-push').html('')
                    var btn_text = $(this).text()
                    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-pulse"></i>')
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
                    window.location.href = "/login"
                })
            })
        </script>
    @endif
@endsection
