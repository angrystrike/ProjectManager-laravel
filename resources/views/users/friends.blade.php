@extends('layouts.app')

@section('content')
    <div class="col-sm-10 center">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#friends" role="tab">Friends</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#pending" role="tab">Pending requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#sent" role="tab">Sent requests</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="friends">
                        @if (count($friends) > 0)
                            <ul class="list-unstyled">
                                @foreach($friends as $friend)
                                    <li class="list-group-item">
                                        <a href="/users/{{ $friend->id }}">{{ $friend->email }}</a>
                                        <button type="button" class="btn btn-danger float-right margin-btn">Remove</button>
                                        <button type="button" class="btn btn-success float-right margin-btn">Write</button>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No friends added yet</p>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="pending" role="tabpanel">
                        @if (count($requests) > 0)
                            <ul class="list-unstyled">
                                @foreach($requests as $request)
                                    <li class="list-group-item">
                                        <a href="/users/{{ $request->sender_id }}">{{ $request->email }}</a>   {{ Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                        <button type="button" class="btn btn-danger float-right margin-btn">Deny</button>
                                        <button type="button" class="btn btn-success float-right js-accept-friend" data-sender_id="{{ $request->sender_id }}">Accept</button>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            Nothing to show
                        @endif
                    </div>
                    <div class="tab-pane fade" id="sent" role="tabpanel">..3.</div>
                </div>
            </div>
        </div>
    </div>
@endsection

