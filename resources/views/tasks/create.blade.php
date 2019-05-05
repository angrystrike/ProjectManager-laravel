@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
        @if ($project_id == null && count($projects) == 0)
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong> Task cannot be created before Project. Try to create any Project first.</strong>
            </div>
        @endif

        <h3 class="text-center">Create new Task</h3>
        <form method="post" action="{{ route('tasks.store') }}">
            @csrf

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

            <div class="form-group">
                <label for="task-description">Description:</label>
                <textarea placeholder="Enter description"
                          id="task-description"
                          name="description"
                          rows="5" spellcheck="false"
                          class="form-control form-control-lg"></textarea>
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
                    <label>Select Project:</label>
                    <select name="project_id" class="form-control form-control-lg">
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="form-group">
                <label for="task-kind">Type: </label>
                <select name="kind" id="task-kind" class="form-control form-control-lg">
                    <option>Proposal</option>
                    <option>Enhancement</option>
                    <option>Bug</option>
                </select>
            </div>

            <div class="form-group">
                <label for="task-priority">Priority: </label>
                <select name="priority" id="task-priority" class="form-control form-control-lg">
                    <option>Trivial</option>
                    <option>Minor</option>
                    <option>Major</option>
                    <option>Critical</option>
                </select>
            </div>

            <div class="form-group">
                <label for="task-days">Days:</label>
                <input placeholder="Enter days"
                       id="task-days"
                       type="number"
                       min="1"
                       max="1000"
                       required
                       name="days"
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


