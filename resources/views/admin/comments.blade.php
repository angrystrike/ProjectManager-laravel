@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
       @include ('partials.comments')
    </div>
@endsection

<script src="{{ URL::asset('js/main.js') }}"></script>
