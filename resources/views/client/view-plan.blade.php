@extends('client')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">{{ $sms_plan->plan_name }}</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">

            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            @if ($sms_plan->id !== $client->plan_id)
                                <button class="change-plan-btn btn btn-complete pull-right">
                                    <i class="fa fa-shopping-cart">Change Plan</i>
                                </button>
                            @else
                                <button class="btn btn-success btn-xs">Your current plan </button>
                            @endif
                            <h3 class="panel-title">{{ $sms_plan->plan_name }}</h3>
                        </div>
                        <div class="panel-body p-none">
                            <table class="table table-ultra-responsive">
                                <thead>
                                    <tr>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sms_plan->planFeatures as $f)
                                        <tr>
                                            <td data-label="feature name">{{ $f->feature }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

            /*For Delete Price Plan*/
            $("body").delegate(".change-plan-btn", "click", function(e) {
                e.preventDefault();
                bootbox.confirm("Are you sure you want to change the plan ? ", function(result) {
                    if (result) {
                        window.location.href = "/user/plan/change/{{ $sms_plan->id }}";
                    }
                });
            });

        });
    </script>
@endsection
