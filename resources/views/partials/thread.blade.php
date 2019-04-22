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
            <h6 class="card-title">
                <strong>Creator:</strong>
                <a href="/users/{{ $thread->creator()->id }}">
                    {{ $thread->creator()->name }}
                </a>
            </h6>

            <h6 class="card-title">
                <strong>Participants:</strong>
                @for ($i = 0; $i < count($thread->participantsNames()); $i++)
                    <a href="/users/{{ $thread->participantsUserIds()[$i] }}">
                        {{ $thread->participantsNames()[$i] }},
                    </a>
                @endfor
            </h6>
        </div>
    </div>
</div>
