@if (count($comments) > 0)
    <div class="card">
        <div class="card-header text-center margin-heading">
            <h4 class="card-title">
                Comments:
            </h4>
        </div>
        <div class="card-body">
            @foreach($comments as $comment)
                <div class="card border-rounded">
                    <div class="card-header">
                        <h4 class="card-title">
                            <a href="/users/{{ $comment->user->id }}">{{ $comment->user->email }}</a>
                            <br>
                            <small>
                                commented on {{ $comment->created_at }}
                            </small>
                        </h4>
                    </div>
                    <div class="card-body">
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
                </div>
                <hr>
            @endforeach

        </div>

    </div>
    <div class="row justify-content-center">{{ $comments->links() }}</div>
@else
    <h4 class="text-center margin-comments"> No comments created yet</h4>
@endif




