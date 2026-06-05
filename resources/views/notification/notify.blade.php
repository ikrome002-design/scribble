@if (Session::has('message'))
    <div class="alert fs-5 {{ Session::has('message_important') ? 'alert-danger' : 'alert-success' }} alert-dismissible"
        role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
        {{ Session::get('message') }}
    </div>
@endif

@if (isset($errors) && count($errors) > 0)
    <div class="alert alert-danger alert-dismissible fs-5" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif
