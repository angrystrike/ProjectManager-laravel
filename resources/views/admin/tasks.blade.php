@extends('layouts.app')

@section('content')
    <div class="col-md-6 col-lg-6 center">
        <div class="card">
            <div class="card-header text-center text-white bg-primary"> Tasks <a class="pull-right btn btn-primary" href="/tasks/create">Create new</a></div>
            <div class="card-body">
                <ul class="list-group">
                    @if (count($tasks) > 0)
                        @foreach($tasks as $task)
                            <li class="list-group-item">
                                <a href="/tasks/{{ $task->id }}">{{ $task->name }}</a>
                                <button type="button" class="btn btn-danger btn-sm float-right mr-left-8 js-delete">Delete</button>
                                <button type="button" class="btn btn-dark btn-sm float-right "><a href="/tasks/{{ $task->id }}/edit" class="text-white">Edit</a></button>

                                <form id="delete-form" action="{{ route('tasks.destroy', [$task->id]) }}"
                                      method="POST" class="hidden">
                                    @method('delete')
                                    @csrf
                                </form>
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
