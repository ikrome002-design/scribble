@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit Account</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-10">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Account</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" role="form" method="post" action="" enctype="multipart/form-data">
                                {{ csrf_field() }}


                                <div class="form-group">
                                    <label>Account Name</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name') ?? $account->name }}">
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>
                                        {{ language_data('Update') }} </button>
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

    <script>
        $(document).ready(function() {
            $('#add-feature').click(function() {
                $('#features').append(`
                <div class="form-group row">
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control " name="features[]">
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="button" class="btn btn-danger btn-xs  remove-feature"><i
                                                    class="fa fa-times">Remove</i></a>
                                            </button>
                                        </div>
                                    </div>
                `)
            })

            $(document).on('click', '.remove-feature', function() {
                $(this).closest('.form-group').remove()
            })

        })
    </script>
@endsection
