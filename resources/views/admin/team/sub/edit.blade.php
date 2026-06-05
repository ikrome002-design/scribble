@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit Team Link Subscription</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Team Link Subscription</h3>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="/team/subscription/{{ $subscription->id }}">
                                @method('patch')
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Client</label>
                                            <input type="text" class="form-control" disabled
                                                value="{{ $subscription->client->fname . ' ' . $subscription->client->lname }} ({{ $subscription->client->email }})">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Team Members</label>
                                            <input type="number" class="form-control" name="team_members"
                                                value="{{ $subscription->team_members }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="selectpicker form-control" name="sub_status">
                                                @php
                                                    $status = ['Active', 'Inactive'];
                                                @endphp
                                                @foreach ($status as $s)
                                                    <option @selected($subscription->sub_status == $s) value="{{ $s }}">
                                                        {{ $s }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Recurring Date</label>
                                            <input type="date" disabled class="form-control"
                                                value="{{ $subscription->team_recurring_date }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Opted Out</label>
                                            <select class="selectpicker form-control" name="opted_out">
                                                @php
                                                    $status = ['No', 'Yes'];
                                                @endphp
                                                @foreach ($status as $s)
                                                    <option @selected($subscription->opted_out == $s) value="{{ $s }}">
                                                        {{ $s }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($subscription->opted_out == 'Yes')
                                            <div class="form-group">
                                                <label for="">Opted Out Date</label>
                                                <input type="date" disabled class="form-control"
                                                    value="{{ $subscription->opted_out_date }}">
                                            </div>
                                        @endif
                                        <button type="submit" class="btn btn-success btn-sm">Update</i>
                                        </button>
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
    {!! Html::script('assets/libs/data-table/datatables.min.js') !!}
    {!! Html::script('assets/js/bootbox.min.js') !!}
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

            $("body").delegate("#send-otp", "click", function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/user/generate/otp',
                    type: 'POST',
                    success: function(response) {}
                })
                bootbox.alert('Check Otp Sent to your email')
            });
        })
    </script>
@endsection
