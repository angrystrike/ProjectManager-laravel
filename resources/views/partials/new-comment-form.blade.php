<form method="post" action="{{ route('comments.store') }}">
    @csrf

    <input type="hidden" name="commentable_type" value={{ $type }}>
    <input type="hidden" name="commentable_id" value="{{ $id }}">

    <div class="form-group">
        <label for="comment-content" class="mr-top-25">Comment:</label>
        <textarea placeholder="Enter comment"
                  id="comment-content"
                  required
                  name="body"
                  rows="3"
                  class="form-control form-control-lg"></textarea>
    </div>

    <div class="form-group">
        <label for="comment-content">Proof of work done:</label>
        <textarea placeholder="Enter url or screenshots"
                  id="comment-content"
                  name="url"
                  rows="2"
                  class="form-control form-control-lg"></textarea>
    </div>

    <div class="form-group text-center">
        <input type="submit" class="btn btn-primary btn-block" value="Submit"/>
    </div>
</form>
