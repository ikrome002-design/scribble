@extends('register')


@section('body')
    <div class="container my-3">
        <div class="panel">
            <div class="panel-body wizard-content">
                <div id="example-form" action="#" class="tab-wizard wizard-circle wizard clearfix">
                    <h6>Step 1</h6>
                    <section>
                        <br />
                        <div class="row">
                            <div class="col-sm-4 col-sm-push-4" style="margin: 0 auto;">

                                <form id="step-1">
                                    <input type="hidden" name="step" value="1">
                                    <div class="form-card">
                                        <label class="fieldlabels" for="account">Select Type of Account *</label>
                                        <select class="form-control" name="account" id="account">
                                            <option value="" selected disabled>Open this select menu</option>
                                            @foreach ($accounts as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </section>

                    <h6>Step 2</h6>
                    <section>
                        <div class="row">


                            <form id="step-2">
                                <input type="hidden" name="step" value="2">
                                <div class="row">
                                    <div class="col-md-4 m-auto mb-4">
                                        <label class="text-center">Select Package *</label>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach ($plans as $p)
                                        <div class="col-md-4 mx-auto">
                                            <div class="card-header text-center">
                                                <h4 class="my-0 font-weight-normal position-relative"> {{ $p->name }}

                                                    @if ($p->popular == 'Yes')
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">
                                                            Popular
                                                        </span>
                                                    @endif
                                                </h4>


                                            </div>

                                            <div class="card-body">
                                                <h1 class="card-title pricing-card-title">{{ $p->total }}<small
                                                        class="text-muted">/
                                                        mo</small></h1>
                                                <ul class="list-unstyled mt-3 mb-4">
                                                    @foreach ($p->planFeatures as $f)
                                                        <li>{{ $f->feature }}</li>
                                                    @endforeach
                                                </ul>
                                                <input type="radio" class="btn-check package-select w-100"
                                                    value="{{ $p->id }}" name="package"
                                                    id="package{{ $p->id }}" autocomplete="off">
                                                <label
                                                    class="btn package-label w-100 package{{ $p->id }} btn-success border-0 button-style"
                                                    for="package{{ $p->id }}">Select</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </form>
                        </div>
                    </section>

                    <h6>Step3</h6>
                    <section>
                        <form id="step-3">
                            <input type="hidden" name="step" value="3">
                            <div class="form-card">

                                <h4>Personal Detials</h4>
                                <br />
                                <label class="fieldlabels">First Name: *</label>
                                <input class="form-control" type="text" name="fname" placeholder="First Name" />
                                <br />
                                <label class="fieldlabels">Last Name: *</label>
                                <input class="form-control" type="text" name="lname" placeholder="Last Name" />
                                <br />
                                <label class="fieldlabels">Email Address: *</label>
                                <input class="form-control" type="email" name="email" placeholder="Email Address" />
                                <br />
                                <label class="fieldlabels">Password: *</label>
                                <input class="form-control" type="password" name="pwd" id="password"
                                    placeholder="Password" />
                                <br />
                                <label class="fieldlabels">Confirm Password: *</label>
                                <input class="form-control" type="password" name="cpwd" placeholder="Confirm Password" />
                                <br />
                            </div>

                        </form>

                    </section>

                    <h6>Step 4</h6>



                    <section>

                        <form id="step-4">

                            <input type="hidden" name="step" value="4">
                            <div id="personal">

                                <h4>Contact Details</h4>
                                <br />
                                <label class="fieldlabels">Phone Number: *</label>
                                <input class="form-control" type="text" name="phone_number" placeholder="Phone Number" />
                                <br />
                                <label class="fieldlabels">Recovery Email Address: *</label>
                                <input class="form-control" type="email" name="recovery_email_address"
                                    placeholder="Recovery Email Address" />
                                <br />
                                <label class="fieldlabels">Date of Birth: *</label>
                                <input class="form-control" type="date" name="date" />
                                <br />

                                <label class="fieldlabels">Gender: *</label>
                                <br />
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="1"
                                        id="male">
                                    <label class="form-check-label fieldlabels" for="male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="0"
                                        id="female">
                                    <label class="form-check-label fieldlabels" for="female">
                                        Female
                                    </label>
                                </div>

                            </div>



                            <br />


                            <div id="buisness">

                                <h4>Business Details</h4>
                                <br />
                                <label class="fieldlabels">Business Name: *</label>
                                <input class="form-control" type="text" name="business_name"
                                    placeholder="Business Name" />
                                <br />
                                <label class="fieldlabels">Type of Business: *</label>
                                <input class="form-control" type="text" name="type_of_business"
                                    placeholder="Type of Business" />
                                <br />
                                <label class="fieldlabels">Phone Number: *</label>
                                <input class="form-control" type="text" name="phone_number_business"
                                    placeholder="Phone Number" />
                                <br />
                                <label class="fieldlabels">Recovery Email Address: *</label>
                                <input class="form-control" type="text" name="recovery_email_address_business"
                                    placeholder="Recovery Email Address" />
                                <br />
                                <label class="fieldlabels">Date of Birth: *</label>
                                <input class="form-control" type="date" name="date_business" />
                                <br />
                                <label class="fieldlabels">Gender: *</label>
                                <br />
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender_business" value="1"
                                        id="male">
                                    <label class="form-check-label fieldlabels" for="male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender_business" value="0"
                                        id="female">
                                    <label class="form-check-label fieldlabels" for="female">
                                        Female
                                    </label>
                                </div>

                            </div>


                            <br />


                            <div id="school">


                                <h4>School Details</h4>
                                <br />
                                <label class="fieldlabels">School Name: *</label>
                                <input class="form-control" type="text" name="school_name"
                                    placeholder="Business Name" />
                                <br />
                                <label class="fieldlabels">Type of School: *</label>
                                <select name="type_of_school" id="type_of_school" name="type_of_school"
                                    class="form-control">
                                    <option value="">Select type of school</option>
                                    <option value="1">Pre-School</option>
                                    <option value="2">Primary</option>
                                    <option value="3">High School</option>
                                </select>
                                <br />
                                <label class="fieldlabels">Phone Number: *</label>
                                <input class="form-control" type="text" name="phone_number_school"
                                    placeholder="Phone Number" />
                                <br />
                                <label class="fieldlabels">Recovery Email Address: *</label>
                                <input class="form-control" type="text" name="recovery_email_address_school"
                                    placeholder="Recovery Email Address" />
                                <br />
                                <label class="fieldlabels">Date of Birth: *</label>
                                <input class="form-control" type="date" name="date_school" />
                                <br />
                                <label class="fieldlabels">Gender: *</label>
                                <br />
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender_school" value="1"
                                        id="male">
                                    <label class="form-check-label fieldlabels" for="male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender_school" value="0"
                                        id="female">
                                    <label class="form-check-label fieldlabels" for="female">
                                        Female
                                    </label>
                                </div>


                            </div>

                            <br />

                            <div id="organization_details">

                                <label class="fieldlabels">Organization Name: *</label>
                                <input class="form-control" type="text" name="organization_name"
                                    placeholder="Organization Name" />

                                <br />

                                <select name="type_of_organization" id="type_of_organization" name="type_of_organization"
                                    class="form-control">
                                    <option value="">Type of Organization</option>
                                    <option value="1">Religious Centre</option>
                                </select>

                                <br />

                                <label class="fieldlabels">Phone Number: *</label>
                                <input class="form-control" type="text" name="phone_number_org"
                                    placeholder="Phone Number" />

                                <br />
                                <label class="fieldlabels">Recovery Email Address: *</label>
                                <input class="form-control" type="text" name="recovery_email_address_org"
                                    placeholder="Recovery Email Address" />

                                <br />

                                <label class="fieldlabels">Date of Birth: *</label>
                                <input class="form-control" type="date" name="date_org" />


                                <br />

                                <label class="fieldlabels">Gender: *</label>
                                <br />
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender_org" value="1"
                                        id="male">
                                    <label class="form-check-label fieldlabels" for="male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender_org" value="0"
                                        id="female">
                                    <label class="form-check-label fieldlabels" for="female">
                                        Female
                                    </label>
                                </div>


                            </div>




                        </form>





                    </section>


                    <h6>Step5</h6>
                    <section>
                        <form id="final_step" method="POST" action="{{ route('steps') }}">
                            <div class="form-card">

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="tc"
                                        checked>
                                    <label class="form-check-label fieldlabels" for="tc">
                                        Agree Terms & Conditions
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="privacy"
                                        checked>
                                    <label class="form-check-label fieldlabels" for="privacy">
                                        Privacy Policy
                                    </label>
                                </div>
                            </div>

                        </form>

                    </section>
                    <div id="reg-errors" class="text-danger text-center py-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {


            $('#step-1').validate({
                rules: {
                    account: {
                        required: true,
                    }
                },
            });

            $('#step-2').validate({
                rules: {
                    package: {
                        required: true,
                    }
                },
            });

            $('#step-3').validate({
                rules: {
                    fname: {
                        required: true,
                    },
                    lname: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    pwd: {
                        required: true
                    },
                    cpwd: {
                        required: true
                    }

                },


                messages: {
                    fname: {
                        required: 'First name field is required'
                    },
                    lname: {
                        required: 'Last name field is required'
                    },
                    pwd: {
                        required: 'Password field is required'
                    },
                    cpwd: {
                        required: 'Confirm password field is required'
                    }
                }
            });


            $('#step-4').validate({
                rules: {
                    phone_number: {
                        required: true,
                    },
                    recovery_email_address: {
                        required: true,
                    },

                    Date: {
                        required: true,
                    },

                    business_name: {
                        required: true,
                    },

                    type_of_business: {
                        required: true,
                    },
                    email: {
                        required: true
                    }



                },
                messages: {
                    phone_number: {
                        required: 'Phone number field is required'
                    },
                    recovery_email_address: {
                        required: 'Recovery email field is required'
                    },
                    business_name: {
                        required: 'Buisness name field is required'
                    },
                    type_of_business: {
                        required: 'Type of Buisness field is required'
                    },

                }
            });


        });


        // apply ajax on form steps
        $("#example-form").steps({

            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',

            onStepChanging: function(event, currentIndex, newIndex) {


                if (currentIndex == 0 && newIndex == 1) {

                    callAjaxFunction($('#step-1').serialize());

                    var valid = $('#step-1').valid();

                    if (valid !== " ") {
                        return $('#step-1').valid();
                    }
                } else if (currentIndex == 1 && newIndex == 2) {
                    callAjaxFunction($('#step-2').serialize());
                    var check = $('#step-2').valid();
                    if (valid != " ") {
                        return $('#step-2').valid();
                    }
                } else if (currentIndex == 2 && newIndex == 3) {

                    $pers_name = $('#step-4').find('#personal').attr('data-name');
                    $buis_name = $('#step-4').find('#buisness').attr('data-name');
                    $school_name = $('#step-4').find('#school').attr('data-name');
                    $org_name = $('#step-4').find('#organization_details').attr('data-name');


                    if ($pers_name == "personal_form") {
                        $('#buisness').css('display', 'none');
                        $('#school').css('display', 'none');
                        $('#organization_details').css('display', 'none');

                    }

                    if ($buis_name == "buisness") {
                        $('#personal').css('display', 'none');
                        $('#school').css('display', 'none');
                        $('#organization_details').css('display', 'none');
                    }

                    if ($school_name == "school") {
                        $('#personal').css('display', 'none');
                        $('#buisness').css('display', 'none');
                        $('#organization_details').css('display', 'none');
                    }

                    if ($org_name == "other") {
                        $('#personal').css('display', 'none');
                        $('#buisness').css('display', 'none');
                        $('#school').css('display', 'none');
                    }


                    callAjaxFunction($('#step-3').serialize());
                    var valid = $('#step-3').valid();

                    if (valid == false) {
                        return $('#step-3').valid();
                    }

                } else if (currentIndex == 3 && newIndex == 4) {

                    callAjaxFunction($('#step-4').serialize());
                    var valid = $('#step-4').valid();

                    if (valid == false) {
                        return $('#step-4').valid();
                    }

                }

                return true;

            },


            onFinishing: function(event, currentIndex) {

                if (currentIndex == 4) {
                    $('#final_step').submit();
                }

            },

            labels: {
                current: "current step:",
                pagination: "Pagination",
                finish: "Finish",
                next: "Next",
                previous: "Prev",
            }

        });


        // calling ajax for steps
        function callAjaxFunction(data) {
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('steps') }}",
                data: data,
                success: function(response) {


                }
            });
        }


        // accoount change function call

        $(document).on('change', '#account', function() {

            let account_val = $(this).val();

            if (account_val == 1) {
                //add
                $('#personal').attr('data-name', 'personal_form');

                //remove
                $('#step-4').find('#buisness').removeAttr('data-name');
                $('#step-4').find('#school').removeAttr('data-name');
                $('#step-4').find('#organization_details').removeAttr('data-name');

            } else if (account_val == 2 || account_val == 3) {

                // remove
                $('#step-4').find('#personal').removeAttr('data-name');
                $('#step-4').find('#school').removeAttr('data-name');
                $('#step-4').find('#organization_details').removeAttr('data-name');

                // add
                $('#buisness').attr('data-name', 'buisness');
            } else if (account_val == 4) {
                // remove
                $('#step-4').find('#personal').removeAttr('data-name');
                $('#step-4').find('#buisness').removeAttr('data-name');
                $('#step-4').find('#organization_details').removeAttr('data-name');

                // add
                $('#school').attr('data-name', 'school');
            } else {
                // remove
                $('#step-4').find('#personal').removeAttr('data-name');
                $('#step-4').find('#buisness').removeAttr('data-name');
                $('#step-4').find('#school').removeAttr('data-name');

                //add
                $('#organization_details').attr('data-name', 'other');
            }

        });
        $(document).on('change', '.package-select', function() {
            $('.package-label').html('Select')
            var id = $(this).attr('id');
            $('.' + id).html('Selected')
            $("#example-form").steps("next", {});
        })
    </script>
@endsection
