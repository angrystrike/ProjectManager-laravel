<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body',
        'url',
        'commentable_id',
        'commentable_type',
        'user_id',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function findByIdAndType($type, $id, $itemsPerPage)
    {
        $comments = Comment::where('commentable_type', $type)
            ->where('commentable_id', $id)
            ->paginate($itemsPerPage);

        return $comments;
    }

    public static function createOne($body, $url, $type, $id, $user_id)
    {
        $comment = Comment::create([
            'body' => $body,
            'url' => $url,
            'commentable_type' => $type,
            'commentable_id' => $id,
            'user_id' => $user_id
        ]);

        return $comment;
    }

    public static function findByUserIdAndPaginate($user_id, $itemsPerPage)
    {
        $comments = Comment::where('user_id', $user_id)->paginate($itemsPerPage);
        return $comments;
    }

    public static function deleteByTypeAndId($type, $id)
    {
        Comment::where('commentable_type', $type)
            ->where('commentable_id', $id)
            ->delete();
    }
}
