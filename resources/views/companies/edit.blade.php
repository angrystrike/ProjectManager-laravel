@extends('layouts.app')

@section('content')
    <div class="col-sm-9">
            <form method="post" action="{{ route('companies.update', [$company->id]) }}">
                {{ csrf_field() }}

                @method('put')

                <div class="form-group">
                    <label for="company-name">Name:</label>
                    <input   placeholder="Enter name"
                             id="company-name"
                             required
                             name="name"
                             spellcheck="false"
                             class="form-control form-control-lg"
                             value="{{ $company->name }}"
                    />
                </div>

                <div class="form-group">
                    <label for="company-content">Description:</label>
                    <textarea placeholder="Enter description"
                              id="company-content"
                              name="description"
                              rows="5" spellcheck="false"
                              class="form-control form-control-lg">{{ $company->description }}
                    </textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block"
                           value="Submit"/>
                </div>
            </form>
    </div>

    <div class="col-sm-3">
        <ul class="list-group">
            <h4>Actions: </h4>
            <li class="list-group-item"><a href="/companies/{{ $company->id }}">View Company</a></li>
            <li class="list-group-item"><a href="/companies">All Companies</a></li>
        </ul>
    </div>

@endsection
