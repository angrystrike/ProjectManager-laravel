@extends('layouts.app')
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
    <div class="col-sm-9">
        <h3 class="text-center">Create new Message</h3>
        <form action="{{ route('messages.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label">Subject:</label>
                <input type="text" class="form-control form-control-lg" name="subject" placeholder="Subject"
                       value="{{ old('subject') }}" required>
            </div>

            <div class="form-group">
                <label class="control-label">Message:</label>
                <textarea name="message" class="form-control form-control-lg" required>{{ old('message') }}</textarea>
            </div>

            @if($users->count() > 0)
                <div class="card margin-bot">
                    <div class="card-header">
                        <strong>Chat participants:</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="multipleSelect">Choose receivers: </label>
                            <select style="width: 100%" name="recipients[]" class="form-control form-control-lg" id="multipleSelect" data-placeholder="Select an option" multiple>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{!!$user->name!!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <div class="form-group">
                <button type="submit"  class="btn btn-primary form-control">Submit</button>
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
