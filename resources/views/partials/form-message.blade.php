<h4 class="mr-top-25">Add a new message</h4>
<form action="{{ route('messages.update', $thread->id) }}" method="post">
    @method('put')
    @csrf
    <div class="form-group">
        <textarea name="message" class="form-control" placeholder="Type new message" required>{{ old('message') }}</textarea>
    </div>
    <br>

    <h4 class="text-center">Invite new participants</h4>
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
        <button type="submit" class="btn btn-primary form-control">Submit</button>
    </div>
</form>
