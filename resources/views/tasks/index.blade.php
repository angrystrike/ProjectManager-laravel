@extends('layouts.app')

@section('content')
    <div class="col-md-6 col-lg-6 center">
        <div class="card">
            <div class="card-header text-center text-white bg-primary"> Tasks <a class="pull-right btn btn-primary" href="/tasks/create">Create new</a></div>
            <div class="card-body">
                <ul class="list-group">
                    @if (!is_null($tasks) && count($tasks) > 0)
                        @foreach($tasks as $task)
                            <li class="list-group-item">
                                <a href="/tasks/{{ $task->id }}"> {{ $task->name }} </a>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item">
                            <p class="text-center">No Tasks yet</p>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
