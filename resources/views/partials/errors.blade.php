{{--
@if ( isset($errors) && $errors)
    <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        @dump($errors)
       --}}
{{-- @foreach ($errors->all() as $error)
            <li><strong>{!! $error !!}</strong></li>
        @endforeach--}}{{--

    </div>
@endif
--}}
@if (session()->has('errors'))
    <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>
            {!! session()->get('errors') !!}
        </strong>
    </div>
@endif

