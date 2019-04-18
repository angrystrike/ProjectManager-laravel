@extends('layouts.app')
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')

    <div class="col-sm-9">
        <div class="jumbotron text-center">
            <h1>{{ $task->name }}</h1>
            <p class="lead">Duration: {{ $task->duration }}</p>
            <p class="text-success">created at {{ $task->created_at }}</p>
            <p class="lead">by <a href="/users/{{ $creator->id }}">{{ $creator->email }}</a> </p>
            <p class="lead">Project title: <a href="/projects/{{ $project->id }}">{{ $project->name }}</a></p>
        </div>
        <br>
        @include('partials.comments')

        <form method="post" action="{{ route('comments.store') }}">
            {{ csrf_field() }}

            <input type="hidden" name="commentable_type" value="App\Models\Task">
            <input type="hidden" name="commentable_id" value="{{ $task->id }}">

            <div class="form-group">
                <label for="comment-content" class="margin-heading">Comment:</label>
                <textarea placeholder="Enter comment"
                          id="comment-content"
                          name="body" required
                          rows="3" spellcheck="false"
                          class="form-control form-control-lg"></textarea>
            </div>

            <div class="form-group">
                <label for="comment-content">Proof of work done (Url/Photos):</label>
                <textarea placeholder="Enter url or screenshots"
                          id="comment-content"
                          name="url"
                          rows="2" spellcheck="false"
                          class="form-control form-control-lg">
                            </textarea>
            </div>

            <div class="form-group text-center">
                <input type="submit" class="btn btn-primary btn-block"
                       value="Submit"/>
            </div>
        </form>

    </div>


    <div class="col-sm-3">
        <div class="sidebar-module">
            <h4>Actions: </h4>
            <ol class="list-unstyled">
                @if ($task->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                    <li><a href="/tasks/{{ $task->id }}/edit"><i class="fas fa-edit"></i> Edit</a></li>
                @endif
                <li><a href="/tasks/create"><i class="fas fa-plus-circle"></i> Create new Task</a></li>
                <li><a href="/tasks"><i class="fas fa-briefcase"></i> My Tasks</a></li>
                <br>
                @if ($task->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                    <li>
                        <a href="#" class="js-delete">
                            <i class="fas fa-trash"></i> Delete
                        </a>

                        <form id="delete-form" action="{{ route('tasks.destroy', [$task -> id]) }}"
                              method="POST" class="hidden">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endif
            </ol>
            @if ($task->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                <h5>Add members:</h5>
                <form id="add-user" action="{{ route('tasks.addUser') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="input-group">
                        <input class="form-control" name="task_id" value="{{ $task->id }}" type="hidden">
                        <input type="text" class="form-control" id="email" required name="email"
                               placeholder="Email">

                        <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">Add!</button>
                            </span>
                    </div>
                </form>
                <br>
            @endif
            <h5>Current members:</h5>
            <ul class="list-group">
                @if (count($task->users) > 0)
                    @foreach($task->users as $user)
                        <li class="list-group-item "><a href="/users/{{$user->id}}">{{ $user->email }}</a>
                            @if ($task->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                                <button type="button"
                                        class="btn btn-primary btn-sm js-delete-member btn-danger float-right"
                                        data-task_id="{{ $task->id }}" data-user_id="{{ $user->id }}">Remove
                                </button>
                            @endif
                        </li>
                    @endforeach
                @else
                    <h6 class="text-muted">No active members yet</h6>
                @endif
            </ul>
        </div>
    </div>

@endsection

<script src="{{ URL::asset('js/main.js') }}"></script>
