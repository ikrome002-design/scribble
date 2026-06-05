@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">{{ language_data('View Invoice') }}</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')

            <div class="panel">
                <div class="panel-body p-20" style="background-color: #eff9fe !important;">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="btn-group pull-right" aria-label="...">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn  btn-success btn-sm dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false">{{ language_data('Mark As') }} <span
                                            class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        @if ($inv->status != 'Paid')
                                            <li><a href="#" id="mark_paid"
                                                    data-value="{{ $inv->id }}">{{ language_data('Paid') }}</a></li>
                                        @endif
                                        @if ($inv->status != 'Unpaid')
                                            <li><a href="#" id="mark_unpaid"
                                                    data-value="{{ $inv->id }}">{{ language_data('Unpaid') }}</a>
                                            </li>
                                        @endif
                                        @if ($inv->status != 'Partially Paid')
                                            <li><a href="#" id="mark_partially_paid"
                                                    data-value="{{ $inv->id }}">{{ language_data('Partially Paid') }}</a>
                                            </li>
                                        @endif
                                        @if ($inv->status != 'Cancelled')
                                            <li><a href="#" id="mark_cancelled"
                                                    data-value="{{ $inv->id }}">{{ language_data('Cancelled') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <a href="{{ url('invoices/edit/' . $inv->id) }}" class="btn btn-warning  btn-sm"><i
                                        class="fa fa-pencil"></i> {{ language_data('Edit') }}</a>

                                <a href="{{ url('invoices/download-pdf/' . $inv->invoice_no) }}"
                                    class="btn btn-pdf  btn-sm download-pdf"><i class="fa fa-file-pdf-o"></i>
                                    {{ language_data('PDF') }}</a>
                                <br>
                                <br>

                                <div class="modal fade" id="send-email-invoice" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">
                                                    {{ language_data('Send Invoice') }}</h4>
                                            </div>
                                            <div class="modal-body">

                                                <form class="form-some-up" role="form"
                                                    action="{{ url('invoices/send-invoice-email') }}" method="post">

                                                    <div class="form-group">
                                                        <label>{{ language_data('Subject') }}</label>
                                                        <input type="text" class="form-control" name="subject"
                                                            required="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>{{ language_data('Message') }}</label>
                                                        <textarea class="form-control" rows="5" name="message"></textarea>
                                                    </div>

                                                    <div class="text-right">
                                                        <input type="hidden" value="{{ $inv->id }}" name="cmd">
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-dismiss="modal">{{ language_data('Close') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm">{{ language_data('Send') }}</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-3 col-sm-3 col-xs-12">
                            @include('payments.invoice')
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
    {!! Html::script('assets/js/bootbox.min.js') !!}

    <script>
        $(document).ready(function() {
            /*For Invoice mark paid*/
            $('#mark_paid').click(function(e) {
                e.preventDefault()
                var id = $(this).data('value')

                bootbox.confirm("{!! language_data('Are you sure') !!}?", function(result) {
                    if (result) {
                        var _url = $('#_url').val()
                        window.location.href = _url + '/invoices/mark-paid/' + id
                    }
                })
            })

            /*For Invoice mark as unpaid*/
            $('#mark_unpaid').click(function(e) {
                e.preventDefault()
                var id = $(this).data('value')

                bootbox.confirm("{!! language_data('Are you sure') !!}?", function(result) {
                    if (result) {
                        var _url = $('#_url').val()
                        window.location.href = _url + '/invoices/mark-unpaid/' + id
                    }
                })
            })

            /*For Invoice mark as partially paid*/
            $('#mark_partially_paid').click(function(e) {
                e.preventDefault()
                var id = $(this).data('value')

                bootbox.confirm("{!! language_data('Are you sure') !!}?", function(result) {
                    if (result) {
                        var _url = $('#_url').val()
                        window.location.href = _url + '/invoices/mark-partially-paid/' + id
                    }
                })
            })

            /*For Invoice mark as cancelled*/
            $('#mark_cancelled').click(function(e) {
                e.preventDefault()
                var id = $(this).data('value')

                bootbox.confirm("{!! language_data('Are you sure') !!}?", function(result) {
                    if (result) {
                        var _url = $('#_url').val()
                        window.location.href = _url + '/invoices/mark-cancelled/' + id
                    }
                })
            })

        })
    </script>
@endsection
