@if (Session::has('message'))
    <div class="alert fs-5 {{ Session::has('message_important') ? 'alert-danger' : 'alert-success' }} alert-dismissible fade show"
        role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span
                aria-hidden="true"></span></button>
        {{ Session::get('message') }}
    </div>
@endif

@if (isset($errors) && count($errors) > 0)
    <div class="alert fs-5 alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span
                aria-hidden="true"></span></button>
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif
