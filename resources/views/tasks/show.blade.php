@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
        <div class="jumbotron text-center">
            <h2>{{ $task->name }}</h2>
            <h4><i>Type:</i> <mark>{{ $task->kind }}</mark></h4>
            <h4><i>Priority:</i> <mark>{{ $task->priority }}</mark></h4>
            <p class="lead">Days: <span class="underline">{{ $task->days }}</span></p>
            <p class="text-success">created at {{ $task->created_at }}</p>
            <p class="lead">by <a href="/users/{{ $creator->id }}">{{ $creator->email }}</a> </p>
            <p class="lead">Project title: <a href="/projects/{{ $project->id }}">{{ $project->name }}</a></p>
        </div>
        <br>
        @if (!empty ($task->description))
            <h4 class="text-center"> Task description </h4>
            <p>{{ $task->description }}</p>
            <br><br>
        @endif

        @include('partials.comments')

        @include('partials.new-comment-form', ["type" => "App\Models\Task", "id" => $task->id])

    </div>

    <div class="col-sm-3">
        <div class="sidebar-module">
            <h4>Actions: </h4>
            <ol class="list-unstyled">
                @if (Auth::check() && ($task->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                    <li><a href="/tasks/{{ $task->id }}/edit"><i class="fas fa-edit"></i> Edit</a></li>
                @endif
                <li><a href="/tasks/create"><i class="fas fa-plus-circle"></i> Create new Task</a></li>
                <li><a href="/tasks"><i class="fas fa-briefcase"></i> My Tasks</a></li>
                <br>
                @if (Auth::check() && ($task->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                    <li>
                        <a href="#" class="js-delete">
                            <i class="fas fa-trash"></i> Delete
                        </a>

                        <form id="delete-form" action="{{ route('tasks.destroy', [$task->id]) }}"
                              method="POST" class="hidden">
                            @method('delete')
                            @csrf
                        </form>
                    </li>
                @endif
            </ol>

            @if (Auth::check() && ($task->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                <h5>Add members:</h5>
                <form id="add-user" action="{{ route('tasks.addUser') }}" method="POST">
                    @csrf

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

            @if (count($task->users) > 0)
                <h5>Current members:</h5>
                <ul class="list-group">
                    @foreach($task->users as $user)
                        <li class="list-group-item"><a href="/users/{{$user->id}}">{{ $user->email }}</a>
                            @if (Auth::check() && $task->user_id == Auth::id())
                                <button type="button" class="btn btn-primary btn-sm js-delete-task-member btn-danger float-right"
                                        data-task_id="{{ $task->id }}" data-user_id="{{ $user->id }}">Remove
                                </button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <h6 class="text-muted">No active members yet</h6>
            @endif
        </div>
    </div>
@endsection

