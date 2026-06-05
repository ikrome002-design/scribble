@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit team link Plan</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit team link Plan</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" action="/team/plan/{{ $teamplan->id }}">
                                @method('patch')
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>Team link plan Name</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $teamplan->name }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="text" class="form-control" name="price"
                                                value="{{ $teamplan->price }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Discount Type') }}</label>
                                            <br />
                                            <input type="radio" name="disc_amount_charge" value="2"
                                                {{ $teamplan->discount_type == 2 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Fixed</label>
                                            &nbsp;
                                            <input type="radio" name="disc_amount_charge" value="1"
                                                {{ $teamplan->discount_type == 1 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Percent</label>

                                            &nbsp;
                                            <input type="radio" name="disc_amount_charge" value="3"
                                                {{ $teamplan->discount_type == 3 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">No Discount</label>


                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Apply Discount') }}</label>

                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="1"
                                                {{ $teamplan->apply_discount == 1 ? 'checked' : '' }}>
                                            <label for="One Time">One Time</label>
                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="2"
                                                {{ $teamplan->apply_discount == 2 ? 'checked' : '' }}>
                                            <label for="Recurring">Recurring</label>
                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="3"
                                                {{ $teamplan->apply_discount == 3 ? 'checked' : '' }}>
                                            <label for="First_Purchase">During First Purchase Only</label>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Discount Amount') }}</label>
                                            <input type="text" class="form-control" name="discount_amount"
                                                value="{{ $teamplan->discount_amount }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Type of Plan') }}</label>
                                            <select name="plan" id="plan" class="form-control" required>
                                                <option value="0">Select Type of plan</option>
                                                @foreach ($plans as $p)
                                                    <option @selected($p->id == $teamplan->plan_id) value="{{ $p->id }}">
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-md-6">


                                        <div class="form-group">
                                            <label>{{ language_data('TRANSACTION FEE') }} (%)</label>
                                            <input type="text" class="form-control" name="transaction_fee"
                                                value="{{ $teamplan->transaction_fee }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Government Charges Type') }}</label>
                                            <br />
                                            <input type="radio" name="government_charges_type" value="2"
                                                {{ $teamplan->govt_charges_type == 2 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Fixed</label>
                                            &nbsp;
                                            <input type="radio" name="government_charges_type" value="1"
                                                {{ $teamplan->govt_charges_type == 1 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Percent</label>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Apply Government Charges') }}</label>
                                            <br />
                                            <input type="radio" name="apply_govt_charges" value="1"
                                                {{ $teamplan->apply_govt_charges == 1 ? 'checked' : '' }}>
                                            <label for="tax">Tax %</label>
                                            <br />
                                            <input type="radio" name="apply_govt_charges" value="2"
                                                {{ $teamplan->apply_govt_charges == 2 ? 'checked' : '' }}>
                                            <label for="Recurring">Other Charges %</label>

                                        </div>

                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>{{ language_data('Goverment Charges Amount') }} </label>
                                            <input type="text" class="form-control" name="govt_charges_amount"
                                                value="{{ $teamplan->govt_charges_amt }}" required>
                                        </div>
                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>Digital Tax(%) </label>
                                            <input type="text" class="form-control" name="digital_tax"
                                                value="{{ $teamplan->digital_tax }}" required>
                                        </div>
                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>{{ language_data('Status') }}</label>
                                            <select class="selectpicker form-control" name="status" required>
                                                <option value="">Select Status</option>
                                                <option value="Active"
                                                    {{ $teamplan->status == 'Active' ? 'selected' : '' }}>
                                                    {{ language_data('Active') }}</option>
                                                <option value="Inactive"
                                                    {{ $teamplan->status == 'Inactive' ? 'selected' : '' }}>
                                                    {{ language_data('Inactive') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>Notes</label>
                                            <textarea name="notes" class="form-control" rows="10">{!! str_replace('<br />', '', $teamplan->notes) !!}</textarea>
                                        </div>

                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-sm pull-right"><i
                                        class="fa fa-plus"></i>
                                    {{ language_data('Update') }}</button>
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
