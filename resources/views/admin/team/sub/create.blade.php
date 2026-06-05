@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add Team Link Subscription</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add Team Subscription</h3>
                        </div>
                        <div class="panel-body">
                            <form action="/team/subscription" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>Select Client</label>
                                            <select class="form-control" id='client-search' name="client"></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Team members and Staff you wish to add</label>(<i>Scribble Pro staff will
                                                be included here</i>)
                                            <input type="number" class="form-control" name="team_members"
                                                value="{{ old('team_memebrs') }}"><br>

                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" class="form-select" name="generate_invoice"
                                                value="Yes"> Generate Invoice

                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i>
                                                Add</button>

                                        </div>
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


@section('script')
    {!! Html::script('assets/libs/handlebars/handlebars.runtime.min.js') !!}
    {!! Html::script('assets/js/form-elements-page.js') !!}
    <script>
        $('#client-search').select2({
            placeholder: 'Search Client Client',
            ajax: {
                url: '/admin/client/search',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: `${item.email}, ${item.fname} ${item.lname} `,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>
@endsection
