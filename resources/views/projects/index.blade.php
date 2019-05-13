@extends('layouts.app')

@section('content')
    <div class="col-md-6 col-lg-6 center">
        <div class="card">
            <div class="card-header text-center text-white bg-primary"> Projects <a class="btn btn-primary" href="/projects/create">Create new</a></div>
            <div class="card-body">
                <ul class="list-group">
                    @if (!is_null($projects) && count($projects) > 0)
                        @foreach($projects as $project)
                            <li class="list-group-item">
                                <a href="/projects/{{ $project->id }}"> {{ $project->name }} </a>
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
