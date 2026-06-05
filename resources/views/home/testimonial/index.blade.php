@extends('admin')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Testimonials</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Testimonials</h3>
                        </div>

                        <a class="btn btn-success btn-xs" style="margin-bottom : 6px; margin-left : 6px;"
                            href="/testimonials/create"><i class="fa fa-plus"></i>Add testimonials</a>


                        <div class="panel-body p-none">
                            <table class="table data-table table-hover">
                                <thead style="white-space: nowrap;">
                                    <tr>
                                        <th>{{ language_data('SL') }}#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Occupation</th>
                                        <th>Rating</th>
                                        <th>Rated at</th>
                                        <th>Review</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody style="white-space: nowrap;">

                                    @foreach ($testimonials as $t)
                                        <tr>
                                            <td data-label="SL">{{ $loop->iteration }}</td>
                                            <td data-label="image">
                                                @if ($t->image)
                                                    <img style="width:50px"
                                                        src="/assets/img/testimonials/{{ $t->image }}" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                <p>{{ $t->name }}</p>
                                            </td>
                                            <td data-label="occupation">
                                                <p>{{ $t->occupation }}</p>
                                            </td>
                                            <td data-label="rating">
                                                <p>{{ $t->rating }}</p>
                                            </td>

                                            <td data-label="rated_at">
                                                <p>{{ $t->rated_at }}</p>
                                            </td>
                                            <td data-label="review">
                                                <p>{{ substr($t->review, 0, 20) }} ...</p>
                                            </td>
                                            <td data-label="Actions">
                                                <a class="btn btn-complete btn-xs"
                                                    href="/testimonials/{{ $t->id }}/edit"><i
                                                        class="fa fa-edit"></i>Edit</a>
                                                <a class="btn btn-danger btn-xs cdelete"
                                                    href="/testimonials/{{ $t->id }}"> <i
                                                        class="fa fa-trash"></i>Delete</a>
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
                scrollX: true
            });

            /*For Delete Price Plan*/
            $("body").delegate(".cdelete", "click", function(e) {
                e.preventDefault();
                var url = this.href;
                bootbox.confirm("Are you sure ?", function(result) {
                    if (result) {
                        $('.panel-body').append(`
                     <form method="post" id="delete-form" action="${url}">
                        @method('DELETE') 
                        </form>
                     `)
                        $('#delete-form').submit()
                    }
                });
            });

        });
    </script>
@endsection
