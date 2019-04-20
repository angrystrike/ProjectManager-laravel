@extends('layouts.app')
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="col-sm-9">
        <h3 class="text-center">Create new Message</h3>
        <form action="{{ route('messages.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label">Subject:</label>
                <input type="text" class="form-control form-control-lg" name="subject" placeholder="Subject"
                       value="{{ old('subject') }}">
            </div>

            <div class="form-group">
                <label class="control-label">Message:</label>
                <textarea name="message" class="form-control form-control-lg">{{ old('message') }}</textarea>
            </div>

            @if($users->count() > 0)
                {{--<div class="checkbox">
                    @foreach($users as $user)
                       --}}{{-- <label title="{{ $user->name }}">
                            <input type="checkbox" name="recipients[]" value="{{ $user->id }}">{!!$user->name!!}
                        </label>--}}{{--
                        <label class="checkbox-container" title="{{ $user->name }}">
                            <input type="checkbox" name="recipients[]" value="{{ $user->id }}">{!!$user->name!!}
                            <span class="checkmark"></span>
                        </label>
                    @endforeach
                </div>--}}
                <select id="multipleSelectExample" data-placeholder="Select an option" multiple>
                    @foreach ($users as $user)
                        <option name="recipients[]" value="{{ $user->id }}">{!!$user->name!!}</option>
                    @endforeach
                </select>
            @endif

            <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
        </form>
    </div>

    <div class="col-sm-3">
        <ul class="list-group">
            <h4>Actions: </h4>
            <li class="list-group-item"><a href="/messages">My Messages</a></li>
        </ul>
    </div>

@endsection
<script src="{{ URL::asset('js/main.js') }}"></script>
