@extends('layouts.app')

@section('content')
    <div class="col-sm-9 float-left">
            <form method="post" action="{{ route('tasks.update', [$task->id]) }}">
                {{ csrf_field() }}

                <input type="hidden" name="_method" value="put">

                <div class="form-group">
                    <label for="task-name">Name:</label>
                    <input   placeholder="Enter name"
                             id="task-name"
                             required
                             name="name"
                             spellcheck="false"
                             class="form-control form-control-lg"
                             value="{{ $task->name }}"
                    />
                </div>

                <div class="form-group">
                    <label for="task-days">Days:</label>
                    <input   placeholder="Enter days"
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
                    <input   placeholder="Enter hours"
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
                    <input   placeholder="Enter duration"
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

                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block"
                           value="Submit"/>
                </div>
            </form>
        </div>

    <div class="col-sm-3">
        <ul class="list-group">
            <h4>Actions: </h4>
            <li class="list-group-item"><a href="/tasks/{{ $task->id }}">View Task</a></li>
            <li class="list-group-item"><a href="/tasks">My Tasks</a></li>
        </ul>
    </div>
@endsection
