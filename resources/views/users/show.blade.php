@extends('layouts.app')
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="col-sm-10 center">
        @if (Auth::user()->id == $user->id)
            <h3 class="text-center">Welcome to your Profile page!</h3>
        @endif
        <div class="jumbotron text-center">
            <h2>Login: {{ $user->name }}</h2>
            <p class="lead">Email: {{ $user->email }}</p>
            <p class="text-info">created at {{ $user->created_at }}</p>
            @if (count ($jobProjects) == 0)
                <p>User is not assigned to any Projects</p>
            @endif
            @if (count ($jobTasks) == 0)
                <p>User is not assigned to any Tasks</p>
            @endif
        </div>

        @if (count($jobProjects) > 0)
            <br>
            <h5 class="text-center">Currently working on these Projects: </h5>
            <ul class="list-group">
                @foreach($jobProjects as $jobProject)
                    <li class="list-group-item"> <a href="/projects/{{ $jobProject->id }}">{{ $jobProject->name }}</a></li>
                @endforeach
            </ul>
        @endif

        @if (count($jobTasks) > 0)
            <br>

            <h5 class="text-center">Currently working on these Tasks: </h5>
            <ul class="list-group">
                @foreach($jobTasks as $jobTask)
                    <li class="list-group-item"> <a href="/tasks/{{ $jobTask->id }}">{{ $jobTask->name }}</a></li>
                @endforeach
            </ul>

        @endif

        <br>

        @if (count($companies) > 0)
            <div class="card">
                <div class="card-header text-center text-white bg-primary"> Companies <a
                        class="btn btn-primary" href="/companies/create">Create new</a></div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($companies as $company)
                            <li class="list-group-item">
                                <a href="/companies/{{ $company->id }}">{{ $company->name }}</a>
                                @if (Auth::user()->id == $user->id || Auth::user()->role_id == 1)
                                    <button type="button"
                                            class="btn btn-danger btn-sm float-right margin-btn js-delete">Delete
                                    </button>
                                    <button type="button" class="btn btn-dark btn-sm float-right "><a
                                            href="/companies/{{ $company->id }}/edit" class="text-white">Edit</a>
                                    </button>
                                @endif
                                <form id="delete-form" action="{{ route('companies.destroy', [$company->id]) }}"
                                      method="POST" class="hidden">
                                    <input type="hidden" name="_method" value="delete">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        @else
            <h4 class="margin-heading text-center">No Companies created yet </h4>
        @endif

        <br><br>

        @if (count($projects) > 0)
            <div class="card">
                <div class="card-header text-center text-white bg-primary"> Projects <a
                        class="pull-right btn btn-primary" href="/projects/create">Create new</a></div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($projects as $project)
                            <li class="list-group-item">
                                <a href="/projects/{{ $project->id }}">{{ $project->name }}</a>
                                @if (Auth::user()->id == $user->id || Auth::user()->role_id == 1)
                                    <button type="button"
                                            class="btn btn-danger btn-sm float-right margin-btn js-delete">Delete
                                    </button>
                                    <button type="button" class="btn btn-dark btn-sm float-right "><a
                                            href="/projects/{{ $project->id }}/edit" class="text-white">Edit</a>
                                    </button>
                                @endif
                                <form id="delete-form" action="{{ route('projects.destroy', [$project->id]) }}"
                                      method="POST" class="hidden">
                                    <input type="hidden" name="_method" value="delete">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        @else
            <h4 class="margin-heading text-center">No Projects created yet </h4>
        @endif

        <br><br>

        @if (count($tasks) > 0)

            <div class="card">
                <div class="card-header text-center text-white bg-primary"> Tasks <a
                        class="btn btn-primary" href="/tasks/create">Create new</a></div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($tasks as $task)
                            <li class="list-group-item">
                                <a href="/tasks/{{ $task->id }}">{{ $task->name }}</a>
                                @if (Auth::user()->id == $user->id || Auth::user()->role_id == 1)
                                    <button type="button"
                                            class="btn btn-danger btn-sm float-right margin-btn js-delete">Delete
                                    </button>
                                    <button type="button" class="btn btn-dark btn-sm float-right "><a
                                            href="/tasks/{{ $task->id }}/edit" class="text-white">Edit</a></button>
                                @endif
                                <form id="delete-form" action="{{ route('tasks.destroy', [$task->id]) }}"
                                      method="POST" class="hidden">
                                    <input type="hidden" name="_method" value="delete">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        @else
            <h4 class="margin-heading text-center">No Tasks created yet </h4>
        @endif

        <br><br>
        @include ('partials.comments')
    </div>
@endsection

<script src="{{ URL::asset('js/main.js') }}"></script>
