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
                            <form class="" method="post" action="/prosubscriptions/{{ $prosubscription->id }}">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Client</label>
                                            <input class="form-control" disabled
                                                value="{{ $prosubscription->client->fname }} {{ $prosubscription->client->lname }}"></select>
                                        </div>

                                        <div class="form-group">
                                            <label>Business Name</label>
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
                                            <label>Daily transaction summary time</label>
                                            <input type="time" class="form-control"
                                                value="{{ $prosubscription->summary_time }}" name="summary_time">
                                        </div>
                                        <div class="form-group">
                                            <label>Sender Id used to send Message * </label>
                                            <select class="selectpicker form-control" name="sender_id"
                                                data-live-search="true">
                                                <option value="">Select sender id</option>
                                                @foreach ($sender_ids as $s)
                                                    <option @selected($s->id == $prosubscription->sender_id) value="{{ $s->id }}">
                                                        {{ $s->sender_id }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Shortcode (Paybill or Till Number)</label>
                                            <input type="number" class="form-control" name="shortcode"
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
                                                    Till
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Subscription Status </label>
                                            <select class="selectpicker form-control" name="sub_status"
                                                data-live-search="true" required>
                                                <option @selected($prosubscription->sub_status == 'Active') value="Active">
                                                    Active
                                                </option>
                                                <option @selected($prosubscription->sub_status == 'Inactive') value="Inactive">
                                                    Inactive
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Mpesa Integration </label>
                                            <select class="selectpicker form-control" name="shortcode_status"
                                                data-live-search="true">
                                                <option value="">Select mpesa shortcode integration status</option>
                                                <option @selected($prosubscription->shortcode_status == 'Complete') value="Complete">
                                                    Complete
                                                </option>
                                                <option @selected($prosubscription->shortcode_status == 'Incomplete') value="Incomplete">
                                                    Incomplete
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Recurring Date</label>
                                            <input disabled type="date" class="form-control disabled"
                                                name="plan_recurring_date"
                                                value="{{ $prosubscription->plan_recurring_date }}">
                                        </div>


                                        <div class="form-group">
                                            <label>Opted Out </label>
                                            <select class="selectpicker form-control" name="opted_out"
                                                data-live-search="true" required>
                                                <option @selected($prosubscription->opted_out == 'No') value="No">
                                                    No
                                                </option>
                                                <option @selected($prosubscription->opted_out == 'Yes') value="Yes">
                                                    Yes
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <input type="checkbox" value="email_client" name="email_client"><label>Check if
                                                you want to email client if is first time you completed mpesa
                                                integration</label>
                                        </div>



                                        <button type="submit" class="btn btn-success btn-sm pull-right"><i
                                                class="fa fa-plus"></i> Update Pro Subscription</button>
                                    </div>

                                    <div class="col-md-6">
                                        <h4>Files</h4>
                                        @foreach ($prosubscription->proSubscriptionFile as $file)
                                            <a href="/prosubscriptions/download/{{ $file->filename }}">
                                                {{ substr($file->originalname, 0, 30) }} <i class="fa fa-arrow-down"></i>
                                            </a> <br>
                                        @endforeach

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
    {!! Html::script('assets/libs/handlebars/handlebars.runtime.min.js') !!} {!! Html::script('assets/js/form-elements-page.js') !!}
@endsection
