@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
        <div class="jumbotron text-center">
            <h1>{{ $project->name }}</h1>
            <p class="lead">{{ $project->description }}</p>
            <p class="text-success">created at {{ $project->created_at }}</p>
            <p class="lead">by <a href="/users/{{ $creator->id }}">{{ $creator->email }}</a> </p>
            @if ($company)
                <p class="lead">Company title: <a href="/companies/{{ $company->id }}">{{ $company->name }}</a>
                </p>
            @endif
        </div>

        <div class="row">
            @foreach($project->tasks as $task)
                <div class="col-sm-4">
                    <h2>{{ $task->name }}</h2>
                    <p class="text-success">Duration: {{ $task->duration }}</p>
                    <p><a class="btn btn-primary btn-block" href="/tasks/{{ $task->id }}" role="button">View Task</a>
                    </p>
                </div>
            @endforeach
        </div>

        @include('partials.comments')

        @include('partials.new-comment-form', ["type" => "App\Models\Project", "id" => $project->id])

    </div>


    <div class="col-sm-3">
        <div class="sidebar-module">
            <h4>Actions: </h4>
            <ol class="list-unstyled">
                @if (Auth::check() && ($project->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                    <li><a href="/projects/{{ $project->id }}/edit"><i class="fas fa-edit"></i> Edit</a></li>
                    <li><a href="/tasks/create/{{ $project->id }}"><i class="fas fa-tasks"></i> Add Task</a></li>
                @endif
                <li><a href="/projects/create"><i class="fas fa-plus-circle"></i> Create new Project</a></li>

                <li><a href="/projects"><i class="fas fa-briefcase"></i> My projects</a></li>
                <br>
                @if (Auth::check() && ($project->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                    <li>
                        <a href="#" class="js-delete">
                            <i class="fas fa-trash"></i> Delete
                        </a>

                        <form id="delete-form" action="{{ route('projects.destroy', [$project->id]) }}"
                              method="POST" class="hidden">
                            @method('delete')
                            @csrf
                        </form>
                    </li>
                @endif
            </ol>
            @if (Auth::check() && ($project->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                <h5>Add members:</h5>
                <form id="add-user" action="{{ route('projects.addUser') }}" method="POST">
                    @csrf

                    <div class="input-group">
                        <input class="form-control" name="project_id" value="{{ $project->id }}" type="hidden">
                        <input type="text" class="form-control" id="email" required name="email"
                               placeholder="Email">

                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">Add!</button>
                        </span>
                    </div>
                </form>
                <br>
            @endif
            <h5>Current members:</h5>
            <ul class="list-group">
                @if (count($project->users) > 0)
                    @foreach($project->users as $user)
                        <li class="list-group-item "><a href="/users/{{ $user->id }}">{{ $user->email }}</a>
                            @if (Auth::check() && ($project->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                                <button type="button" class="btn btn-danger btn-sm js-delete-project-member float-right"
                                        data-project_id="{{ $project->id }}" data-user_id="{{ $user->id  }}">
                                    Remove
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

