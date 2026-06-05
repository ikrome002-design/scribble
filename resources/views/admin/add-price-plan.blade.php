@extends('admin')

@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">{{ language_data('Add SMS Price Plan') }}</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ language_data('Add SMS Price Plan') }}</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" role="form" method="post"
                                action="{{ url('sms/post-new-price-plan') }}">
                                {{ csrf_field() }}


                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>{{ language_data('Unit From') }}</label>
                                            <input type="text" class="form-control" name="unit_from" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Unit Price</label>
                                            <input type="text" class="form-control" name="price" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Discount Type') }}</label>
                                            <br />
                                            <input type="radio" name="disc_amount_charge" id="disc_amount_charge"
                                                value="2" checked>
                                            <label for="disc_amt_charge">Fixed</label>
                                            &nbsp;
                                            <input type="radio" name="disc_amount_charge" id="disc_amount_charge"
                                                value="1">
                                            <label for="disc_amt_charge">Percent</label>

                                            &nbsp;
                                            <input type="radio" name="disc_amount_charge" id="disc_amount_charge"
                                                value="3">
                                            <label for="disc_amt_charge">No Discount</label>


                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Apply Discount') }}</label>

                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="1" checked>
                                            <label for="One Time">One Time</label>
                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="2">
                                            <label for="Recurring">Recurring</label>
                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="3">
                                            <label for="First_Purchase">During First Purchase Only</label>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Discount Amount') }}</label>
                                            <input type="text" class="form-control" name="discount_amount"
                                                id="discount_amount" value="0" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Type of Plan') }}</label>
                                            <select name="plan" id="plan" class="form-control" required>
                                                <option value="0">Select Type of plan</option>
                                                @foreach ($plans as $p)
                                                    <option value="{{ $p->id }}">{{ $p->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>{{ language_data('Unit To') }}</label>
                                            <input type="text" class="form-control" name="unit_to" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('TRANSACTION FEE') }} (%)</label>
                                            <input type="text" value="0" class="form-control" name="transaction_fee"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Government Charges Type') }}</label>
                                            <br />
                                            <input type="radio" name="government_charges_type" value="2">
                                            <label for="disc_amt_charge">Fixed</label>
                                            &nbsp;
                                            <input checked type="radio" name="government_charges_type" value="1">
                                            <label for="disc_amt_charge">Percent</label>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Apply Government Charges') }}</label>
                                            <br />
                                            <input type="radio" id="apply_govt_charges" name="apply_govt_charges"
                                                value="1" checked>
                                            <label for="tax">Tax %</label>
                                            <br />
                                            <input type="radio" id="apply_charges" name="apply_govt_charges"
                                                value="2">
                                            <label for="Recurring">Other Charges %</label>

                                        </div>

                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>{{ language_data('Goverment Charges Amount') }} </label>
                                            <input type="text" class="form-control" name="govt_charges_amount"
                                                required>
                                        </div>

                                        <div class="form-group" style="margin-top: 40px;">
                                            <label class=mt-5>{{ language_data('Client Group') }}</label>

                                            <select name="client_group" id="client_group" class="form-control">
                                                <option value=""> All Client Groups</option>
                                                @if (isset($client_group) && count($client_group) > 0)
                                                    @foreach ($client_group as $client_groups)
                                                        <option value="{{ $client_groups->id }}">
                                                            {{ $client_groups->group_name }}</option>
                                                    @endforeach
                                                @else
                                                @endif
                                            </select>

                                        </div>
                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>{{ language_data('Status') }}</label>
                                            <select class="selectpicker form-control" name="status" required>
                                                <option value="">Select Status</option>
                                                <option value="Active">{{ language_data('Active') }}</option>
                                                <option value="Inactive">{{ language_data('Inactive') }}</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-sm pull-right"><i
                                        class="fa fa-plus"></i> {{ language_data('Add Plan') }}</button>
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
        $('input[name=disc_amount_charge]').change(function() {
            var value = $('input[name=disc_amount_charge]:checked').val();

            if (value == 3) {
                $('#discount_amount').val(0);
            } else {
                $('#discount_amount').val("");
            }

        });
    </script>
@endsection
