@extends('client')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit Staff member</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Staff member</h3>
                        </div>

                        <div class="panel-body">
                            <form class="" method="post" action="/team/members/{{ $member->id }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('Patch')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unique Id</label>
                                            <input class="form-control" disabled value="{{ $member->unique_id }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Fist Name</label>
                                            <input class="form-control" name="first_name" value="{{ $member->fname }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input class="form-control" name="middle_name" value="{{ $member->mname }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last_name" value="{{ $member->lname }}"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" name="email" value="{{ $member->email }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input class="form-control" name="phone_number"
                                                value="{{ $member->phone_number }}" required>
                                        </div>
                                        <div class="form-group">
                                            @if ($member->image)
                                                <img src="/private/staff/{{ $member->image }}"
                                                    style="width:100px;height:auto">
                                            @endif
                                            <br>
                                            <label>Change Image</label>
                                            <input accept="image/jpg,image/png,image/image/jpeg,image/gif"
                                                class="form-control" name="image" type="file">
                                        </div>

                                        <div class="form-group">
                                            <label>Is Team Member</label>
                                            <select class="selectpicker form-control" name="is_team" required>
                                                <option @selected($member->is_team == 'No') value="No">No</option>
                                                <option @selected($member->is_team == 'Yes') value="Yes">Yes</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Team Role</label>
                                            <span class="text-complete"><i class="fa fa-info-circle"></i>
                                                Must be team member for this role to be effective. Manager have access like
                                                merchant while staff can't Team Link, Sender ID
                                                Management, SMS API, Coverage/Routing, Plans, Subscriptions, Staff and
                                                roles.</span>
                                            <select class="selectpicker form-control" name="team_role"
                                                data-live-search="true">
                                                <option value="">None</option>
                                                @foreach ($team_roles as $r)
                                                    <option @selected($r == $member->team_role) value="{{ $r }}">
                                                        {{ $r }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Status (Active=Can login, Inactive=Can't login)</label>
                                            <select class="selectpicker form-control" name="status" required>
                                                <option @selected($member->status == 'Active') value="Active">Active</option>
                                                <option @selected($member->status == 'Inactive') value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Opted out team</label>
                                            <span class="text-complete"><i class="fa fa-info-circle"></i> Opted out staff
                                                can still login until billing period ends. Opted out and Inactive staff are
                                                ones deemed opted out fully</span>
                                            <select class="selectpicker form-control" name="team_opted_out" required>
                                                <option @selected($member->team_opted_out == 'No') value="No">No</option>
                                                <option @selected($member->team_opted_out == 'Yes') value="Yes">Yes</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Gender</label><br>
                                            <input type="radio" @checked($member->gender == 'Male') name="gender"
                                                value="Male"> Male<br>
                                            <input type="radio" @checked($member->gender == 'Female') name="gender"
                                                value="Female"> Female<br>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm pull-right"><i
                                                class="fa fa-plus"></i>Update Staff</button>
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
@endsection
