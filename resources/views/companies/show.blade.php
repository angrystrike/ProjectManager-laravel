@extends('layouts.app')

@section('content')
    <div class="col-md-8 col-lg-8 col-sm-8 pull-left">
        <div class="container">
            <div class="jumbotron text-center">
                <h1>{{ $company->name }}</h1>
                <p class="lead">{{ $company->description }}</p>
            </div>

            <div class="row">
                @foreach($company->projects as $project)
                    <div class="col-md-4 col-sm-6 col-6">
                        <h2>{{ $project->name }}</h2>
                        <p class="text-success">{{ $project->description }}</p>
                        <p><a class="btn btn-primary" href="/projects/{{ $project->id }}" role="button">View Project</a></p>
                    </div>
                @endforeach
            </div>
            <div class="row">
                @include('partials.comments')
            </div>
            <div class="row">
                <div class="container-fluid">
                    <form method="post" action="{{ route('comments.store') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="commentable_type" value="App\Models\Company">
                        <input type="hidden" name="commentable_id" value="{{ $company->id }}">

                        <div class="form-group margin-heading">
                            <label for="comment-content">Comment:</label>
                            <textarea placeholder="Enter comment"
                                      id="comment-content"
                                      name="body"
                                      rows="3" spellcheck="false"
                                      class="form-control form-control-lg">
                            </textarea>
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
            </div>
        </div>
    </div>

    <div class="col-sm-4 pull-right">
        <div class="sidebar-module">
            <h4>Actions: </h4>
            <ol class="list-unstyled">
                @if ($company->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                    <li><a href="/companies/{{ $company->id }}/edit"><i class="fas fa-edit"></i> Edit</a></li>
                @endif
                <li><a href="/companies/create"><i class="fas fa-plus-circle"></i> Create new Company</a></li>
                <li><a href="/projects/create/{{ $company->id }}"><i class="fas fa-briefcase"></i> Add Project</a></li>
                <li><a href="/companies"><i class="fas fa-building"></i> My Companies</a></li>
                <br>
                @if ($company->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                    <li>
                        <a
                            href="#"
                            onclick="
                      var result = confirm('Are you sure you wish to delete this Company?');
                          if( result ){
                                  event.preventDefault();
                                  document.getElementById('delete-form').submit();
                          }
                              "
                        >
                            <i class="fas fa-trash"></i> Delete
                        </a>

                        <form id="delete-form" action="{{ route('companies.destroy', [$company->id]) }}"
                              method="POST" class="hidden">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endif
            </ol>
        </div>
    </div>
@endsection
