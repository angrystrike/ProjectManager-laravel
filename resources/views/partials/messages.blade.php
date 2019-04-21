<div class="card">
    @if ($message->user->id == Auth::id())
        <h5 class="card-header bg-light-green text-white">{{ $message->user->name }}</h5>
    @else
        <h5 class="card-header">{{ $message->user->name }}</h5>
    @endif
    <div class="card-body">
        <p>{{ $message->body }}</p>
        <div class="text-muted">
            <small>Posted {{ $message->created_at->diffForHumans() }}</small>
        </div>
    </div>
</div>
<br>
