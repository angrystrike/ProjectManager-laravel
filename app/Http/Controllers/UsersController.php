<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;


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
        return session()->flash('success', 'User was deleted successfully!');
    }
}
