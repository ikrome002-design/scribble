@extends('client')

@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
    @include('partials.styles')
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Team Subscription</h2>
        </div>

        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Team Subscription</h3>
                        </div>
                        @if (count($subs) == 0)
                            <a class="btn btn-success btn-xs" style="margin-bottom : 6px; margin-left : 6px;"
                                href="/team/subscription/create"><i class="fa fa-plus"></i>
                                Add Team Link Subscription</a>
                        @endif
                        <div class="panel-body p-none">
                            <div class="mb-5">
                                <table class="table data-table table-hover">
                                    <thead style="white-space: nowrap;">
                                        <tr>
                                            <th>SL#</th>
                                            <th>Status</th>
                                            <th>Team Members</th>
                                            <th>Recurring Date</th>
                                            <th>Opted Out</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="white-space: nowrap;">

                                        @foreach ($subs as $sub)
                                            <tr>
                                                <td data-label="SL">{{ $loop->iteration }}</td>
                                                <td>
                                                    <p>
                                                        {{ $sub->sub_status }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p>{{ $sub->team_members }}
                                                        @if ($sub->sub_status == 'Active')
                                                            <a class="btn btn-success scale-up-down btn-xs"
                                                                href="/team/subscription/memebers/scale/{{ $sub->id }}"></i>
                                                                Scale down/up
                                                            </a>
                                                        @endif

                                                    </p>
                                                </td>
                                                <td>
                                                    <p>{{ $sub->team_recurring_date }}
                                                    </p>
                                                </td>
                                                <td>
                                                    @if ($sub->opted_out == 'Yes')
                                                        <p>{{ $sub->opted_out }} ({{ $sub->opted_out_date }})
                                                            <a class="btn btn-success opt-in btn-xs"
                                                                href="/team/subscription/opt/in/{{ $sub->id }}"></i>
                                                                Opt in
                                                            </a>
                                                        </p>
                                                    @else
                                                        <p>{{ $sub->opted_out }} <a class="btn opt-out btn-success btn-xs"
                                                                href="/team/subscription/opt/out/{{ $sub->id }}"></i>
                                                                Opt out
                                                            </a>
                                                        </p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($sub->sub_status == 'Inactive')
                                                        <p><a class="btn btn-success generate-invoice btn-xs"
                                                                href="/team/subscription/generate/invoice/{{ $sub->id }}"></i>
                                                                Generate Invoice
                                                            </a>
                                                        </p>
                                                        <input type="text" hidden id="team-members"
                                                            value="{{ $sub->team_members }}">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            @if (count($teamActions) > 0)
                                <div>
                                    <h3 class="text-primary mb-5">Staff Members Action which will be effected today or in
                                        future
                                    </h3>
                                    <table class="table data-table table-hover">
                                        <thead style="white-space: nowrap;">
                                            <tr>
                                                <th>SL#</th>
                                                <th>Action</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody style="white-space: nowrap;">

                                            @foreach ($teamActions as $t)
                                                <tr>
                                                    <td data-label="SL">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <p>
                                                            {{ $t->action }} by {{ $t->team_members }} member(s)
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p>
                                                            {{ $t->action_date }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p>
                                                            <a class="btn btn-success stop-action btn-xs"
                                                                href="/team/subscription/stop/action/{{ $t->id }}">
                                                                Stop Action
                                                            </a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            @endif
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
    @include('partials.scripts')
    <script>
        $('.data-table').DataTable({
            language: {
                url: '{!! url('assets/libs/data-table/i18n/English.lang') !!}'
            },
            responsive: true
        });

        /*For invoice*/
        $("body").delegate(".generate-invoice", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            var team_members = $('#team-members').val();
            Swal.fire({
                title: 'Are Sure you want to generate Invoice',
                confirmButtonText: 'Submit',
                showCancelButton: true,
                cancelButtonText: 'No',
                html: `<form action='${url}'  method='post' id="generate-invoice-form">
                       @csrf
                      <div class="mb-3">
                    <label for=""  class="form-label">Team Members</label><br>
                   <input type="number" value="${team_members}" name="team_members" class="swal2-input" >            
                    </div>
                </form>
                  `,

            }).then((result) => {
                if (result.isConfirmed) {
                    $('#generate-invoice-form').submit()
                }
            })
        })

        //opt out
        $("body").delegate(".opt-out", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            Swal.fire({
                icon: 'warning',
                title: 'Are sure you want to opt out',
                confirmButtonText: 'Submit',
                showCancelButton: true,
                cancelButtonText: 'No',
                html: `
                <p>All your staff members will become inactive when the current billing period ends.</p>
                <form action='${url}'  method='post' id="opt-out-form"> 
                     @csrf                   
                </form>
                  `,

            }).then((result) => {
                if (result.isConfirmed) {
                    $('#opt-out-form').submit()
                }
            })
        })


        /*For opt in*/
        $("body").delegate(".opt-in", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            var team_members = $('#team-members').val();
            Swal.fire({
                title: 'Are Sure you want to generate Invoice',
                confirmButtonText: 'Submit',
                showCancelButton: true,
                cancelButtonText: 'No',
                icon: 'warning',
                html: `<form action='${url}'  method='post' id="optin-form">
                     @csrf
                      <div class="mb-3">
                    <label for=""  class="form-label">Team Members</label><br>
                   <input type="number" value="${team_members}" name="team_members" class="swal2-input" >            
                    </div>
                </form>
                  `,

            }).then((result) => {
                if (result.isConfirmed) {
                    $('#optin-form').submit()
                }
            })
        })
        /*For opt in*/
        $("body").delegate(".scale-up-down", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            Swal.fire({
                title: 'Are Sure you want to scale up/down ?',
                confirmButtonText: 'Submit',
                showCancelButton: true,
                cancelButtonText: 'No',
                html: `<form action='${url}'  method='post' id="scale-form">
                     @csrf
                    <p class="alert-warning">
                        If you scale down, ensure you have opted out and deactivated members to reach required numbers. Otherwise system will scale opt out and deactivate the most recent members active members by end of current billing period.<br><br>
                        When you opt in or out of team subcsription all action will be deemed null.<br><br>
                       During renewing or  extending subscription, the scale up/down will be included only in the first invoice. <br><br>
                      Otherwise the action will be effected at day of end of current billing period.<br><br>
                    </p>
                    <div class="mb-3">
                    <label for=""  class="form-label">How do want to scale members ?</label><br>
                        <select name='scale' class="swal2-select">
                        <option value="">Select scale type</option>
                        <option value="up">Scale Up</option>
                        <option value="down">Scale down</option>
                    </select>     
                    </div>
                      <div class="mb-3">
                    <label for=""  class="form-label">How many staff members you want scale up/down</label><br>
                   <input type="number"  name="team_members" class="swal2-input" >            
                    </div>
                     <div class="mb-3">
                    <label for=""  class="form-label">If you want scale up, when ?</label><br>
                    <select name='scale_up_when' class="swal2-select">
                        <option value="">When to scale up</option>
                        <option value="now">Now</option>
                        <option value="end">End of current billing end</option>
                    </select>       
                    </div>
                </form>
                  `,

            }).then((result) => {
                if (result.isConfirmed) {
                    $('#scale-form').submit()
                }
            })
        })
        /*For stop action in*/
        $("body").delegate(".stop-action", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            var team_members = $('#team-members').val();
            Swal.fire({
                icon: 'warning',
                title: 'Are Sure you want to stop this action',
                confirmButtonText: 'Yes',
                showCancelButton: true,
                cancelButtonText: 'No',
                html: `<form action='${url}'  method='post' id="stop-action-form">
                </form>
                  `,

            }).then((result) => {
                if (result.isConfirmed) {
                    $('#stop-action-form').submit()
                }
            })
        })
    </script>
@endsection
