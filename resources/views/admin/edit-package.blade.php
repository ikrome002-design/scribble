@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit Package</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Package</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" action="">

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>Package Name</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $package->name }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Price') }} excluding (tax,discount,transcation
                                                fee)</label>
                                            <input type="text" class="form-control" name="amount"
                                                value="{{ $package->price }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Discount Type') }}</label>
                                            <br />
                                            <input type="radio" name="disc_amount_charge" value="2"
                                                {{ $package->discount_type == 2 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Fixed</label>
                                            &nbsp;
                                            <input type="radio" name="disc_amount_charge" value="1"
                                                {{ $package->discount_type == 1 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Percent</label>

                                            &nbsp;
                                            <input type="radio" name="disc_amount_charge" value="3"
                                                {{ $package->discount_type == 3 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">No Discount</label>


                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Apply Discount') }}</label>

                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="1"
                                                {{ $package->apply_discount == 1 ? 'checked' : '' }}>
                                            <label for="One Time">One Time</label>
                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="2"
                                                {{ $package->apply_discount == 2 ? 'checked' : '' }}>
                                            <label for="Recurring">Recurring</label>
                                            <br />
                                            <input type="radio" id="discount" name="discounts" value="3"
                                                {{ $package->apply_discount == 3 ? 'checked' : '' }}>
                                            <label for="First_Purchase">During First Purchase Only</label>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Discount Amount') }}</label>
                                            <input type="text" class="form-control" name="discount_amount"
                                                value="{{ $package->discount_amount }}" required>
                                        </div>

                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>{{ language_data('Mark Popular') }}</label>
                                            <select class="selectpicker form-control" name="popular" required>
                                                <option value="Yes" {{ $package->popular == 'Yes' ? 'selected' : '' }}>
                                                    {{ language_data('Yes') }}</option>
                                                <option value="No" {{ $package->popular == 'No' ? 'selected' : '' }}>
                                                    {{ language_data('No') }}</option>
                                            </select>
                                        </div>



                                    </div>

                                    <div class="col-md-6">


                                        <div class="form-group">
                                            <label>{{ language_data('TRANSACTION FEE') }} (%)</label>
                                            <input type="text" class="form-control" name="transaction_fee"
                                                value="{{ $package->transaction_fee }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Government Charges Type') }}</label>
                                            <br />
                                            <input type="radio" name="Government_Charges_Type" value="2"
                                                {{ $package->govt_charges_type == 2 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Fixed</label>
                                            &nbsp;
                                            <input type="radio" name="Government_Charges_Type" value="1"
                                                {{ $package->govt_charges_type == 1 ? 'checked' : '' }}>
                                            <label for="disc_amt_charge">Percent</label>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Apply Government Charges') }}</label>
                                            <br />
                                            <input type="radio" id="apply_charges" name="apply_charges" value="1"
                                                {{ $package->apply_govt_charges == 1 ? 'checked' : '' }}>
                                            <label for="tax">Tax %</label>
                                            <br />
                                            <input type="radio" id="apply_charges" name="apply_charges" value="2"
                                                {{ $package->apply_govt_charges == 2 ? 'checked' : '' }}>
                                            <label for="Recurring">Other Charges %</label>

                                        </div>

                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>{{ language_data('Goverment Charges Amount') }} </label>
                                            <input type="text" class="form-control" name="govt_charges_amount"
                                                value="{{ $package->govt_charges_amt }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Show in Client') }}</label>
                                            <select class="selectpicker form-control" name="show_in_client" required>
                                                <option value="Yes"
                                                    {{ $package->show_client == 'Yes' ? 'selected' : '' }}>
                                                    {{ language_data('Yes') }}</option>
                                                <option value="No"
                                                    {{ $package->show_client == 'No' ? 'selected' : '' }}>
                                                    {{ language_data('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>{{ language_data('Status') }}</label>
                                            <select class="selectpicker form-control" name="status" required>
                                                <option value="">Select Status</option>
                                                <option value="Active"
                                                    {{ $package->status == 'Active' ? 'selected' : '' }}>
                                                    {{ language_data('Active') }}</option>
                                                <option value="Inactive"
                                                    {{ $package->status == 'Inactive' ? 'selected' : '' }}>
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
