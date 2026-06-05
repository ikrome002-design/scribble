@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">View Invoice</h2>
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

                                    <a href="{{ url('/receipts/download-pdf/' . $receipt->id) }}"
                                        class="btn btn-pdf  btn-sm download-pdf"><i class="fa fa-file-pdf-o"></i>
                                        PDF</a>

                                    <br>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">
                            @include('payments.receipt')
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
@endsection
