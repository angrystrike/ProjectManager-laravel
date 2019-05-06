@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
        <h4 class="text-center">{{ $thread->subject }}</h4>
        @each('partials.messages', $thread->messages, 'message')

        @include('partials.form-message')
    </div>

    <div class="col-sm-3">
        <ul class="list-group">
            <h4>Actions: </h4>
            <li class="list-group-item"><a href="/messages">My Messages</a></li>
            <li class="list-group-item"><a href="/messages/create">New Message</a></li>
            <li class="list-group-item"><a href="/threads/{{ $thread->id }}/participants">View current participants</a></li>
        </ul>
    </div>
@endsection
