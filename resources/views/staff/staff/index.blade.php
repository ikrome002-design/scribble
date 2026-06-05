@extends('staff')

{{-- External Style Section --}}
@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
    @include('partials.styles')
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Staff
                @if (isset($work_history))
                    View Work History
                @endif
            </h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Staff</h3>
                        </div>

                        @if (count($add_roles) > 0)
                            <a class="btn btn-success btn-xs" style="margin-bottom : 6px; margin-left : 6px;"
                                href="/staff/create"><i class="fa fa-plus"></i>
                                Add Staff
                            </a>
                        @endif

                        <div class="panel-body p-none">
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <input type="text" hidden value="staff.{{ env('APP_DOMAIN') }}/change-password" id="link-otp">
    <input type="text" hidden
        value="Please use the following OTP. It expires after 30 minutes. If you want to change password,please click the following link and login to your account."
        id="message-otp">
    <input type="text" hidden value="staff.{{ env('APP_DOMAIN') }}/change-password" id="link-otp">
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
        $(document).ready(function() {
            $('.data-table').DataTable({
                language: {
                    url: '{!! url('assets/libs/data-table/i18n/' . get_language_code()->language . '.lang') !!}'
                },
                scrollX: true
            });
            $("body").delegate(".send-otp", "click", function(e) {
                e.preventDefault();
                staff_id = $(this).attr('id')
                $.ajax({
                    url: '/staff/generate/otp',
                    type: 'post',
                    data: {
                        staff_id: staff_id,
                        message: $('#message-otp').val(),
                        link: $('#link-otp').val(),
                        sendFalse: 'Yes',
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            title: 'OTP has been sent to the staff',
                            icon: 'success',
                        })
                    }
                })

            });
            $("body").delegate(".delete", "click", function(e) {
                e.preventDefault();
                var url = this.href;
                Swal.fire({
                    icon: 'question',
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

        });
    </script>
@endsection
