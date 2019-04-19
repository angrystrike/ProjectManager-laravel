<?php


namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;


class SearchController
{
    public function search(Request $request)
    {
        $search = $request->input('search');

        $companies = Company::where('name', 'like', '%' . $search . '%')->get();
        $projects = Project::where('name', 'like', '%' . $search . '%')->get();
        $tasks = Task::where('name', 'like', '%' . $search . '%')->get();
        $users = User::where('name', 'like', '%' . $search . '%')->get();

        $totalCount = count($companies) + count($projects) + count($tasks) + count($users);
        $rowsAmount = max(count($users), count($companies), count($projects), count($tasks));

        return view('search', ['search' => $search, 'users' => $users,
            'companies' => $companies, 'projects' => $projects,
            'tasks' => $tasks, 'totalCount' => $totalCount,
            'rowsAmount' => $rowsAmount]);

    }
}
