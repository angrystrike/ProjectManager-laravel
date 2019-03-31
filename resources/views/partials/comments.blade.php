<div class="col-md-12 col-sm-12 col-xs-12">
    @if (count($comments) > 0)
        <div class="card">
            <div class="card-heading text-center margin-heading">
                <h4 class="card-title">
                    Recent Comments
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
                            </div>
                        </li>
                        <hr>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <h4 class="text-center margin-comments"> Be the first one to comment!</h4>
    @endif



</div>
