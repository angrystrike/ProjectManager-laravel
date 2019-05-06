<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Hootlex\Friendships\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UsersController extends Controller
{
    public function all()
    {
        $users = User::where('role_id', '!=', 1)->get();
        return view('admin.users', ['users' => $users]);
    }

    public function show(User $user)
    {
        $companies = Company::where('user_id', $user->id)->get();
        $projects = Project::where('user_id', $user->id)->get();
        $tasks = Task::where('user_id', $user->id)->get();
        $comments = Comment::where('user_id', $user->id)->paginate(3);

        $projectUsers = ProjectUser::where('user_id', $user->id)->get();
        $jobProjects = [];
        if (count($projectUsers) > 0) {
            foreach ($projectUsers as $projectUser) {
                array_push($jobProjects, Project::where('id', $projectUser->project_id)->first());
            }
        }

        $taskUsers = TaskUser::where('user_id', $user->id)->get();
        $jobTasks = [];
        if (count($taskUsers) > 0) {
            foreach ($taskUsers as $taskUser) {
                array_push($jobTasks, Task::where('id', $taskUser->task_id)->first());
            }
        }

        $tmp1 = Friendship::where('sender_id', Auth::id())->where('recipient_id', $user->id)->first();
        $tmp2 = Friendship::where('sender_id', $user->id)->where('recipient_id', Auth::id())->first();

        if (empty($tmp1) && empty($tmp2)) {
            $relationshipState = 0; // users are not connected in any way
        }
        else if (!empty($tmp1) && $tmp1->status == 0) {
            $relationshipState = 1; // current logged user sent friend request
        }
        else if (!empty($tmp2) && $tmp2->status == 0) {
            $relationshipState = 2; // friend request was already sent to a current logged user
        }
        else if ((!empty($tmp1) && $tmp1->status == 1) || (!empty($tmp2) && $tmp2->status == 1)) {
            $relationshipState = 3; // users are friends
        }
        else {
            $relationshipState = 0;
        }

        return view('users.show', ['user' => $user, 'companies' => $companies,
                                        'projects' => $projects, 'tasks' => $tasks,
                                        'comments' => $comments, 'jobProjects' => $jobProjects,
                                        'jobTasks' => $jobTasks, 'state' => $relationshipState]);
    }

    public function destroy(User $user)
    {
        Comment::where('user_id', $user->id)->delete();
        ProjectUser::where('user_id', $user->id)->delete();
        TaskUser::where('user_id', $user->id)->delete();
        Task::where('user_id', $user->id)->delete();
        Project::where('user_id', $user->id)->delete();
        Company::where('user_id', $user->id)->delete();

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function deleteFriend(Request $request)
    {
        Friendship::where('sender_id', Auth::id())
            ->where('recipient_id', $request->input('friend_id'))
            ->where('status', 1)
            ->delete();

        Friendship::where('sender_id', $request->input('friend_id'))
            ->where('recipient_id', Auth::id())
            ->where('status', 1)
            ->delete();

        return response()->json(['message' => 'That user was deleted from your friend list']);
    }

    public function denyFriend(Request $request)
    {
        $sender = User::where('id', $request->input('sender_id'))->first();
        Auth::user()->denyFriendRequest($sender);

        return response()->json(['message' => 'Friend request was denied']);
    }

    public function cancelFriendRequest(Request $request)
    {
        Friendship::where('sender_id', Auth::id())
            ->where('recipient_id', $request->input('recipient_id'))
            ->where('status', 0)
            ->delete();

        return response()->json(['message' => 'Friend request was canceled']);
    }

    public function addToFriends(Request $request)
    {
        $sender = User::where('id', Auth::id())->first();
        $recipient = User::where('id', $request->input('recipient_id'))->first();
        $sender->befriend($recipient);

        return response()->json(['message' => 'Friend request was successfully sent']);
    }

    public function acceptFriend(Request $request)
    {
        $sender = User::where('id', $request->input('sender_id'))->first();
        $recipient = User::where('id', Auth::id())->first();

        $recipient->acceptFriendRequest($sender);
        Friendship::where('sender_id', $request->input('sender_id'))
            ->where('recipient_id', Auth::id())
            ->update([
                'status' => 1
            ]);

        return response()->json([
            'message' => $sender->email.' is successfully added to friends',
            'accepted_friend_id' => $request->input('sender_id'),
            'accepted_friend_email' => $sender->email
        ]);
    }

    public function friendListInfo()
    {
        $requests = DB::select(DB::raw("SELECT u.email, f.sender_id, f.status, f.created_at
                                        FROM users u JOIN friendships f ON u.id = f.sender_id
                                        WHERE f.recipient_id = ".Auth::id()." AND f.status = 0"));

        $sentRequests = DB::select(DB::raw("SELECT u.email, f.recipient_id, f.status, f.created_at
                                            FROM users u JOIN friendships f ON u.id = f.recipient_id
                                            WHERE f.sender_id = ".Auth::id()." AND f.status = 0"));

        $friends = Auth::user()->getFriends();

        return view('users.friends', ['requests' => $requests, 'friends' => $friends, 'sentRequests' => $sentRequests]);
    }
}
