@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
        @if ((isset($participants) && count($participants) > 0) || isset($matchesCount))
            <form method="GET" action="{{ route('search.participants', $thread_id = Request::segment(2)) }}" class="card card-sm">
                <div class="card-body row no-gutters align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-search h4 text-body"></i>
                    </div>
                    <div class="col">
                        <input class="form-control form-control-lg form-control-borderless" name="search" type="search" placeholder="Type email or name" @if(isset($search) && !empty($search)) value="{{ $search }}" @endif>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-lg btn-success" id="searchBtn" type="submit">Search</button>
                    </div>
                </div>
            </form>
        @endif
        @if (isset($matchesCount) && $matchesCount == 0)
            <h4 class="text-center mr-top-25">Zero matches found with keyword: {{ $search }}</h4>
        @elseif (isset($matchesCount) && $matchesCount > 0)
            <div class="card mr-top-25">
                <div class="card-header">
                    <h4 class="card-title text-center">Matches found: {{ $matchesCount }}</h4>
                </div>
                <div class="card-body">
                    @foreach($result as $match)
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <a href="/users/{{ $match->id }}">{{ $match->email }}</a> <br>
                                    <small>joined on <span class="text-success">{{ $match->created_at }}</span></small>
                                </h4>
                            </div>
                            <div class="card-body">
                                <b>Name: </b>
                                <p>{{ $match->name }}</p>
                                <b>Last visit:</b>
                                @if ($match->last_read)
                                    <p class="text-success">{{ $match->last_read }}</p>
                                @else
                                    <p>User did not visit this conversation yet</p>
                                @endif
                                @if ($isCreator)
                                    <button type="button" class="btn btn-danger js-kick-from-thread" data-id="{{ $match->id }}" data-thread_id="{{ $thread_id }}">Kick</button>
                                @endif
                            </div>
                        </div>
                        <br>
                    @endforeach
                </div>
            </div>
        @endif

        @if (isset($participants) && count($participants) > 0)
            <div class="card mr-top-25" id="participants">
                <div class="card-header">
                    <h4 class="card-title text-center">Current participants</h4>
                </div>
                <div class="card-body">
                    @foreach($participants as $participant)
                        <div class="card">
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
                <div class="row justify-content-center">{{ $participants->links() }}</div>
            </div>
        @elseif (isset($participants) && count($participants) == 0)
            <h5 class="text-center mr-top-25">There are no participants in this conversation besides the creator</h5>
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
