<?php /*$class = $thread->isUnread(Auth::id()) ? 'alert-info' : ''; */?>
<div class="col-sm-6 margin-bot">
    <div class="card text-center">
        <div class="card-header">
            <a href="{{ route('messages.show', $thread->id) }}">{{ $thread->subject }}</a>
            {{--({{ $thread->userUnreadMessagesCount(Auth::id()) }} unread)--}}</div>
        <div class="card-body">
            {{ $thread->latestMessage->body }}

        </div>
        <div class="card-footer">
            <h6 class="card-title"><strong>Creator:</strong> {{ $thread->creator()->name }}</h6>
            <h6 class="card-title"><strong>Participants:</strong> {{ $thread->participantsString(Auth::id()) }}</h6>
        </div>
    </div>
</div>
