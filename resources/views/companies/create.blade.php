@extends('layouts.app')

@section('content')
    <div class="col-md-9 col-lg-9 col-sm-9 pull-left">
        <h3 class="text-center">Create new Company</h3>
            <form method="post" action="{{ route('companies.store') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="company-name">Name:</label>
                    <input   placeholder="Enter name"
                             id="company-name"
                             required
                             name="name"
                             spellcheck="false"
                             class="form-control form-control-lg"
                    />
                </div>

                <div class="form-group">
                    <label for="company-content">Description:</label>
                    <textarea placeholder="Enter description"
                              id="company-content"
                              name="description"
                              rows="5" spellcheck="false"
                              class="form-control form-control-lg">
                    </textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block"
                           value="Submit"/>
                </div>
            </form>
    </div>

    <div class="col-sm-3 col-sm-3 col-sm-3 pull-right">
        <ul class="list-group">
            <h4>Actions: </h4>
            <li class="list-group-item"><a href="/companies">All Companies</a></li>
        </ul>
    </div>
@endsection
