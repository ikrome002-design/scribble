@extends('admin')
@section('style')
    {{-- External Style Section --}}
    {!! Html::style('assets/libs/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">{{ language_data('Edit Invoice') }}</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ language_data('Edit Invoice') }}</h3>
                        </div>
                        <div class="panel-body">

                            <form method="post" action="{{ url('invoices/post-edit-invoice') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ language_data('Client') }}</label>
                                            <select class="selectpicker form-control" disabled>
                                                <option value="{{ $client->id }}"
                                                    @if ($client->id == $inv->cl_id) selected @endif>{{ $client->fname }}
                                                    {{ $client->lname }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Invoice No.</label>
                                            <input disabled type="text" class="form-control "
                                                value="{{ $inv->invoice_no }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" class="form-control " name="description"
                                                value="{{ $inv->description }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="selectpicker form-control" name="status">
                                                <option value="Paid" @if ($inv->status == 'Paid') selected @endif>
                                                    Paid
                                                </option>
                                                <option value="Unpaid" @if ($inv->status == 'Unpaid') selected @endif>
                                                    UnPaid
                                                </option>
                                                <option value="Unpaid" @if ($inv->status == 'Partially Paid') selected @endif>
                                                    Partially Paid
                                                </option>
                                                <option value="Unpaid" @if ($inv->status == 'Cancelled') selected @endif>
                                                    Cancelled
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>SMS Limit</label>
                                            <input type="text" class="form-control " name="sms_limit"
                                                value="{{ $inv->sms_limit }}">
                                        </div>

                                        <div class="form-group">
                                            <label>{{ language_data('Invoice Date') }}</label>
                                            <input type="text" class="form-control datePicker" name="created"
                                                value="{{ $inv->created }}">
                                        </div>


                                        <div class="show-one-time">
                                            <div class="form-group">
                                                <label>{{ language_data('Due Date') }}</label>
                                                <input type="text" class="form-control datePicker" name="due_date"
                                                    value="{{ $inv->duedate }}">
                                            </div>


                                            <div class="form-group">
                                                <label>{{ language_data('Paid Date') }}</label>
                                                <input type="text" class="form-control datePicker" name="paid_date"
                                                    value="{{ $inv->datepaid }}">
                                            </div>
                                        </div>





                                        <div class="form-group">
                                            <label>Subtotal</label>
                                            <input type="text" class="form-control " name="subtotal"
                                                value="{{ $inv->subtotal }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Tax</label>
                                            <input type="text" class="form-control " name="tax"
                                                value="{{ $inv->tax }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Discount</label>
                                            <input type="text" class="form-control " name="discount"
                                                value="{{ $inv->discount }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Transcation Fee</label>
                                            <input type="text" class="form-control " name="trans_amount"
                                                value="{{ $inv->trans_amount }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Total(Amount to paid by user)</label>
                                            <input type="text" class="form-control " name="total"
                                                value="{{ $inv->total }}">
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <h3>Items</h3>
                                        <div class="table-responsive">
                                            <table class="table table-hover " id="invoice_items">
                                                <thead>
                                                    <tr>
                                                        <th hidden>id</th>
                                                        <th>Description</th>
                                                        <th>Price</th>
                                                        <th>QTY</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $it)
                                                        <td hidden><input type="number" name="item_id[]"
                                                                value={{ $it->id }}></td>
                                                        <tr class="info">

                                                            <td data-label="description"><input type="text"
                                                                    class="form-control" name="description[]"
                                                                    value="{{ $it->description }}"></td>
                                                            <td data-label="price"><input type="text"
                                                                    class="form-control" name="price[]"
                                                                    value="{{ $it->price }}"></td>
                                                            <td data-label="quantity"><input type="text"
                                                                    class="form-control" value="{{ $it->quantity }}"
                                                                    name="quantity[]"></td>
                                                            <td data-label="amount"><input type="text"
                                                                    class="form-control" name="amount[]"
                                                                    value="{{ $it->amount }}"> </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <div class="text-center">
                                    <input type="hidden" value="{{ $inv->id }}" name="cmd">
                                    <input type="hidden" value="{{ $inv->cl_id }}" name="client_id">
                                    <button class="btn btn-success" type="submit"><i class="fa fa-save"></i>
                                        update Invoice</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
@endsection

{{-- External Script Section --}}
@section('script')
    {!! Html::script('assets/libs/handlebars/handlebars.runtime.min.js') !!}
    {!! Html::script('assets/libs/moment/moment.min.js') !!}
    {!! Html::script('assets/libs/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
    {!! Html::script('assets/js/form-elements-page.js') !!}
    {!! Html::script('assets/js/invoice-edit.js') !!}
@endsection
