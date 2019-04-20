@extends('layouts.app')

@section('content')
    <div class="col-sm-10 center">
        <div class="card">
            <div class="card-header">Search</div>
            <div class="card-body">
                <form method="GET" action="{{ route('search') }}" class="form-inline">
                    <input class="form-control mr-sm-2" type="text" name="search" placeholder="Type something" aria-label="Search" value="{{ $search }}">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Find</button>
                </form>
                @if (!empty($search))
                    <div class="table-responsive">
                        <h4 class="text-center margin-heading">Total matches found: {{ $totalCount }}</h4>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                @if (count($users) > 0)
                                    <th>Users</th>
                                @endif
                                @if (count($companies) > 0)
                                    <th>Companies</th>
                                @endif
                                @if (count($projects) > 0)
                                    <th>Projects</th>
                                @endif
                                @if (count($tasks) > 0)
                                    <th>Tasks</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @for ($i = 0; $i < $rowsAmount; $i++)
                                <tr>
                                    @if (count($users) > 0 && count($users) <= $i)
                                        <th></th>
                                    @endif
                                    @if (isset($users[$i]))
                                        <th><a href="/users/{{ $users[$i]->id }}">{{ $users[$i]->name }}</a></th>
                                    @endif

                                    @if (count($companies) > 0 && count($companies) <= $i)
                                        <th></th>
                                    @endif
                                    @if (isset($companies[$i]))
                                        <th><a href="/companies/{{ $companies[$i]->id }}">{{ $companies[$i]->name }}</a></th>
                                    @endif

                                    @if (count($projects) > 0 && count($projects) <= $i)
                                        <th></th>
                                    @endif
                                    @if (isset($projects[$i]))
                                        <th><a href="/projects/{{ $projects[$i]->id }}">{{ $projects[$i]->name }} </a></th>
                                    @endif

                                    @if (count($tasks) > 0 && count($tasks) <= $i)
                                        <th></th>
                                    @endif
                                    @if (isset($tasks[$i]))
                                        <th><a href="/tasks/{{ $tasks[$i]->id }}">{{ $tasks[$i]->name }}</a> </th>
                                    @endif
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
