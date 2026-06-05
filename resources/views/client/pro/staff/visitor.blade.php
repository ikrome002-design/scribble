@extends('pro')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
    @include('partials.styles')
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">{{ $staff->fname }} {{ $staff->lname }} ({{ $staff->email }}) </h2>

        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Staff Role In Visitors</h3>
                        </div>

                        <div class="panel-body p-none">

                            <form action="" method="post">
                                <p class="text-complete  fs-5">
                                    *When add, edit,delete or re assign is assigned, view
                                    will be automatically assigned<br>
                                    *Those given role to edit or delete , will automatically have check out role<br>
                                    *Please if you check re assign role , ensure that the staff has a role to view staff in
                                    that business <a href="/staff/staff/roles/{{ $staff->id }}"> Here</a> .
                                </p>
                                <div class="table-responsive">
                                    {{ $dataTable->table() }}
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success btn-sm ">
                                        Update Roles</button>
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
    @include('partials.scripts')
    {{ $dataTable->scripts() }}
    <script>
        $('.view-check-state').on('change', function() {
            var sub_id = $(this).val();
            var checked = $(`.check${sub_id}:checkbox:checked`).length
            if (checked > 0) {
                $(`.check-view${sub_id}`).prop('checked', true).attr('readonly', true)
            } else {
                $(`.check-view${sub_id}`).attr('readonly', false)
            }

        });
    </script>
@endsection
