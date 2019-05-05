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
                    <p class="text-success">{{ $project->description }}</p>
                    <p><a class="btn btn-primary btn-block" href="/projects/{{ $project->id }}" role="button">View Project</a>
                </div>
            @endforeach
        </div>

        @include('partials.comments')

        <form method="post" action="{{ route('comments.store') }}">
            @csrf

            <input type="hidden" name="commentable_type" value="App\Models\Company">
            <input type="hidden" name="commentable_id" value="{{ $company->id }}">

            <div class="form-group">
                <label for="comment-content" class="mr-top-25">Comment:</label>
                <textarea placeholder="Enter comment"
                          id="comment-content"
                          required
                          name="body"
                          rows="3" spellcheck="false"
                          class="form-control form-control-lg"></textarea>
            </div>

            <div class="form-group">
                <label for="comment-content">Proof of work done:</label>
                <textarea placeholder="Enter url or screenshots"
                          id="comment-content"
                          name="url"
                          rows="2" spellcheck="false"
                          class="form-control form-control-lg">
                </textarea>
            </div>

            <div class="form-group text-center">
                <input type="submit" class="btn btn-primary btn-block"
                       value="Submit"/>
            </div>
        </form>
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
