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
        $companies = Company::findByUserId($user->id);
        $projects = Project::findByUserId($user->id);
        $tasks = Task::findByUserId($user->id);
        $comments = Comment::findByUserIdAndPaginate($user->id, 3);
        $jobProjects = ProjectUser::userJobProjects($user->id);
        $jobTasks = TaskUser::userJobTasks($user->id);

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
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function deleteFriend(Request $request)
    {
        User::deleteFriend($request->input('friend_id'));
        return response()->json(['message' => 'That user was deleted from your friend list']);
    }

    public function denyFriend(Request $request)
    {
        $sender = User::find($request->input('sender_id'));
        Auth::user()->denyFriendRequest($sender);

        return response()->json(['message' => 'Friend request was denied']);
    }

    public function cancelFriendRequest(Request $request)
    {
        User::cancelFriendRequest($request->input('recipient_id'));
        return response()->json(['message' => 'Friend request was canceled']);
    }

    public function addToFriends(Request $request)
    {
        $recipient = User::find($request->input('recipient_id'));
        Auth::user()->befriend($recipient);

        return response()->json(['message' => 'Friend request was successfully sent']);
    }

    public function acceptFriend(Request $request)
    {
        $sender = User::find($request->input('sender_id'));
        Auth::user()->acceptFriendRequest($sender);

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
        $inComingRequests = User::findInComingFriendRequests(Auth::id());
        $outComingRequest = User::findOutComingFriendRequests(Auth::id());
        $friends = Auth::user()->getFriends();

        return view('users.friends', ['requests' => $inComingRequests, 'friends' => $friends, 'sentRequests' => $outComingRequest]);
    }
}
