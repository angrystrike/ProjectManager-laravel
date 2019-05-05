@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
        <form method="post" action="{{ route('tasks.update', [$task->id]) }}">
            @csrf
            @method('put')

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
                <label for="task-description">Description:</label>
                <textarea placeholder="Enter description"
                          id="task-description"
                          name="description"
                          rows="5" spellcheck="false"
                          class="form-control form-control-lg"></textarea>
            </div>

            <div class="form-group">
                <label for="task-kind">Type: </label>
                <select name="kind" id="task-kind" class="form-control form-control-lg">
                    <option>Enhancement</option>
                    <option>Bug</option>
                    <option>Proposal</option>
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
