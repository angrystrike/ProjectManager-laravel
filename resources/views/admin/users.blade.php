@extends('layouts.app')

@section('content')
    <div class="col-md-6 col-lg-6 center">
        <div class="card">
            <div class="card-header text-center text-white bg-primary"> Users</div>
            <div class="card-body">
                <ul class="list-group">
                    @if (count($users) > 0)
                        @foreach($users as $user)
                            <li class="list-group-item">
                                <a href="/users/{{ $user->id }}">{{ $user->name }}</a>
                                <button type="button" class="btn btn-danger btn-sm float-right mr-left-8 js-delete-user" data-id="{{ $user->id }}">
                                    Delete
                                </button>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item">
                            <p class="text-center">No Users registered</p>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
