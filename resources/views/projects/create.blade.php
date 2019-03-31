@extends('layouts.app')

@section('content')
    <div class="col-md-9 col-lg-9 col-sm-9 pull-left">
        <h3 class="text-center">Create new Project</h3>
            <form method="post" action="{{ route('projects.store') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="project-name">Name:</label>
                    <input   placeholder="Enter name"
                             id="project-name"
                             required
                             name="name"
                             spellcheck="false"
                             class="form-control form-control-lg"
                    />
                </div>

                @if($companies == null)
                    <input
                        class="form-control form-control-lg"
                        type="hidden"
                        name="company_id"
                        value="{{ $company_id }}"
                    />

                 @endif

                @if($companies != null)
                    <div class="form-group">
                        <label for="company-content">Select company:</label>

                        <select name="company_id" class="form-control form-control-lg" >
                            @foreach($companies as $company)
                                <option value="{{$company->id}}"> {{$company->name}} </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group">
                    <label for="project-content">Description:</label>
                    <textarea placeholder="Enter description"
                              id="project-content"
                              name="description"
                              rows="5" spellcheck="false"
                              class="form-control form-control-lg ">
                    </textarea>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block"
                           value="Submit"/>
                </div>

            </form>
    </div>

    <div class="col-sm-3 pull-right">
        <ul class="list-group">
            <h4>Actions: </h4>
            <li class="list-group-item"><a href="/projects">All Projects</a></li>
        </ul>
    </div>


@endsection
