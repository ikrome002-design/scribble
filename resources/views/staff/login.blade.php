<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ app_config('AppName') }} {{ language_data('Login') }}</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    {!! Html::style('assets/libs/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('assets/libs/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/css/style.css') !!}
    {!! Html::style('assets/css/responsive.css') !!}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="/assets/css/sweetalert.css">
    <style>
        .app-logo-inner * {
            max-width: 242px;
            height: auto;
        }
    </style>

</head>

<body>
    <main id="wrapper" class="wrapper">
        <div class="container jumbo-container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="app-logo-inner text-center">
                        <img src="<?php echo asset(app_config('AppLogo')); ?>" alt="logo" class="bar-logo">
                    </div>
                    <div class="panel panel-30">

                        <div class="panel-heading">
                            <h3 class="panel-title text-center">Sign to your staff account</h3>
                        </div>

                        <div class="panel-body">
                            <div>
                                @include('notification.notify')
                            </div>
                            <form class="" role="form" method="post" action="">
                                @csrf
                                <div class="form-group form-group-default required">
                                    <label for="uniqe id">Unique ID</label>
                                    <input type="text" value="{{ old('unique_id') }}" class="form-control" required
                                        name="unique_id">
                                </div>
                                <div class="form-group form-group-default required">
                                    <label for="user name">Your Email</label>
                                    <input type="email" value="{{ old('email') }}" class="form-control" required
                                        name="email">
                                </div>
                                <div class="form-group form-group-default required">
                                    <label for="password">{{ language_data('Password') }}</label>
                                    <input type="password" class="form-control" required name="password">
                                </div>
                                <div class="form-group m-t-20 m-b-20">
                                    <div class="coder-checkbox">
                                        <input type="checkbox" checked name="remember">
                                        <span class="co-check-ui"></span>
                                        <label>Remember Me</label>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary btn-block btn-lg" value="Login">
                            </form>
                            <br>
                        </div>
                    </div>
                    <div class="panel-other-acction">
                        <div class="text-sm text-center">
                            <div class="panel-other-acction">
                                <div class="text-sm text-center">
                                    <button id="forgot-password" type="button"
                                        class="text-complete btn border-0">Forgot
                                        Password ?</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    {!! Html::script('assets/libs/jquery-1.10.2.min.js') !!}
    {!! Html::script('assets/libs/jquery.slimscroll.min.js') !!}
    {!! Html::script('assets/libs/bootstrap/js/bootstrap.min.js') !!}
    {!! Html::script('assets/js/scripts.js') !!}
    <script src="/assets/js/sweetalert.min.js"></script>
    <script>
        $('body').delegate("#forgot-password", "click", function(e) {
            e.preventDefault();
            var url = this.href;
            Swal.fire({
                title: 'Enter your details to change your password',
                confirmButtonText: 'Submit',
                showCancelButton: true,
                html: `<form action='/forgot-password' method='post' id="forgot-password-form">
                <input name="unique_id" placeholder="Unique Id"class="swal2-input"><br>
                <input type="email" placeholder="email" name="email" class="swal2-input"><br>
                </form>
                  `,

            }).then((result) => {
                if (result.isConfirmed) {
                    $('#forgot-password-form').submit()
                }
            })

        })
    </script>
</body>

</html>
