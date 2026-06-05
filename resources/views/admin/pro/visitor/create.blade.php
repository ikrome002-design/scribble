@extends('pro')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add Visitor
                @if (isset($sub->business_name))
                    ({{ $sub->business_name }})
                @endif
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
                                            <label for="">Check in time</label>
                                            <input type="datetime-local" class="form-control set-datetime-local"
                                                name="check_in_time">
                                        </div>
                                        <div class="form-group">
                                            <label>Business Visited</label>
                                            <select class="selectpicker form-control" name="business_name"
                                                data-live-search="true" required>
                                                <option value="">Select Business</option>
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
    {!! Html::script('assets/libs/data-table/datatables.min.js') !!}
    {!! Html::script('assets/js/bootbox.min.js') !!}

    <script>
        $("body").delegate("#send-otp", "click", function(e) {
            e.preventDefault();
            $.ajax({
                url: '/user/generate/otp',
                type: 'POST',
                success: function(response) {}
            })
            bootbox.alert('Check Otp Sent to your email')
        });
    @endsection
