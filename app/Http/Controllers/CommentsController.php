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
        $validatedData = $request->validated();

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

    public function update()
    {
        if (strlen(request('body')) >= 1000 || strlen(request('url')) >= 255 || strlen(request('body')) == 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Incorrect input, changes were not done'
            ]);
        }


        Comment::where('id', request('id'))
            ->update([
                'body' => request('body'),
                'url' => request('url')
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Comment was successfully updated!'
        ]);

    }
    public function destroy(Comment $comment)
    {
        $isDeleted = Comment::where('id', $comment->id)->delete();
        return response()->json([
            'success' => $isDeleted
        ]);
    }
}
