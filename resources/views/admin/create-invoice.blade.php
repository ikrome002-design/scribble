@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Create Invoice</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Create Invoice</h3>
                        </div>
                        <p class="text-complete p-5">
                            Only SMS will be awarded automatically when client pays for invoice. Other Services,
                            as admin you will award them manually.
                        </p>
                        <div class="panel-body">
                            <form class="" role="form" method="post" action="/admin/create/invoice">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" value="{{ old('description') }}" class="form-control"
                                                name="description" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="number" value="{{ old('price') }}" class="form-control"
                                                min="1" name="price" required>
                                        </div>
                                        <div class="form-group">
                                            <label>SMS</label>
                                            <input value="{{ old('sms') }}" type="number" class="form-control"
                                                value="0" min="0" name="sms">
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
                                            <label>{{ language_data('Discount Amount') }}</label>
                                            <input type="text" class="form-control" name="discount_amount"
                                                id="discount_amount" value="0" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Select Client</label>
                                            <select name="client[]" class="selectpicker form-control"
                                                data-live-search="true" multiple required>
                                                <option value="">select client</option>
                                                <option value="0"> All Clients</option>
                                                @foreach ($clients as $c)
                                                    <option value="{{ $c->id }}">{{ $c->fname }}
                                                        {{ $c->lname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-md-6">

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
                                            <input type="text" value="0" class="form-control"
                                                name="govt_charges_amount">
                                        </div>
                                        <div class="form-group" style="margin-top: 45px;">
                                            <label>Digital Tax(%) </label>
                                            <input type="text" class="form-control" name="digital_tax" value="0.00">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-sm pull-right">
                                    <i class="fa fa-plus"></i>
                                    Create Invoice</button>
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
