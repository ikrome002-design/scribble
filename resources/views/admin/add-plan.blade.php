@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Add New Plan</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-10">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add New Plan</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" role="form" method="post" action="{{ url('admin/plan/add') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}


                                <div class="form-group">
                                    <label>Plan Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                </div>

                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control" name="price" value="{{ old('price') }}">
                                </div>
                                <div class="form-group">
                                    <label>Popular</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="popular" id="flexRadioDefault1"
                                            value="No" checked>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            No
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="popular" value="Yes"
                                            id="flexRadioDefault2">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Yes
                                        </label>
                                    </div>
                                </div>
                                <h4>Plan Features</h4>
                                <div id="features" class="mb-3">
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
                                    @if (old('features'))
                                        @foreach (old('features') as $f)
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control d-inline-block"
                                                        name="features[]" value="{{ $f }}">
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button d-inline-block"
                                                        class="btn btn-danger btn-xs remove-feature"
                                                        style="margin-bottom : 6px; margin-top : 6px; margin-left : 6px;"><i
                                                            class="fa fa-times">Remove</i></a>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div style="margin-bottom :3rem">
                                    <button type="button" id="add-feature" class="btn btn-success btn-xs"
                                        style="margin-bottom : 6px; margin-left : 6px;"><i class="fa fa-plus"></i>Add
                                        feature</a>
                                    </button>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>
                                        {{ language_data('Add') }} </button>
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
