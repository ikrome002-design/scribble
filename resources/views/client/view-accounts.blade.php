@extends('client')

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
                                                @if ($c->id == $client->account_type)
                                                    <button class="btn btn-success btn-xs">Your current account</button>
                                                @else
                                                    <a class="btn btn-complete btn-xs change-account"
                                                        href="{{ url('/user/account/change/' . $c->id) }}"><i
                                                            class="fa fa-shopping-cart"></i>
                                                        Change Account</a>
                                                @endif
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
            //change account
            $("body").delegate(".change-account", "click", function(e) {
                e.preventDefault();
                url = this.href
                bootbox.confirm("Are you sure you want to change account?", function(result) {
                    if (result) {

                        window.location.href = url;
                    }
                });
            });

        })
    </script>
@endsection
