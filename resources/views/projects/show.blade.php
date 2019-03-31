@extends('layouts.app')
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="col-md-9 col-lg-9 col-sm-9 pull-left bg-light">
        <div class="container">
            <div class="jumbotron">
                <h1>{{ $project->name }}</h1>
                <p class="lead">{{ $project->description }}</p>
            </div>
            <br>
            @include('partials.comments')
            <div class="container-fluid">
                <form method="post" action="{{ route('comments.store') }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="commentable_type" value="App\Models\Project">
                    <input type="hidden" name="commentable_id" value="{{ $project->id }}">

                    <div class="form-group">
                        <label for="comment-content">Comment:</label>
                        <textarea placeholder="Enter comment"
                                  id="comment-content"
                                  name="body"
                                  rows="3" spellcheck="false"
                                  class="form-control autosize-target text-left">
                        </textarea>
                    </div>

                    <div class="form-group">
                        <label for="comment-content">Proof of work done (Url/Photos):</label>
                        <textarea placeholder="Enter url or screenshots"
                                  id="comment-content"
                                  name="url"
                                  rows="2" spellcheck="false"
                                  class="form-control autosize-target text-left">
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

    <div class="col-sm-3 pull-right">
        <div class="sidebar-module">
            <h4>Actions: </h4>
            <ol class="list-unstyled">
                @if ($project->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                    <li><a href="/projects/{{ $project->id }}/edit"><i class="fas fa-edit"></i> Edit</a></li>
                @endif
                <li><a href="/projects/create"><i class="fas fa-plus-circle"></i> Create new Project</a></li>
                <li><a href="/tasks/create/{{ $project->id }}"><i class="fas fa-tasks"></i> Add Task</a></li>
                <li><a href="/projects"><i class="fas fa-briefcase"></i> My projects</a></li>
                <br>
                @if ($project->user_id == Auth::user()->id || Auth::user()->role_id == 1)
                    <li>
                        <a
                            href="#"
                            class="js-delete"
                            onclick="var result = confirm('Are you sure you wish to delete this Project?');
                            if( result ){
                                  event.preventDefault();
                                  document.getElementById('delete-form').submit();
                            }
                              "
                        >
                            <i class="fas fa-trash"></i> Delete
                        </a>

                        <form id="delete-form" action="{{ route('projects.destroy', [$project->id]) }}"
                              method="POST" class="hidden">
                            <input type="hidden" name="_method" value="delete">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endif
            </ol>
            <h5>Add members:</h5>
                    <form id="add-user" action="{{ route('projects.adduser') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="input-group">
                            <input class="form-control" name="project_id" value="{{ $project->id }}" type="hidden">
                            <input type="text" class="form-control" id="email" required name="email" placeholder="Email">

                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">Add!</button>
                            </span>
                        </div>
                    </form>
                <br>
                <h5>Current members:</h5>
                <ul class="list-group">
                    @if (count($project->users) > 0)
                         @foreach($project->users as $user)
                            <li class="list-group-item ">{{ $user->email }} <button type="button" class="btn btn-primary btn-sm js-delete-member" data-project_id="{{ $project->id }}" data-user_id="{{ $user->id  }}">Remove</button></li>
                            <p>Project: {{ $project->id  }}</p>
                            <p>User: {{ $user->id  }}</p>
                        @endforeach
                    @else
                        <h6>No active members yet</h6>
                    @endif
                </ul>

        </div>
    </div>
@endsection
{{--<i class="fas fa-window-close js-deleteMember" data-projectID="{{ $project->id  }}" data-userID="{{ $user->id }}"></i>--}}
{{--<button type="button" class="btn btn-primary btn-sm js-deleteMember" data-projectID="{{ $project->id  }}" data-userID="{{ $user->id }}">Remove</button>--}}
<script>
    $(document).ready(function(){
        $(".js-delete-member").click(function(){
            let project_id = $(this).data("project_id");
            let user_id = $(this).data("user_id");
            let token = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                url: "member/"+project_id+"/"+user_id,
                type: 'DELETE',
                data: {
                    "project_id": project_id,
                    "user_id": user_id,
                    "_token": token,
                },
                success: function (){
                    console.log("it Works");
                }
            });

        });
    });

</script>
