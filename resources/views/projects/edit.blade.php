@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
            <form method="post" action="{{ route('projects.update', [$project->id]) }}">
                @csrf

                @method('put')

                <div class="form-group">
                    <label for="project-name">Name:</label>
                    <input   placeholder="Enter name"
                             id="project-name"
                             required
                             name="name"
                             spellcheck="false"
                             class="form-control form-control-lg"
                             value="{{ $project->name }}"
                    />
                </div>

                <div class="form-group">
                    <label for="project-content">Description:</label>
                    <textarea placeholder="Enter description"
                              id="project-content"
                              name="description"
                              rows="5" spellcheck="false"
                              class="form-control form-control-lg">{{ $project->description }}</textarea>
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
            <li class="list-group-item"><a href="/projects/{{ $project->id }}">View Project</a></li>
            <li class="list-group-item"><a href="/projects">My Projects</a></li>
        </ul>
    </div>
@endsection
