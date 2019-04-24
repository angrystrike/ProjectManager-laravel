@if (count($comments) > 0)
    <div class="message-container hidden">
        <div class="alert alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong class="message">4</strong>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-center">
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
                        <p class="js-comment-body" data-id="{{ $comment->id }}">{{ $comment->body }} </p>

                        @if (!empty($comment->url))
                            <b>Proof:</b>
                            <p class="js-comment-url" data-id="{{ $comment->id }}">
                                {{ $comment->url }}
                            </p>
                        @else
                            <p class="js-comment-url" data-id="{{ $comment->id }}"><b>No proof provided</b></p>
                        @endif

                        @if ($comment->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                            <button type="button" class="btn btn-danger js-delete-comment"
                                    data-id="{{ $comment->id }}">
                                Delete
                            </button>
                            <button type="button" class="btn btn-info js-edit-comment text-white"
                                    data-id="{{ $comment->id }}"
                                    data-body="{{ $comment->body }}"
                                    data-url="{{ $comment->url }}">
                                Edit
                            </button>
                        @endif
                    </div>
                </div>
                <br>
            @endforeach

        </div>

    </div>
    <div class="row justify-content-center">{{ $comments->links() }}</div>
@else
    <h4 class="text-center"> No comments created yet</h4>
@endif




