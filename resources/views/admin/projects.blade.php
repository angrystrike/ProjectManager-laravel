@extends('layouts.app')

@section('content')
    <div class="col-md-6 col-lg-6 center">
        <div class="card">
            <div class="card-header text-center text-white bg-primary"> Projects <a class="pull-right btn btn-primary" href="/projects/create">Create new</a></div>
            <div class="card-body">
                <ul class="list-group">
                    @if (count($projects) > 0)
                        @foreach($projects as $project)
                            <li class="list-group-item">
                                <a href="/projects/{{ $project->id }}">{{ $project->name }}</a>
                                <button type="button" class="btn btn-danger btn-sm float-right margin-btn js-delete">Delete</button>
                                <button type="button" class="btn btn-dark btn-sm float-right "><a href="/projects/{{ $project->id }}/edit" class="text-white">Edit</a></button>

                                <form id="delete-form" action="{{ route('projects.destroy', [$project->id]) }}"
                                      method="POST" class="hidden">
                                    @method('delete')
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item">
                            <p class="text-center">No Projects yet</p>
                        </li>
                    @endif
                </ul>

            </div>
        </div>
    </div>
@endsection
