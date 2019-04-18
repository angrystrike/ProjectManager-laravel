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
                            @if ($comment->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                             <button type="button" class="btn btn-danger js-delete-comment" data-id="{{ $comment->id }}">Delete</button>
                            @endif
                        </div>
                    </li>
                    <hr>
                @endforeach
            </ul>
        </div>

    </div>
    <div class="offset-md-5 offset-3">{{ $comments->links() }}</div>
@else
    <h4 class="text-center margin-comments"> No comments created yet</h4>
@endif




