@extends('layouts.app')

@section('content')
    <div class="col-md-6 col-lg-6 center">
        <div class="card">
            <div class="card-header text-center text-white bg-primary"> Companies <a class="btn btn-primary" href="/companies/create">Create new</a></div>
            <div class="card-body">
                <ul class="list-group">
                    @if (!is_null($companies) && count($companies) > 0)
                        @foreach($companies as $company)
                            <li class="list-group-item">
                                <a href="/companies/{{ $company->id }}"> {{ $company->name }} </a>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item">
                            <p class="text-center">No Companies yet</p>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
