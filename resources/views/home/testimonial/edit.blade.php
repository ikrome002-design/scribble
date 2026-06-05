@extends('admin')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/bootstrap3-wysihtml5-bower/bootstrap3-wysihtml5.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Edit Testimonials</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">

            @include('notification.notify')
            <div class="row">

                <div class="col-lg-7">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Testimonials</h3>
                        </div>
                        <div class="panel-body">
                            <form method="POST" enctype="multipart/form-data"
                                action="/testimonials/{{ $testimonial->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @method('PATCH')

                                <div class="form-group">
                                    <label>Name (optional)</label>
                                    <input type="text" class="form-control" value="{{ $testimonial->name }}"
                                        name="name">
                                </div>

                                <div class="form-group">
                                    <label>Occupation (optional)</label>
                                    <input type="text" class="form-control" value="{{ $testimonial->occupation }}"
                                        name="occupation">
                                </div>
                                <div class="form-group">
                                    <label>title of review (optional)</label>
                                    <input type="text" class="form-control" value="{{ $testimonial->title }}"
                                        name="title">
                                </div>
                                <div class="form-group">
                                    <label>Rating*</label>
                                    <select name="rating" class="selectpicker form-control" data-live-search="true">
                                        @for ($i = 1; $i < 6; $i++)
                                            <option @selected($i == $testimonial->rating) value="{{ $i }}">
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Review*</label>
                                    <textarea class="form-control" name="review" rows="10">{{ $testimonial->review }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>rated at plaform e.g google, facebook etc (optional)</label>
                                    <input type="text" value="{{ $testimonial->rated_at }}" class="form-control"
                                        name="rated_at">
                                </div>
                                <div class="form-group">
                                    <label>Url to rating (optional)</label>
                                    <input type="text" value="{{ $testimonial->rated_url }}" class="form-control"
                                        name="rated_url">
                                </div>
                                <div class="form-group">
                                    <img src="/assets/img/testimonials/{{ $testimonial->image }}" style="width:50px"
                                        alt=""><br>
                                    <label>Image, rename image without special characters before uploading
                                        (optional)</label>
                                    <input type="file" accept="image/*" class="form-control" name="image">
                                </div>

                                <button type="submit" name="add" class="btn btn-success"><i class="fa fa-plus"></i>
                                    Update testimonial
                                </button>
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
    {!! Html::script('assets/libs/wysihtml5x/wysihtml5x-toolbar.min.js') !!}
    {!! Html::script('assets/libs/bootstrap3-wysihtml5-bower/bootstrap3-wysihtml5.min.js') !!}
@endsection
