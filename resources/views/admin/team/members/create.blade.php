@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add Team Member</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Team Member</h3>
                            <h5 class="text-complete"><i class="fa fa-info-circle"></i>All staff added here will be come team
                                members</h5>

                        </div>
                        <div class="panel-body">
                            <form class="" method="post" action="/team/members" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fist Name</label>
                                            <input class="form-control" name="first_name" value="{{ old('first_name') }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input class="form-control" name="middle_name" value="{{ old('middle_name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last_name" value="{{ old('last_name') }}"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" name="email" value="{{ old('email') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input class="form-control" name="phone_number"
                                                value="{{ old('phone_number') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Image</label>
                                            <input accept="image/jpg,image/png,image/image/jpeg,image/gif"
                                                class="form-control" name="image" type="file">
                                        </div>
                                        <div class="form-group">
                                            <label>Team Role</label>
                                            <span class="text-complete"><i class="fa fa-info-circle"></i>
                                                Manager have access like merchant while staff can't Team Link, Sender ID
                                                Management, SMS API, Coverage/Routing, Plans, Subscriptions, Staff and
                                                roles.</span>
                                            <select class="selectpicker form-control" name="team_role"
                                                data-live-search="true" required>
                                                <option value="">Select team role</option>
                                                <option value="Manager">
                                                    Manager
                                                </option>
                                                <option value="Cashier">
                                                    Staff
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Status (<i>Active=Can login, Inactive=Can't login </i>)</label>
                                            <select class="selectpicker form-control" name="status" required>
                                                <option value="Active">Active</option>
                                                <option value="Active">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Gender</label><br>
                                            <input type="radio" name="gender" value="Male"> Male<br>
                                            <input type="radio" name="gender" value="Female"> Female<br>
                                        </div>
                                        <div>
                                            <button type="button" id="send-otp" class="btn btn-xs btn-complete">
                                                Send OTP to email
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <label>OTP</label>
                                            <input type="number" class="form-control" name="otp"
                                                value="{{ old('otp') }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm pull-right"><i
                                                class="fa fa-plus"></i>Add Staff</button>
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
        $("body").delegate("#send-otp", "click", function(e) {
            e.preventDefault();
            $.ajax({
                url: '/user/generate/otp',
                type: 'POST',
                success: function(response) {}
            })
            bootbox.alert('Check Otp Sent to your email')
        });
    </script>
@endsection
