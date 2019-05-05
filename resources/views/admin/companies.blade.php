@extends('layouts.app')

@section('content')
    <div class="col-md-6 col-lg-6 center">
        <div class="card">
            <div class="card-header text-center text-white bg-primary"> Companies <a class="btn btn-primary" href="/companies/create">Create new</a></div>
            <div class="card-body">
                <ul class="list-group">
                    @if (count($companies) > 0)
                        @foreach($companies as $company)
                            <li class="list-group-item">
                                <a href="/companies/{{ $company->id }}">{{ $company->name }}</a>
                                <button type="button" class="btn btn-danger btn-sm float-right mr-left-8 js-delete">Delete</button>
                                <button type="button" class="btn btn-dark btn-sm float-right "><a href="/companies/{{ $company->id }}/edit" class="text-white">Edit</a></button>

                                <form id="delete-form" action="{{ route('companies.destroy', [$company->id]) }}"
                                      method="POST" class="hidden">
                                      @method('delete')
                                    @csrf
                                </form>
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

