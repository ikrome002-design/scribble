@extends('pro')

@section('style')
    {!! Html::style('assets/libs/data-table/datatables.min.css') !!}
    @include('partials.styles')
@endsection


@section('content')
    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Mpesa Integration Guide</h2>
        </div>

        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-8 m-auto">
                    <div class="panel">
                        <div class="panel-body p-4 fs-4">
                            <p>Dear {{ auth('client')->user()->fname }} {{ auth('client')->user()->lname }} ,</p>
                            <p>
                                We understand that integrating your M-Pesa account with Scribble PRO can be a
                                complex process. That's why we have created a co mprehensive guide to help you
                                through the process.
                            </p>
                            <p>
                                To learn more about the details you need and the steps you need to take in order to
                                integrate your M-Pesa account with Scribble PRO, please visit the following website:
                                www.tinyurl.com/M-Pesa-Integration.
                                We have included a template guide of the M-Pesa Administrator account details that
                                you need to submit in your Scribble PRO application.
                            </p>
                            <p>
                                If you don't have an M-Pesa Administrator account, kindly send a request to Safaricom
                                via the following email to create one for you: M-PESABusiness@safaricom.co.ke and
                                Lipanampesa@safaricom.co.ke. In your email request, include the following details:
                            <ol>
                                <li>Organization Name</li>
                                <li>Type of M-Pesa account: Buy Goods Till Account or Paybill Account</li>
                                <li>M-Pesa account Number</li>
                                <li>The request should come from the email used in M -Pesa Account application</li>
                            </ol>
                            </p>
                            <p>
                                When requesting Safaricom to create an M -Pesa Business Administrator account,
                                please attach the following documents in your email:
                            <ol>
                                <li>Filled M-Pesa Administrator Form</li>
                                <li>Share a current CR12 (validity within 90 days) to validate the directorship.</li>
                                <li>I.D. Number of Organization Director</li>
                                <li>
                                    Letter to nominate the applicant as the Administrator for the requested M -Pesa
                                    Administrator account.
                                </li>
                            </ol>
                            </p>
                            <p>
                                We have attached a template letter of nomination of an M -Pesa Administrator for
                                your convenience.<br>
                                <a href="/mpesa/M-Pesa Integration Details - Template.pdf" download>M-Pesa
                                    Integration
                                    Details
                                    - Template</a><br>
                                <a href="/mpesa/Template Letter For Nomination of an M-Pesa Business Administrator.pdf"
                                    download>Template Letter For Nomination of an M-Pesa Business Administrator</a>
                            </p>
                            <p>
                                If you do not have an M-Pesa Business Administrator account, you can download and
                                fill the M-Pesa Business Administrator form from Safaricom
                                website:https://www.safaricom.co.ke/images/Downloads/M-PESA-Business-AdministatorForm.pdf.
                            </p>
                            <p>
                                You have any questions or concerns about the integration process, please don't
                                hesitate to contact us by raising a ticket on the Scribble platform or by sending an
                                email to scribble.support@citrus.co.ke.
                            </p>
                            <p>
                                Thank you for choosing Scribble PRO for your Bulk SMS needs.
                            </p>
                            <p>
                                Best regards,<br>
                                Scribble Team
                            </p>

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
    <script>
        $(document).ready(function() {

            $('.data-table').DataTable({
                language: {
                    url: '{!! url('assets/libs/data-table/i18n/' . get_language_code()->language . '.lang') !!}'
                },
                scrollX: true
            });
        })
    </script>
@endsection
