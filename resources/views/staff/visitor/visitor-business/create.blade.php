@extends('staff')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add business names/premises/offices to be visited
            </h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Business Names/premises/Offices</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" method="post" action="/visitorBusiness" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="selectpicker form-control" name="subscription"
                                                data-live-search="true" required>
                                                <option value="">Select main subscription business</option>
                                                @foreach ($subs as $s)
                                                    <option value="{{ $s->id }}">
                                                        {{ $s->business_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Business
                                                    Name/premise/Office</label>
                                                <input type="text" class="form-control" name="business_name">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm btn-xs pull-right"><i
                                                class="fa fa-plus"></i> Add Business </button>
                                    </div>
                                </div>
                            </form>
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
@endsection
