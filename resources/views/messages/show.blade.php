@extends('layouts.app')

@section('content')
    <div class="col-md-6">
        <h1>{{ $thread->subject }}</h1>
        @each('partials.messages', $thread->messages, 'message')

        @include('partials.form-message')
    </div>
@endsection
