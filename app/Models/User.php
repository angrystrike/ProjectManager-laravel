<?php

namespace App\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Hootlex\Friendships\Models\Friendship;
use Hootlex\Friendships\Traits\Friendable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Friendable;
    use Notifiable;
    use Messagable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public static function findOneByEmail($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
    }

    public static function findThreadParticipants($thread_id, $search)
    {
        $threadParticipants = DB::table('users')
            ->select('users.id', 'users.email', 'users.name', 'participants.last_read', 'participants.created_at')
            ->join('participants', 'users.id', '=', 'participants.user_id')
            ->where('participants.thread_id', $thread_id)
            ->where('users.name', 'LIKE', "%{$search}%")
            ->orWhere('users.email', 'LIKE', "%{$search}%")
            ->get();

        return $threadParticipants;
    }

    public static function deleteFriend($friend_id)
    {
        Friendship::where('sender_id', Auth::id())
            ->where('recipient_id', $friend_id)
            ->where('status', 1)
            ->delete();

        Friendship::where('sender_id', $friend_id)
            ->where('recipient_id', Auth::id())
            ->where('status', 1)
            ->delete();
    }

    public static function cancelFriendRequest($recipient_id)
    {
        Friendship::where('sender_id', Auth::id())
            ->where('recipient_id', $recipient_id)
            ->where('status', 0)
            ->delete();
    }

    public static function findInComingFriendRequests($user_id)
    {
        $requests = DB::table('users')
            ->select('users.email', 'friendships.sender_id', 'friendships.status', 'friendships.created_at')
            ->join('friendships', 'users.id', 'friendships.sender_id')
            ->where('friendships.recipient_id', $user_id)
            ->where('friendships.status', 0)
            ->get();

        return $requests;
    }

    public static function findOutComingFriendRequests($user_id)
    {
        $requests = DB::table('users')
            ->select('users.email', 'friendships.recipient_id', 'friendships.status', 'friendships.created_at')
            ->join('friendships', 'users.id', 'friendships.recipient_id')
            ->where('friendships.sender_id', $user_id)
            ->where('friendships.status', 0)
            ->get();

        return $requests;
    }
}
