@extends('client')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add Team Link Subscription</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Team Subscription</h3>
                        </div>
                        <div class="panel-body">
                            <form action="/team/subscription" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">

                                        <p class="text-primary">The final price will be calculated depending on
                                            <b>{{ $days_remaining }} day(s) </b>
                                            remaining on your main subscription
                                        </p>
                                        <div class="form-group">
                                            <label>Team members and Staff you wish to add</label>(<i>Scribble Pro staff will
                                                be included here</i>)
                                            <input type="number" class="form-control" name="team_members"
                                                value="{{ old('team_memebrs') }}"><br>

                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i>
                                                Subscribe</button>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card text-center"
                                            style="width: 25rem; margin: 0 auto; margin-bottom: 15px; border: 1px solid black; padding: 12px; background: aliceblue;">
                                            <div class="card-body">
                                                <p><i class="fa fa-iinfo"></i> <i class="text-complete">Pricing per team
                                                        member</i></p>
                                                <h3>{{ $teamplan->name }}</h3>
                                                <h5>price</h5>
                                                <p>KES {{ $teamplan->price }}</p>
                                                <h5>Transaction Fee</h5>
                                                <p>{{ $teamplan->transaction_fee }}%</p>
                                                <h5>Discount Type</h5>
                                                @if ($teamplan->discount_type == 2)
                                                    <p>fixed</p>
                                                @elseif($teamplan->discount_type == 3)
                                                    <p>No Discount</p>
                                                @else
                                                    <p>percent</p>
                                                @endif
                                                <h5>Apply Discount</h5>
                                                @if ($teamplan->apply_discount == 1)
                                                    <p>one time</p>
                                                @elseif($teamplan->apply_discount == 2)
                                                    <p>recurring</p>
                                                @else
                                                    <p>first purchase only</p>
                                                @endif
                                                <h5>Discount Amount</h5>
                                                <p>{{ $teamplan->discount_amount }}</p>
                                                <h5>Government Chart Type</h5>
                                                @if ($teamplan->govt_charges_type == 2)
                                                    <p>fixed</p>
                                                @else
                                                    <p>percent</p>
                                                @endif
                                                <h5>Apply Government Charge</h5>
                                                @if ($teamplan->apply_govt_charges == 1)
                                                    <p>Tax</p>
                                                @else
                                                    <p> other charges</p>
                                                @endif

                                                <h5>Government Charge Amount</h5>
                                                <p>{{ $teamplan->govt_charges_amt }}</p>
                                                <h5>Digital Tax(%)</h5>
                                                <p>{{ $teamplan->digital_tax }}</p>
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
