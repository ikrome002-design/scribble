<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ app_config('AppName') }} Staff Change password</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    {!! Html::style('assets/libs/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('assets/libs/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/css/style.css') !!}
    {!! Html::style('assets/css/responsive.css') !!}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
                            <h3 class="panel-title text-center"> Change password of staff account</h3>
                        </div>
                        <div class="panel-body">
                            <div>
                                @include('notification.notify')
                            </div>
                            <form class="" role="form" method="post" action="">
                                <div class="form-group form-group-default required">
                                    <label for="user name">Unique Id</label>
                                    <input type="text" value="{{ old('unique_id') }}" class="form-control" required
                                        name="unique_id">
                                </div>
                                <div class="form-group form-group-default required">
                                    <label for="user name">Your Email</label>
                                    <input type="email" value="{{ old('email') }}" class="form-control" required
                                        name="email">
                                </div>
                                <div class="form-group form-group-default required">
                                    <label for="user name">Enter OTP sent your email</label>
                                    <input type="number" name="otp" value="{{ old('otp') }}"
                                        class="form-control" required>
                                </div>
                                <div class="form-group form-group-default required">
                                    <label for="password">{{ language_data('Password') }}</label>
                                    <input type="password" class="form-control" required name="password">
                                </div>
                                <div class="form-group form-group-default required">
                                    <label for="password">Confirm Password</label>
                                    <input type="password" class="form-control" required name="password_confirmation">
                                </div>

                                <input type="submit" class="btn btn-primary btn-block btn-lg" value="Change Password">
                            </form>
                            <br>



                        </div>
                    </div>
                    <div class="panel-other-acction">
                        <div class="text-sm text-center">
                            If you don't have OTP , Contact your business owner to generate OTP;
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
</body>

</html>
