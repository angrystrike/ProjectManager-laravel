@extends('layouts.app')

@section('content')
    <div class="col-md-9 col-lg-9 col-sm-9 pull-left">
        <div class="container">
            <div class="jumbotron">
                <h1>{{ $task->name }}</h1>
                <p class="lead">{{ $task->description }}</p>
            </div>
            <br>
            @include('partials.comments')
            <div class="container-fluid">
                <form method="post" action="{{ route('comments.store') }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="commentable_type" value="App\Models\Task">
                    <input type="hidden" name="commentable_id" value="{{ $task->id }}">

                    <div class="form-group">
                        <label for="comment-content">Comment:</label>
                        <textarea placeholder="Enter comment"
                                  id="comment-content"
                                  name="body"
                                  rows="3" spellcheck="false"
                                  class="form-control form-control-lg">
                        </textarea>
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
        </div>

    </div>

    <div class="col-sm-3 pull-right">
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
                        <a
                            href="#"
                            class="js-delete"
                            onclick="var result = confirm('Are you sure you wish to delete this Task?');
                            if( result ){
                                  event.preventDefault();
                                  document.getElementById('delete-form').submit();
                            }
                              "
                        >
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

                <h5>Add members:</h5>
                <form id="add-user" action="{{ route('tasks.adduser') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="input-group">
                        <input class="form-control" name="task_id" value="{{ $task->id }}" type="hidden">
                        <input type="text" class="form-control" id="email" required name="email" placeholder="Email">

                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">Add!</button>
                        </span>
                    </div>
                </form>
                <br>
                <h5>Current members:</h5>
                <ul class="list-group">
                    @if (count($task->users) > 0)
                        @foreach($task->users as $user)
                            <li class="list-group-item">{{ $user->email }}</li>
                        @endforeach
                    @else
                        <h6>No active members yet</h6>
                    @endif
                </ul>
            </div>
        </div>
@endsection
