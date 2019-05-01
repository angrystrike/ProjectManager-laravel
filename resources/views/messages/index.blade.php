@extends('layouts.app')

@section('content')
    <div class="col-sm-12 margin-bot">
        <h4 class="text-center">Your conversations:</h4>
    </div>
    @each('partials.thread', $threads, 'thread', 'partials.no-threads')

    <div class="col-sm-12 text-center">
        <div class="d-inline-block">{{ $threads->links() }}</div>
    </div>
@endsection

