@extends('pro')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add Transaction
            </h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Transaction</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" method="post" action="/transactions" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" name="first_name" value="{{ old('first_name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last_name" value="{{ old('last_name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input class="form-control" name="phone_number"
                                                value="{{ old('phone_number') }}">
                                            <small class="text-muted">The customer will receive thank you message through
                                                this phone </small>
                                        </div>
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="text" class="form-control" name="amount"
                                                value="{{ old('amount') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Transaction Id</label>
                                            <input type="text" class="form-control" name="transaction_id"
                                                value="{{ old('transaction_id') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Transaction Date</label>
                                            <input type="datetime-local" class="form-control set-datetime-local"
                                                name="transaction_date" value="{{ old('transaction_date') }}">
                                        </div>


                                        <div class="form-group">
                                            <label>Payment Method</label>
                                            <select class="selectpicker form-control" name="payment_method"
                                                data-live-search="true" required>
                                                @php($options = ['Cash', 'Cheque', 'Debit Card', 'Credit Card', 'Mobile Payment', 'Electronic Bank Transfer', 'Other'])
                                                @foreach ($options as $s)
                                                    <option value="{{ $s }}">
                                                        {{ $s }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Payment details(e.g airtel, bank name, visa etc)</label>
                                            <input type="text" class="form-control" name="payment_details"
                                                value="{{ old('payment_details') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Pro Subscription</label>
                                            <select class="selectpicker form-control" name="subscription"
                                                data-live-search="true" required>
                                                <option value="">Select Subscription</option>
                                                @php($options = ['Mpesa', 'Cash', 'Cheque', 'Debit Card', 'Credit Card', 'Mobile Payment', 'Electronic Bank Transfer', 'Other'])
                                                @foreach ($subs as $s)
                                                    <option value="{{ $s->id }}">
                                                        {{ $s->business_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm btn-xs pull-right"><i
                                                class="fa fa-plus"></i> Add Transaction</button>
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
