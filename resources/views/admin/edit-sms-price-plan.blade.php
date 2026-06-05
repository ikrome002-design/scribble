@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit SMS Price Plan</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit SMS Price Plan</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post"
                                action="{{ url('sms/update-price-plan/' . $price_plan->id) }}">
                                {{ csrf_field() }}


                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>{{ language_data('Unit From') }}</label>
                                            <input type="text" class="form-control" name="unit_from"
                                                value="{{ $price_plan->unit_from }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Price') }}</label>
                                            <input type="text" class="form-control" name="price"
                                                value="{{ $price_plan->price }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Discount Type') }}</label>
                                            <br />
                                            <input type="radio" name="disc_amount_charge" value="2"
                                                {{ $price_plan->discount_type == 2 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Fixed</label>
                                            &nbsp;
                                            <input type="radio" name="disc_amount_charge" value="1"
                                                {{ $price_plan->discount_type == 1 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Percent</label>

                                            &nbsp;
                                            <input type="radio" name="disc_amount_charge" value="3"
                                                {{ $price_plan->discount_type == 3 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">No Discount</label>


                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Apply Discount') }}</label>

                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="1"
                                                {{ $price_plan->apply_discount == 1 ? 'checked' : '' }}>
                                            <label for="One Time">One Time</label>
                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="2"
                                                {{ $price_plan->apply_discount == 2 ? 'checked' : '' }}>
                                            <label for="Recurring">Recurring</label>
                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="3"
                                                {{ $price_plan->apply_discount == 3 ? 'checked' : '' }}>
                                            <label for="First_Purchase">During First Purchase Only</label>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Discount Amount') }}</label>
                                            <input type="text" class="form-control" name="discount_amount"
                                                value="{{ $price_plan->discount_amount }}" required>
                                        </div>


                                        <div class="form-group">
                                            <label>{{ language_data('Type of Plan') }}</label>
                                            <select name="plan" id="plan" class="form-control" required>

                                                @foreach ($plans as $plan_names)
                                                    <option value="{{ $plan_names->id }}"
                                                        {{ $price_plan->plan_id == $plan_names->id ? 'selected' : '' }}>
                                                        {{ $plan_names->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>




                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>{{ language_data('Unit To') }}</label>
                                            <input type="text" class="form-control" name="unit_to"
                                                value="{{ $price_plan->unit_to }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('TRANSACTION FEE') }} (%)</label>
                                            <input type="text" class="form-control" name="transaction_fee"
                                                value="{{ $price_plan->transaction_fee }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Government Charges Type') }}</label>
                                            <br />
                                            <input type="radio" name="Government_Charges_Type" value="2"
                                                {{ $price_plan->govt_charges_type == 2 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Fixed</label>
                                            &nbsp;
                                            <input type="radio" name="Government_Charges_Type" value="1"
                                                {{ $price_plan->govt_charges_type == 1 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Percent</label>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Apply Government Charges') }}</label>
                                            <br />
                                            <input type="radio" id="apply_charges" name="apply_charges" value="1"
                                                {{ $price_plan->apply_govt_charges == 1 ? 'checked' : '' }}>
                                            <label for="tax">Tax %</label>
                                            <br />
                                            <input type="radio" id="apply_charges" name="apply_charges" value="2"
                                                {{ $price_plan->apply_govt_charges == 2 ? 'checked' : '' }}>
                                            <label for="Recurring">Other Charges %</label>

                                        </div>

                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>{{ language_data('Goverment Charges Amount') }} </label>
                                            <input type="text" class="form-control" name="govt_charges_amount"
                                                value="{{ $price_plan->govt_charges_amt }}" required>
                                        </div>

                                        <div class="form-group" style="margin-top: 40px;">
                                            <label class=mt-5>{{ language_data('Client Group') }}</label>

                                            <select name="client_group" id="client_group" class="form-control">
                                                <option value="0" selected>All Client Groups</option>

                                                @foreach ($client_groups as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ $price_plan->client_group_id == $c->id ? 'selected' : '' }}>
                                                        {{ $c->group_name }}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>{{ language_data('Status') }}</label>
                                            <select class="selectpicker form-control" name="status" required>
                                                <option value="">Select Status</option>
                                                <option value="Active"
                                                    {{ $price_plan->status == 'Active' ? 'selected' : '' }}>
                                                    {{ language_data('Active') }}</option>
                                                <option value="Inactive"
                                                    {{ $price_plan->status == 'Inactive' ? 'selected' : '' }}>
                                                    {{ language_data('Inactive') }}</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-sm pull-right"><i
                                        class="fa fa-plus"></i> {{ language_data('Update') }}</button>
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
@endsection
