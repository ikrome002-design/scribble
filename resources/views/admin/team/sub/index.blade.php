@extends('admin')

@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
    @include('partials.styles')
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Team Subscriptions</h2>
        </div>

        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Team Subscriptions</h3>
                            <a class="btn btn-success" style="margin-bottom : 6px; margin-left : 6px;"
                                href="/team/subscription/create"><i class="fa fa-plus"></i>
                                Add Team Subscription</a>
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
        $("body").delegate(".delete", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this staff from the merchant account',
                confirmButtonText: 'Yes',
                showCancelButton: true,
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`<form hidden method="post" action=${url}>
                                   @csrf
                                   @method('DELETE')
                                </form>
                  `).appendTo('body').submit()
                }
            })
        })
    </script>
@endsection
