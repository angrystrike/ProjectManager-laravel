@extends('layouts.app')
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="col-sm-9">
        @if ($project_id == null && count($projects) == 0)
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>
                    Task cannot be created before Project. Try to create any Project first.
                </strong>
            </div>
        @endif

        <h3 class="text-center">Create new Task</h3>
        <form method="post" action="{{ route('tasks.store') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="task-name">Name:</label>
                <input placeholder="Enter name"
                       id="task-name"
                       required
                       name="name"
                       spellcheck="false"
                       class="form-control form-control-lg"
                />
            </div>

            @if ($projects == null)
                <input
                    class="form-control form-control-lg"
                    type="hidden"
                    name="project_id"
                    value="{{ $project_id }}"
                />

            @endif

            @if ($projects != null && count($projects) != 0)
                <div class="form-group">
                    <label for="project-content">Select Project:</label>
                    <select name="project_id" class="form-control form-control-lg">
                        @foreach($projects as $project)
                            <option value="{{$project->id}}"> {{$project->name}} </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="form-group">
                <label for="task-days">Days:</label>
                <input placeholder="Enter days"
                       id="task-days"
                       type="number"
                       min="1"
                       max="100"
                       required
                       name="days"
                       spellcheck="false"
                       class="form-control form-control-lg"
                />
            </div>

            <div class="form-group">
                <label for="task-hours">Hours:</label>
                <input placeholder="Enter hours"
                       id="task-hours"
                       type="number"
                       min="1"
                       max="1000"
                       required
                       name="hours"
                       spellcheck="false"
                       class="form-control form-control-lg"
                />
            </div>

            <div class="form-group">
                <label for="task-duration">Duration:</label>
                <input placeholder="Enter duration"
                       id="task-duration"
                       type="number"
                       min="1"
                       max="1000"
                       required
                       name="duration"
                       spellcheck="false"
                       class="form-control form-control-lg"
                />
            </div>

            <div class="form-group text-center">
                <input type="submit" class="btn btn-primary btn-block" id="submit"
                       value="Submit"/>
            </div>

        </form>
    </div>

    <div class="col-sm-3">
        <ul class="list-group">
            <h4>Actions: </h4>
            <li class="list-group-item"><a href="/tasks">All Tasks</a></li>
        </ul>
    </div>
    @if ($project_id == null && count($projects) == 0)
        <script>
            $('#submit').attr('disabled', 'disabled');
        </script>
    @endif
@endsection


