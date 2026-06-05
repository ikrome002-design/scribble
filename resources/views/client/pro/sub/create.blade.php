@extends('pro')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add Scribble Pro Subscription</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Scribble Pro Subscription</h3>
                        </div>
                        <div class="panel-body">
                            <form action="/prosubscriptions" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Pro Plan(Depending on {{ $days_remaining }} day(s) remaining in main
                                                subscription)*</label>
                                            <select class="form-control" disabled name="plan" required>
                                                <option value="{{ $proplan->id }}">
                                                    {{ $proplan->name }} (KES
                                                    {{ ceil($proplan->total * $remDaysFraction) }})
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Business Name *</label>
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
                                                value="{{ old('phone_number') ?? auth('client')->user()->phone }}">
                                        </div>

                                        <div class="form-group">
                                            <label>When do you want daily transaction summary</label>
                                            <input type="time" class="form-control" value="23:59" name="summary_time">
                                        </div>

                                        <div class="form-group">
                                            <label>Sender Id used to send Message * </label>
                                            <select class="selectpicker form-control" name="sender_id"
                                                data-live-search="true">
                                                @foreach ($sender_ids as $s)
                                                    <option value="{{ $s->id }}">
                                                        {{ $s->sender_id }}
                                                    </option>
                                                @endforeach


                                            </select>
                                        </div>



                                        <div class="form-group">
                                            <label>Shortcode (Paybill or Till/Buy Goods Number)</label>
                                            <input type="number" class="form-control" name="shortcode"
                                                value="{{ old('shortcode') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Select Type </label>
                                            <select class="selectpicker form-control" name="shortcode_type"
                                                data-live-search="true">
                                                <option value="">Select shortcode type</option>
                                                <option value="Paybill">
                                                    Paybill
                                                </option>
                                                <option value="Till">
                                                    Till/Buy Goods
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>How do you want integrate with mpesa developer</label>
                                            <p><a target="_blank" href="/mpesa/integration/guide">Learn more about Mpesa
                                                    integration</a></p>
                                            <div class="form-check">
                                                <input class="form-check-input integrate-choice" type="radio"
                                                    name="developer_integrate" id="flexRadioDefault1" value="Calendly">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Book time and day for developer calls
                                                </label>
                                                <div id="calendly-form" class="integrate">
                                                    <!-- Calendly inline widget begin -->
                                                    <div class="calendly-inline-widget"
                                                        data-url="https://calendly.com/scribble-support/30min"
                                                        style="min-width:320px;height:630px;"></div>
                                                    <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
                                                    <!-- Calendly inline widget end -->
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input integrate-choice" type="radio"
                                                    name="developer_integrate" value="Files" id="flexRadioDefault2">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Upload required details for developer access
                                                </label>
                                                <div id="upload-form" class="integrate">
                                                    <p>
                                                        Download the following files, fill in details and upload them
                                                        here<br>
                                                        <a href="/mpesa/M-Pesa Integration Details - Template.pdf"
                                                            download>M-Pesa Integration Details - Template</a><br>
                                                        <a href="/mpesa/Template Letter For Nomination of an M-Pesa Business Administrator.pdf"
                                                            download>Template Letter For Nomination of an M-Pesa Business
                                                            Administrator</a>
                                                    </p>
                                                    <label for="">Upload Files</label>
                                                    <input type="file" multiple class="form-control"
                                                        name="upload_files[]">
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-sm">Subscribe<i
                                                class="fa fa-plus"></i>
                                        </button>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="card text-center"
                                            style="width: 25rem; margin: 0 auto; margin-bottom: 15px; border: 1px solid black; padding: 12px; background: aliceblue;">
                                            <h3 class="card-title">Pro plan</h3>
                                            <div class="card-body">
                                                <h5>Price</h5>
                                                <small>KES {{ $proplan->price }}</small>
                                                <hr>
                                                <h5>Tax</h5>
                                                <small>KES {{ $proplan->tax }}</small>
                                                <hr>
                                                <h5>Discount</h5>
                                                <small>KES {{ $proplan->discount }}</small>
                                                <hr>
                                                <h5>Transaction Fee</h5>
                                                <small>KES {{ $proplan->trans_amount }}</small>
                                                <hr>
                                                <h5>Total Amount</h5>
                                                <small>KES {{ $proplan->total }}</small>
                                            </div>
                                        </div>
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
        })
    </script>
@endsection
