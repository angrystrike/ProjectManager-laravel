<?php


namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SearchController
{
    public function search(Request $request)
    {
        $search = $request->input('search');

        $companies = Company::where('name', 'LIKE', "%{$search}%")->get();
        $projects = Project::where('name', 'LIKE', "%{$search}%")->get();
        $tasks = Task::where('name', 'LIKE', "%{$search}%")->get();
        $users = User::where('name', 'LIKE', "%{$search}%")->get();

        $totalCount = count($companies) + count($projects) + count($tasks) + count($users);
        $rowsAmount = max(count($users), count($companies), count($projects), count($tasks));

        return view('search', ['search' => $search, 'users' => $users,
                                    'companies' => $companies, 'projects' => $projects,
                                    'tasks' => $tasks, 'totalCount' => $totalCount,
                                    'rowsAmount' => $rowsAmount]);

    }

    public function findParticipants(Request $request, $thread_id)
    {
        $search = $request->input('search');

        $result = DB::table('users')
            ->select('users.id', 'users.email', 'users.name', 'participants.last_read', 'participants.created_at')
            ->join('participants', 'users.id', '=', 'participants.user_id')
            ->where('participants.thread_id', '=', $thread_id)
            ->where('users.name', 'LIKE', "%{$search}%")
            ->orWhere('users.email', 'LIKE', "%{$search}%")
            ->get();
        $result = $result->unique('email')->where('id', '!=', Auth::id());

        $thread = Thread::where('id', $thread_id)->first();
        if ($thread->creator()->id == Auth::id()) {
            $isCreator = true;
        }
        else {
            $isCreator = false;
        }

        return view('messages.participants', ['result' => $result, 'matchesCount' => count($result), 'search' => $search, 'isCreator' => $isCreator]);
    }
}
