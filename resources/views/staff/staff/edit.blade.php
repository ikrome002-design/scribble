@extends('staff')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit Staff</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Staff</h3>
                        </div>

                        <div class="panel-body">
                            <form class="" method="post" action="/staff/{{ $staff->id }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fist Name</label>
                                            <input class="form-control" name="first_name" value="{{ $staff->fname }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input class="form-control" name="middle_name" value="{{ $staff->mname }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" name="last_name" value="{{ $staff->lname }}"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" name="email" value="{{ $staff->email }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input class="form-control" name="phone_number"
                                                value="{{ $staff->phone_number }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>ID Number</label>
                                            <input class="form-control" name="id_number" value="{{ $staff->id_number }}">
                                        </div>

                                        <div class="form-group">
                                            <img src="/private/staff/{{ $staff->image }}" style="width:100px;height:auto">
                                            <br>
                                            <label>Change Image</label>
                                            <input accept="image/jpg,image/png,image/image/jpeg,image/gif"
                                                class="form-control" name="image" type="file">
                                        </div>

                                        <div class="form-group">
                                            <label>Main Working Station</label>
                                            <select class="selectpicker form-control" name="main_station" required>
                                                @foreach ($subs as $s)
                                                    <option @selected($staff->pro_subscription_id == $s->id) value="{{ $s->id }}">
                                                        {{ $s->business_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select class="selectpicker form-control" name="role" data-live-search="true"
                                                required>
                                                @foreach ($roles as $r)
                                                    <option @selected($r == $staff->role) value="{{ $r }}">
                                                        {{ $r }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Status (Active=Can login, Inactive=Can't login)</label>
                                            <select class="selectpicker form-control" name="status" required>
                                                <option @selected($staff->status == 'Active') value="Active">Active</option>
                                                <option @selected($staff->status == 'Inactive') value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Gender</label><br>
                                            <input type="radio" @checked($staff->gender == 'Male') name="gender"
                                                value="Male"> Male<br>
                                            <input type="radio" @checked($staff->gender == 'Female') name="gender"
                                                value="Female"> Female<br>
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
@endsection
