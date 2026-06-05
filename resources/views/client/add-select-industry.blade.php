<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{app_config('AppName')}} {{language_data('User Registration')}}</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    {!! Html::style("assets/libs/bootstrap/css/bootstrap.min.css") !!}
    {!! Html::style("assets/libs/font-awesome/css/font-awesome.min.css") !!}
    {!! Html::style("assets/css/style.css") !!}
    {!! Html::style("assets/css/responsive.css") !!}
    {!! Html::style("assets/libs/bootstrap-select/css/bootstrap-select.min.css") !!}
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
                        <h3 class="panel-title text-center">{{language_data('User Registration')}}</h3>
                    </div>
                    <div class="panel-body">

                        @include('notification.notify')

                        <form class="" role="form" method="post" action="{{url('user/add-user-industry')}}">


                            <div class="form-group">
                                <label for="Country">Select Industry</label>
                                <select name="industry" class="form-control selectpicker" data-live-search="true">
                                    <option value="personal">Personal</option>
                                    <option value="sole">Sole Proprietor</option>
                                    <option value="business">Business</option>
                                    <option value="school">School</option>
                                    <option value="nog">Non-Governmental Agency</option>
                                </select>
                            </div>
                 
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="submit" class="btn btn-primary btn-block btn-lg" value="Next">
                        </form>
                        <br>

                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
{!! Html::script("assets/libs/jquery-1.10.2.min.js") !!}
{!! Html::script("assets/libs/jquery.slimscroll.min.js") !!}
{!! Html::script("assets/libs/bootstrap/js/bootstrap.min.js") !!}
{!! Html::script("assets/libs/bootstrap-select/js/bootstrap-select.min.js") !!}
{!! Html::script("assets/js/scripts.js") !!}
<script>
    $("#first_name").focus();
</script>
</body>
</html>
