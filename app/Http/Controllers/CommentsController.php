<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentsRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function all()
    {
        $comments = Comment::paginate(4);
        return view('admin.comments', ['comments' => $comments]);
    }

    public function store(CommentsRequest $request)
    {
        $request->validated();

        $comment = Comment::create([
            'body' => $request->input('body'),
            'url' => $request->input('url'),
            'commentable_type' => $request->input('commentable_type'),
            'commentable_id' => $request->input('commentable_id'),
            'user_id' => Auth::user()->id
        ]);

        if ($comment) {
            return redirect()->back()->with('success', 'Comment added successfully');
        }

        return back()->withInput()->with('errors', 'Error creating new comment');
    }

    public function update(CommentsRequest $request)
    {
        $request->validated();
        Comment::where('id', $request->input('id'))
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
