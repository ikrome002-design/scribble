@extends('staff')

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
                            <h3 class="panel-title">Staff Role In Transactions</h3>
                        </div>

                        <div class="panel-body p-none">
                            <p class="text-primary fs-5">
                                *Lower roles will be automatically assigned if its super role
                                is checked except assign roles. You must assign other role(s) for re assign role to be
                                effective.<br>
                                *Please if you give re assign a role , ensure that the staff has a role to view staff in
                                that
                                business <a href="/staff/staff/roles/{{ $staff->id }}"> Here</a>
                            </p>
                            <form action="" method="post">
                                <div class="table-responsive">
                                    {{ $dataTable->table() }}
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success btn-sm">
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
        $('.checkbox-trans').on('change', function() {
            checked = $(this).is(':checked');
            var id = $(this).attr('id');
            var sub_id = $(this).val();
            var hours = `last_24_hours${sub_id}`;
            var month = `last_one_month${sub_id}`;
            var all = `all${sub_id}`;
            var daily = `daily_summary${sub_id}`;
            var monthly = `monthly_summary${sub_id}`;
            var all_summary = `all_summary${sub_id}`;
            var roles = `assign_roles${sub_id}`;


            if (id === month) {
                if (checked) {
                    $(`#${hours}`).prop('checked', checked).attr('readonly', true)
                } else {
                    $(`#${hours}`).attr('readonly', false)
                }
            }

            if (id === all) {
                if (checked) {
                    $(`#${month}, #${hours}`).prop('checked', checked).attr('readonly', true)
                } else {
                    $(`#${month}, #${hours}`).attr('readonly', false)
                }
            }

            if (id === monthly) {
                if (checked) {
                    $(`#${daily}`).prop('checked', checked).attr('readonly', true)
                } else {
                    $(`#${daily}`).attr('readonly', false)
                }
            }

            if (id === all_summary) {
                if (checked) {
                    $(`#${monthly}, #${daily}`).prop('checked', checked).attr('readonly', true)
                } else {
                    $(`#${monthly}, #${daily}`).attr('readonly', false)
                }
            }

            if ($(`.roles-check${sub_id}:checkbox:checked`).length == 0) {
                $(`#${roles}`).prop('checked', false).attr('readonly', true)
            } else {
                $(`#${roles}`).attr('readonly', false)
            }

        });
    </script>
@endsection
