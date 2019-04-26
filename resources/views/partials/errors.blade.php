@if (session()->has('errors'))
    @if (is_string(Session::get('errors')))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ Session::get('errors') }}</strong>
        </div>
    @else
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            @foreach (session('errors')->all() as $error)
                <strong>{{ $error }}</strong>
                <br>
            @endforeach
        </div>
    @endif
@endif
