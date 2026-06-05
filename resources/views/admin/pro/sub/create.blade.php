@extends('admin-pro')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add Pro Subscription</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Pro Subscription</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" method="post" action="/prosubscriptions">
                                @csrf
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Select Client</label>
                                            <select class="form-control" id='client-search' name="client"></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Business Name</label>
                                            <input type="text" class="form-control" required name="business_name"
                                                value="{{ old('business_name') }}">
                                        </div>
                                        <div class="form-group">
                                            @php
                                                $services = ['staff', 'visitors', 'transactions'];
                                            @endphp
                                            <label>Which services do you want to use</label>
                                            @foreach ($services as $s)
                                                <div class="form-check">
                                                    <input class="form-check-input" name="services[]" type="checkbox"
                                                        value="{{ $s }}">
                                                    <label class="form-check-label" for="">
                                                        {{ $s }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="form-group">
                                            <label>Phone Number for receiving summary mpesa transactions</label>
                                            <small class="form-text text-muted">
                                                It should be the same as other subscriptions if you want to receive as one
                                                sms
                                            </small>
                                            <input type="text" id="phone_number" class="form-control" name="phone_number"
                                                value="{{ old('phone_number') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Daily transaction summary time</label>
                                            <input type="time" class="form-control" value="23:59" name="summary_time">
                                        </div>
                                        <div class="form-group">
                                            <label>Shortcode (Paybill or Till Number)</label>
                                            <input type="number" class="form-control" name="shortcode"
                                                value="{{ old('shortcode') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Select Type </label>
                                            <select class="selectpicker form-control" name="shortcode_type"
                                                data-live-search="true">
                                                <option value="Paybill">
                                                    Paybill
                                                </option>
                                                <option value="Till">
                                                    Till
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Generate Invoice(If user needs to pay) </label>
                                            <select class="selectpicker form-control" name="generate_invoice"
                                                data-live-search="true" required>
                                                <option value="no">
                                                    No (Subscription will be active immediately)
                                                </option>
                                                <option value="yes">
                                                    Yes
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            *Please remember to add sender for this user after adding in edit section. The
                                            sender id will used to
                                            send messages for this business name.
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm pull-right"><i
                                                class="fa fa-plus"></i> Add Pro Subscription</button>
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
        $('#client-search').select2({
            placeholder: 'Search Client Client',
            ajax: {
                url: '/admin/client/search',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: `${item.email}, ${item.fname} ${item.lname} `,
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
