<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function deleteMember($task_id, $user_id)
    {
       TaskUser::where('task_id', $task_id)->where('user_id', $user_id)->delete();
    }

    public function addUser(Request $request)
    {
        $task = Task::find($request->input('task_id'));
        if (Auth::user()->id == $task->user_id) {
            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return redirect()->route('tasks.show', ['task' => $task])
                    ->with('errors', $request->input('email').' doesnt exist');
            }

            $taskUser = TaskUser::where('user_id', $user->id)
                ->where('task_id', $task->id)
                ->first();

            if ($taskUser) {
                return redirect()->route('tasks.show', ['task' => $task])
                    ->with('errors', $request->input('email') . ' is already working on this Task');
            }

            $task->users()->attach($user->id);
            return redirect()->route('tasks.show', ['task' => $task])
                ->with('success', $request->input('email') . ' was added for this Task successfully');
        }
        return redirect()->route('tasks.show', ['task' => $task])
            ->with('errors', 'Invalid action');
    }

    public function all()
    {
        $tasks = Task::all();
        return view('admin.tasks', ['tasks' => $tasks]);
    }

    public function index()
    {
        $tasks = Task::where('user_id', Auth::user()->id)->get();
        return view('tasks.index', ['tasks'=> $tasks]);
    }


    public function create($project_id = null)
    {
        $projects = null;
        if (!$project_id) {
            $projects = Project::where('user_id', Auth::user()->id)->get();
        }

        return view('tasks.create', ['project_id' => $project_id, 'projects' => $projects]);
    }


    public function store(TasksRequest $request)
    {
        $request->validated();

        $task = Task::create([
            'name' => $request->input('name'),
            'days' => $request->input('days'),
            'hours' => $request->input('hours'),
            'duration' => $request->input('duration'),
            'project_id' => $request->input('project_id'),
            'user_id' => Auth::user()->id
        ]);

        if ($task) {
            return redirect()->route('tasks.show', ['task' => $task->id])
                ->with('success' , 'Task created successfully');
        }

        return back()->withInput()->with('errors', 'Error creating new Project');
    }


    public function show(Task $task)
    {
        $comments = Comment::where('commentable_type', 'App\Models\Task')
            ->where('commentable_id', $task->id)
            ->paginate(3);
        $creator = User::where('id', $task->user_id)->first();
        $project = Project::where('id', $task->project_id)->first();
        return view('tasks.show', ['task' => $task, 'comments' => $comments, 'creator' => $creator, 'project' => $project ]);
    }


    public function edit(Task $task)
    {
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(TasksRequest $request, Task $task)
    {
        $request->validated();
        $taskUpdate = Task::where('id', $task->id)
            ->update([
                'name'=> $request->input('name'),
                'days'=> $request->input('days'),
                'hours'=> $request->input('hours'),
                'duration'=> $request->input('duration')
            ]);

        if ($taskUpdate) {
            return redirect()->route('tasks.show', ['task' => $task->id])
                ->with('success' , 'Task updated successfully');
        }
        return back()->withInput();
    }

    public function destroy(Task $task)
    {
        TaskUser::where('task_id', $task->id)->delete();

        if ($task && $task->delete()) {

            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.tasks')
                    ->with('success' , 'Task deleted successfully');
            }

            return redirect()->route('tasks.index')
                ->with('success' , 'Task deleted successfully');
        }

        return back()->withInput()->with('errors' , 'Task could not be deleted');
    }
}
