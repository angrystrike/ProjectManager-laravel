@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
        <div class="jumbotron text-center">
            <h1>{{ $company->name }}</h1>
            <p class="lead">{{ $company->description }}</p>
            <p class="text-success">created at {{ $company->created_at }}</p>
            <p class="lead">by <a href="/users/{{ $creator->id }}">{{ $creator->email }}</a> </p>
        </div>

        <div class="row">
            @foreach($company->projects as $project)
                <div class="col-sm-4">
                    <h2>{{ $project->name }}</h2>
                    <p class="text-success js-text-truncate">{{ $project->description }}</p>
                    <p><a class="btn btn-primary btn-block" href="/projects/{{ $project->id }}" role="button">View Project</a></p>
                </div>
            @endforeach
        </div>

        @include('partials.comments')

        @include('partials.new-comment-form', ["type" => "App\Models\Company", "id" => $company->id])

    </div>

    <div class="col-sm-3">
        <h4>Actions: </h4>
        <ol class="list-unstyled">
            @if (Auth::check() && ($company->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                <li><a href="/companies/{{ $company->id }}/edit"><i class="fas fa-edit"></i> Edit</a></li>
                <li><a href="/projects/create/{{ $company->id }}"><i class="fas fa-briefcase"></i> Add Project</a></li>
            @endif
            <li><a href="/companies/create"><i class="fas fa-plus-circle"></i> Create new Company</a></li>

            <li><a href="/companies"><i class="fas fa-building"></i> My Companies</a></li>
            <br>
                @if (Auth::check() && ($company->user_id == Auth::user()->id || Auth::user()->role_id == 1))
                <li>
                    <a href="#" class="js-delete">
                        <i class="fas fa-trash"></i> Delete
                    </a>

                    <form id="delete-form" action="{{ route('companies.destroy', [$company->id]) }}"
                          method="POST" class="hidden">
                        @method('delete')
                        @csrf
                    </form>
                </li>
            @endif
        </ol>
    </div>

@endsection
