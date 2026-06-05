@extends('admin')

@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">View Plan Features</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">View Plan Features</h3>
                        </div>
                        <div class="panel-body">
                            <label>Plan Name:</label>
                            <h4> {{ $package->name }}</h4>

                            <label>Plan Features:</label>
                            <div>
                                @foreach ($features as $f)
                                    <li>{{ $f->feature }}</li>
                                @endforeach

                            </div>
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
