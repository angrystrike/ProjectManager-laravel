<div class="card">
    @if ($message->user->id == Auth::id())
        <h5 class="card-header bg-light-green text-white">{{ $message->user->name }}</h5>
    @else
        <h5 class="card-header">{{ $message->user->name }}</h5>
    @endif
    <div class="card-body">
        <p class="js-message-body">{{ $message->body }}</p>
        <div class="text-muted">
            <small>Posted {{ $message->created_at->diffForHumans() }}</small>
        </div>
        @if ($message->user->id == Auth::id())
            <button type="button" class="btn btn-danger js-delete-message mr-top-10" data-id="{{ $message->id }}">Delete</button>
            <button type="button" class="btn btn-primary js-edit-message mr-top-10" data-id="{{ $message->id }}">Edit</button>
        @endif
    </div>
</div>
<br>
