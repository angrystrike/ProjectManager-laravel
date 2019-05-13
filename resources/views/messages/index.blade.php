@extends('layouts.app')

@section('content')
    <div class="col-sm-12 margin-bot">
        <h4 class="text-center">Your conversations:</h4>
    </div>
    {{--@each('partials.thread', $threads, 'thread', 'partials.no-threads')--}}
    <?php /*$class = $thread->isUnread(Auth::id()) ? 'alert-info' : ''; */?>
    @if (count($threads) > 0)
        @foreach ($threads as $thread)
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
                            @for ($i = 0; $i < count($thread->participantsEmails()); $i++)
                                <a href="/users/{{ $thread->participantsUserIds()[$i] }}">
                                    {{ $thread->participantsEmails()[$i] }},
                                </a>
                            @endfor
                        </h6>
                    </div>
                </div>
                @if ($thread->creator()->id == Auth::id())
                    <div class="row justify-content-center">
                        <button type="button" class="btn btn-sm btn-danger js-delete-conversation" data-id="{{ $thread->id }}">Delete conversation</button>
                    </div>
                @endif
            </div>
        @endforeach
        <div class="col-sm-12 text-center">
            <div class="d-inline-block">{{ $threads->links() }}</div>
        </div>
    @else
        <p>No conversations created for now</p>
    @endif
@endsection

