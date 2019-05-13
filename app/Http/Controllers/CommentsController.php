<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentsRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function all()
    {
        $itemsPerPage = 4;
        $comments = Comment::paginate($itemsPerPage);
        return view('admin.comments', ['comments' => $comments]);
    }

    public function store(CommentsRequest $request)
    {
        $request->validated();

        $body = $request->input('body');
        $url = $request->input('url');
        $commentable_type = $request->input('commentable_type');
        $commentable_id = $request->input('commentable_id');
        Comment::createOne($body, $url, $commentable_type, $commentable_id, Auth::id());

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function update(CommentsRequest $request)
    {
        $request->validated();
        Comment::find($request->input('id'))
            ->update([
                'body' => $request->input('body'),
                'url' => $request->input('url')
            ]);

        return response()->json(['message' => 'Comment was successfully updated!']);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Comment was successfully deleted']);
    }
}
