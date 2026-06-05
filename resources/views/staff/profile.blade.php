@extends('staff')

@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Profile</h2>
        </div>
        @include('notification.notify')
        <div class="card text-center"
            style="width: 25rem; margin: 0 auto; margin-bottom: 15px; border: 1px solid black; padding: 12px; background: aliceblue;">
            <h3 class="card-title">Your Profile</h3>
            <div class="card-body">
                <h4>Name</h4>
                <h5>{{ $staff->fname }} {{ $staff->mname }} {{ $staff->lname }} </h5>
                <h4>Unique Id</h4>
                <h5>{{ $staff->unique_id }}</h5>
                <h4>Email</h4>
                <h5>{{ $staff->email }}</h5>
                <h4>ID</h4>
                <h5>{{ $staff->id_number ?? 'N/A' }}</h5>
                <h4>Main Working Station</h4>
                <h5>{{ $staff->proSubscription->business_name }}</h5>
                <h4>Phone Number</h4>
                <h5>{{ $staff->phone_number }}</h5>

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
