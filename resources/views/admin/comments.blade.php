@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        @if (count($comments) > 0)
            <div class="card">
                <div class="card-heading text-center margin-heading">
                    <h4 class="card-title">
                        Comments:
                    </h4>
                </div>
                <div class="card-body">
                    <ul class="media-list">
                        @foreach($comments as $comment)
                            <li class="media border-rounded">
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        {{ $comment->user->email }}
                                        <br>
                                        <small>
                                            commented on {{ $comment->created_at }}
                                        </small>
                                    </h4>
                                    <p>
                                        {{ $comment->body }}
                                    </p>
                                    <b>Proof:</b>
                                    <p>
                                        {{ $comment->url }}
                                    </p>
                                    <button type="button" class="btn btn-danger js-delete" data-id="{{ $comment->id }}">Delete</button>
                                </div>
                            </li>
                            <hr>
                        @endforeach
                    </ul>
                </div>

            </div>
        @else
            <h4 class="text-center margin-comments"> No comments created yet</h4>
        @endif
    </div>
@endsection

<script src="{{ URL::asset('js/main.js') }}"></script>
