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

        return view('users.show', ['user' => $user, 'companies' => $companies,
                                        'projects' => $projects, 'tasks' => $tasks,
                                        'comments' => $comments, 'jobProjects' => $jobProjects,
                                        'jobTasks' => $jobTasks]);
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
    }

    public function addToFriends(Request $request)
    {
        if (Auth::id() != $request->input('sender_id')) {
            return response()->json(['message' => 'Friend request was not sent']);
        }
        $sender = User::where('id', $request->input('sender_id'))->first();
        $recipient = User::where('id', $request->input('recipient_id'))->first();
        $sender->befriend($recipient);

        return response()->json(['message' => 'Friend request was successfully sent']);
    }

    public function acceptFriend(Request $request)
    {
        if (Auth::id() != $request->input('recipient_id')) {
            return response()->json(['message' => 'Friend request was not accepted']);
        }
        $sender = User::where('id', $request->input('sender_id'))->first();
        $recipient = User::where('id', $request->input('recipient_id'))->first();

        $recipient->acceptFriendRequest($sender);
        Friendship::where('sender_id', $request->input('sender_id'))
            ->where('recipient_id', $request->input('recipient_id'))
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

        $friends = Auth::user()->getFriends();

        return view('users.friends', ['requests' => $requests, 'friends' => $friends]);
    }
}
