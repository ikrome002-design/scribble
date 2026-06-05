@extends('staff')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add Visitor
            </h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Visitor</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" method="post" action="/visitors" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fist Name</label>
                                            <input class="form-control" name="first_name" value="{{ old('first_name') }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last_name" value="{{ old('last_name') }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input class="form-control" name="phone_number"
                                                value="{{ old('phone_number') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>ID Number</label>
                                            <input class="form-control" name="id_number" value="{{ old('id_number') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Image</label>
                                            <input type="file" class="form-control" name="image">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Check In time</label>
                                            <input type="datetime-local" class="form-control set-datetime-local"
                                                name="check_in_time">
                                        </div>
                                        <div class="form-group">
                                            <label>Main Subscription</label>
                                            <select id="main-subscription" class="selectpicker form-control"
                                                name="main_subscription" data-live-search="true" required>
                                                <option value="">Select Subscription</option>
                                                @foreach ($subs as $s)
                                                    @if (isset($sub->id))
                                                        <option @selected($s->id == $sub->id) value="{{ $s->id }}">
                                                            {{ $s->business_name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $s->id }}">
                                                            {{ $s->business_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Business name/Premises/Office</label>
                                            <small class="text-muted">If no business name/office/premise connected to main
                                                subscription, please contact your business owner to create one </small>
                                            <select class="form-control" id='business_name' name="business_name"></select>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="form-label">Notes</label>
                                            <textarea class="form-control" placeholder="e.g premises visited in the business" name="notes" rows="5"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm btn-xs pull-right"><i
                                                class="fa fa-plus"></i> Add visitor </button>
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
    <script>
        $('#business_name').select2({
            placeholder: 'Select business',
            ajax: {
                url: '/visitor/business/autofill',
                data: function(params) {
                    var query = {
                        search: params.term,
                        sub_id: $('#main-subscription').find(':selected').val()
                    }
                    return query
                },
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: `${item.business_name}`,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>
@endsection
