@extends('staff')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit Visitor</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Visitor</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" method="post" action="/visitors/{{ $visitor->id }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fist Name</label>
                                            <input class="form-control" name="first_name" value="{{ $visitor->fname }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last_name" value="{{ $visitor->lname }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input class="form-control" name="phone_number"
                                                value="{{ $visitor->phone_number }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>ID Number</label>
                                            <input class="form-control" name="id_number" value="{{ $visitor->id_number }}">
                                        </div>

                                        <div class="form-group">
                                            @if ($visitor->image)
                                                <img src="/private/visitor/{{ $visitor->image }}"
                                                    style="width:100px;height:auto"><br>
                                            @endif
                                            <label>Image</label>
                                            <input type="file" class="form-control" name="image">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Check In time</label>
                                            <input type="datetime-local" class="form-control set-datetime-local"
                                                value="{{ $visitor->check_in_time }}" name="check_in_time">
                                        </div>
                                        <div class="form-group">
                                            @if ($visitor->check_out_time)
                                                <label for="">Check Out time</label>
                                                <input type="datetime-local" class="form-control set-datetime-local"
                                                    value="{{ $visitor->check_out_time }}" name="check_out_time">
                                            @else
                                                <a href="/visitors/checkout/{{ $visitor->id }}"
                                                    class="checkout-visitor btn btn-success btn-xs">Check out the
                                                    visitor</a>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Main Subscription</label>
                                            <select id="main-subscription" class="selectpicker form-control"
                                                name="main_subscription" data-live-search="true" required>
                                                <option value="">Select Subscription</option>
                                                @foreach ($subs as $s)
                                                    <option @selected($s->id == $visitor->pro_subscription_id) value="{{ $s->id }}">
                                                        {{ $s->business_name }}
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Business name/Premises/Office</label>
                                            <small class="text-muted">If no business name/office/premise connected to main
                                                subscription, please contact your business owner to create one </small>
                                            <select class="form-control" id='business_name' name="business_name">
                                                @foreach ($buses as $s)
                                                    <option @selected($s->id == $visitor->visitor_business_id) value="{{ $s->id }}">
                                                        {{ $s->business_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="form-label">Notes</label>
                                            <textarea class="form-control" placeholder="e.g premises visited in the business" name="notes" rows="5">{{ $visitor->notes }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm btn-xs pull-right">Update
                                            visitor </button>
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
        $('body').delegate(".checkout-visitor", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            Swal.fire({
                text: 'Are you sure, you want to checkout this visitor',
                confirmButtonText: 'Submit',
                showCancelButton: true,
                html: `<form action='${url}' method='post' id="checkout-visitor-form">
                <label>Check out time</label>
                <input type="datetime-local" name="check_out_time" class="swal2-input set-datetime-local" value=${convertToLocal()}><br>                
                </form>
                  `,

            }).then((result) => {
                if (result.isConfirmed) {
                    $('#checkout-visitor-form').submit()
                }
            })

        })
    </script>
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
