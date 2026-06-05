@extends('pro')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit Scribble Pro Subscription</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Scribble Pro Subscription</h3>
                        </div>
                        <div class="panel-body">
                            <form action="/prosubscriptions/{{ $prosubscription->id }}" method="post"
                                enctype="multipart/form-data">
                                @method('patch')
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Business Name *</label>
                                            <input type="text" class="form-control" required name="business_name"
                                                value="{{ $prosubscription->business_name }}">
                                        </div>
                                        <div class="form-group">
                                            @php
                                                $services = ['staff', 'visitors', 'transactions'];
                                            @endphp
                                            <label>Which services do you want to use</label>
                                            @foreach ($services as $s)
                                                <div class="form-check">
                                                    <input @checked($prosubscription->$s) class="form-check-input"
                                                        name="services[]" type="checkbox" value="{{ $s }}">
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
                                                value="{{ $prosubscription->phone_number }}">
                                        </div>

                                        <div class="form-group">
                                            <label>When do you want daily transaction summary</label>
                                            <input type="time" class="form-control"
                                                value="{{ $prosubscription->summary_time }}" name="summary_time">
                                        </div>

                                        <div class="form-group">
                                            <label>SMS gateways used send Message * </label>
                                            <select class="selectpicker form-control" name="sms_gateway"
                                                data-live-search="true">
                                                @foreach ($sms_gateways as $s)
                                                    <option @selected($prosubscription->sms_gateway == $s->id) value="{{ $s->id }}">
                                                        {{ $s->name }}
                                                    </option>
                                                @endforeach


                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label>Sender Id used to send Message * </label>
                                            <select class="selectpicker form-control" name="sender_id"
                                                data-live-search="true">
                                                @foreach ($sender_ids as $s)
                                                    <option @selected($prosubscription->sender_id == $s->id) value="{{ $s->id }}">
                                                        {{ $s->sender_id }}
                                                    </option>
                                                @endforeach


                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Shortcode (Paybill or Till/Buy Goods Number)</label>
                                            <input type="number" disabled class="form-control" name="shortcode"
                                                value="{{ $prosubscription->shortcode }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Select Type </label>
                                            <select class="selectpicker form-control" name="shortcode_type"
                                                data-live-search="true">
                                                <option value="">Select shortcode type</option>
                                                <option @selected($prosubscription->shortcode_type == 'Paybill') value="Paybill">
                                                    Paybill
                                                </option>
                                                <option @selected($prosubscription->shortcode_type == 'Till') value="Till">
                                                    Till/Buy Goods
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <button type="button" id="send-otp" class="btn btn-xs btn-complete">
                                                Send OTP to email
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <label>OTP</label>
                                            <input type="number" class="form-control" name="otp"
                                                value="{{ old('otp') }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm">Update<i
                                                class="fa fa-plus"></i>
                                        </button>

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
        $(function() {
            $('.integrate').hide()
            $('.integrate-choice').on('change', function() {
                $('.integrate').hide()
                if (this.value == 'Files') {
                    $('#upload-form').show()
                } else if (this.value == 'Calendly') {
                    $('#calendly-form').show()
                }
            });

            $("body").delegate("#send-otp", "click", function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/user/generate/otp',
                    type: 'POST',
                    success: function(response) {}
                })
                bootbox.alert('Check Otp Sent to your email')
            });
        })
    </script>
@endsection
