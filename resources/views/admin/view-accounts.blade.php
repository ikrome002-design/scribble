@extends('admin')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Accounts</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div style="margin-top:3rem; margin-bottom:3rem;">
                    <a class="btn btn-success btn-xs" style="margin-bottom : 6px; margin-left : 6px;"
                        href="/admin/account/add"><i class="fa fa-plus"></i> Add an Account</a>
                </div>
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Accounts</h3>
                        </div>
                        <div class="panel-body p-none">
                            <table class="table data-table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">SL#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $c)
                                        <tr>
                                            <td data-label="SL">{{ $loop->iteration }}</td>
                                            <td data-label="Name">
                                                <p>{{ $c->name }}</p>
                                            </td>
                                            <td data-label="Actions">
                                                <a class="btn btn-complete btn-xs"
                                                    href="{{ url('/admin/account/edit/' . $c->id) }}"><i
                                                        class="fa fa-edit"></i>
                                                    Manage</a>
                                                <a href="#" class="btn  btn-danger btn-xs cdelete"
                                                    id="{{ $c->id }}"><i class="fa fa-trash"></i>
                                                    {{ language_data('Delete') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
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
        $(document).ready(function() {

            $('.data-table').DataTable({
                language: {
                    url: '{!! url('assets/libs/data-table/i18n/' . get_language_code()->language . '.lang') !!}'
                },
                responsive: true
            })

            /*For Delete Plan Feature*/
            $('body').delegate('.cdelete', 'click', function(e) {
                e.preventDefault()
                var id = this.id
                bootbox.confirm("{!! language_data('Are you sure') !!}?", function(result) {
                    if (result) {
                        window.location.href = '/admin/account/delete/' + id
                    }
                })
            })

        })
    </script>
@endsection
