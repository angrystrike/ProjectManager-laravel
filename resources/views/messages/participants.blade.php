@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
        @if (count($participants))
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Current participants</h4>
                </div>
                <div class="card-body">
                    @foreach($participants as $participant)
                        <div class="card border-rounded">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <a href="/users/{{ $participant->id }}">{{ $participant->email }}</a> <br>
                                    <small>joined on <span class="text-success">{{ $participant->created_at }}</span></small>
                                </h4>
                            </div>
                            <div class="card-body">
                                <b>Name: </b>
                                <p>{{ $participant->name }}</p>
                                <b>Last visit:</b>
                                @if ($participant->last_read)
                                    <p class="text-success">{{ $participant->last_read }}</p>
                                @else
                                    <p>User did not visit this conversation yet</p>
                                @endif
                                @if ($isCreator)
                                    <button type="button" class="btn btn-danger js-kick-from-thread" data-id="{{ $participant->id }}" data-thread_id="{{ $thread_id }}">Kick</button>
                                @endif
                            </div>
                        </div>
                        <br>
                    @endforeach
                </div>
            </div>
        @else
            <h4 class="text-center">There are no users in this conversation besides the creator</h4>
        @endif
    </div>

    <div class="col-sm-3">
        <ul class="list-group">
            <h4>Actions: </h4>
            <li class="list-group-item"><a href="/messages">My Messages</a></li>
            <li class="list-group-item"><a href="/messages/create">New Message</a></li>
        </ul>
    </div>
@endsection
