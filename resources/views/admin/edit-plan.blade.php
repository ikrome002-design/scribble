@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit Plan Features</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Plan Features</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" role="form" method="post" action="" enctype="multipart/form-data">
                                {{ csrf_field() }}


                                <div class="form-group">
                                    <label>Plan Name</label>
                                    <input disabled type="text" class="form-control"
                                        value="{{ old('name') ?? $plan->plan_name }}">
                                </div>
                                <h4>Plan Features</h4>
                                <div id="features" class="mb-3">
                                    @if (old('features'))
                                        @foreach (old('features') as $f)
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <input type="text" value="{{ $f }}" class="form-control "
                                                        name="features[]">
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-danger btn-xs  Remove-feature"><i
                                                            class="fa fa-times">Remove</i></a>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach ($plan->planFeatures as $f)
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                    <input type="text" value="{{ $f->feature }}" class="form-control "
                                                        name="features[]">
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-danger btn-xs  Remove-feature"><i
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
                                    <button type="submit" class="btn btn-success btn-sm">
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
                                            <button type="button" class="btn btn-danger btn-xs  Remove-feature"><i
                                                    class="fa fa-times">Remove</i></a>
                                            </button>
                                        </div>
                                    </div>
                `)
            })

            $(document).on('click', '.Remove-feature', function() {
                $(this).closest('.form-group').remove()
            })

        })
    </script>
@endsection
