@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="col-sm-12 margin-bot">
        <h4 class="text-center">Your conversations:</h4>
    </div>
    @each('partials.thread', $threads, 'thread', 'partials.no-threads')
    <div class="center">{{ $threads->links() }}</div>
@endsection
<script src="{{ URL::asset('js/main.js') }}"></script>
