@extends('pro')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Under Construction</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel p-30">
                        <div class="panel-body p-none text-center">
                            <p>
                                Dear <b>{{ auth('client')->user()->fname }}</b>,
                            </p>
                            <p>
                                We are pleased to inform you that your payment gateway is being integrated into
                                Scribble. We expect this integration to be completed within the next few days, and we
                                will notify you via email once it is ready for use.
                            </p>
                            <p>Thank you for your understanding and patience.</p>
                            <p>Sincerely,<br>
                                <b>Scribble Team.</b>
                            </p>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
@endsection
