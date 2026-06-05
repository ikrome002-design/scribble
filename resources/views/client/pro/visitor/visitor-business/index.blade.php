@extends('pro')

@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
    @include('partials.styles')
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Businesses</h2>
            <div>
                <a class="btn btn-success " href="/visitorBusiness/create">Create Business/Premise/Office</a>
            </div>
            <p class="mt-3"><a class="btn btn-success btn-sm btn-xs" style="margin-bottom : 6px; margin-left : 6px;"
                    href="{{ '/visitors/create' }}{{ isset($sub->id) ? '?sub_id=' . $sub->id : '' }}">
                    <i class="fa fa-plus"></i>
                    Add Visitor
                </a>
            <p>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Businesses</h3>
                        </div>
                        <div class="panel-body p-none">
                            {{ $dataTable->table() }}
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
        $('body').delegate(".delete", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            Swal.fire({
                text: 'Are you sure, you want to delete this business. All visitors connected to this business will be deleted.',
                confirmButtonText: 'Ok',
                showCancelButton: true,

            }).then((result) => {
                if (result.isConfirmed) {
                    $(`<form method="post" action=${url}>
                               @csrf
                               @method('DELETE')
                            </form>`).appendTo('body').submit();
                }
            })

        })
    </script>
@endsection
