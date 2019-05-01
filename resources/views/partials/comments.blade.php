@if (count($comments) > 0)
    <div id="messageBox"></div>
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
                        <div class="js-view-comment-section">
                            <p class="js-comment-body">{{ $comment->body }} </p>
                            @if (!empty($comment->url))
                                <b>Proof:</b>
                                <p class="js-comment-url">{{ $comment->url }}</p>
                            @else
                                <p class="js-comment-url"><b>No proof provided</b></p>
                            @endif
                        </div>

                        <div class="js-edit-comment-section hidden">
                            <div class="form-group">
                                <label for="comment-body"><b>Edited comment body:</b></label>
                                <textarea id="comment-body" name="body" class="form-control form-control-lg">{{ $comment->body }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="comment-url"><b>Url:</b></label>
                                <textarea id="comment-url" name="url" class="form-control form-control-lg">{{ $comment->url }}</textarea>
                            </div>
                        </div>
                        @if (Auth::check() && ($comment->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                            <button type="button" class="btn btn-danger js-delete-comment"
                                    data-id="{{ $comment->id }}">
                                Delete
                            </button>
                            <button type="button" class="btn btn-info js-edit-comment text-white"
                                    data-id="{{ $comment->id }}">
                                Edit
                            </button>
                        @endif
                    </div>
                </div>
                <br>
            @endforeach

        </div>
        <div class="row justify-content-center">{{ $comments->links() }}</div>
    </div>

@else
    <h4 class="text-center"> No comments created yet</h4>
@endif





