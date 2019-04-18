<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function all()
    {
        $comments = Comment::paginate(4);
        return view('partials.comments', ['comments' => $comments]);
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
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
        }

        return back()->withInput()->with('errors', 'Error creating new comment');
    }

    public function destroy(Comment $comment)
    {
        $isDeleted = Comment::where('id', $comment->id)->delete();
        return response()->json([
            'success' => $isDeleted
        ]);
    }
}
