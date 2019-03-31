<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function adduser(Request $request)
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
        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function index()
    {
        if (Auth::check()) {
            $tasks = Task::where('user_id', Auth::user()->id)->get();

            return view('tasks.index', ['tasks'=> $tasks]);
        }
        return view('auth.login');
    }


    public function create($project_id = null)
    {
        $projects = null;
        if (!$project_id) {
            $projects = Project::where('user_id', Auth::user()->id)->get();
        }

        return view('tasks.create', ['project_id' => $project_id, 'projects' => $projects]);
    }


    public function store(Request $request)
    {
        if (Auth::check()) {
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

        }

        return back()->withInput()->with('errors', 'Error creating new Project');
    }


    public function show(Task $task)
    {
        $task = Task::find($task->id);
        $comments = $task->comments;
        return view('tasks.show', ['task' => $task, 'comments' => $comments ]);
    }


    public function edit(Task $task)
    {
        $task = Project::find($task->id);
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
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
        $findTask = Task::find($task->id);
        if ($findTask && $findTask->delete()) {

            return redirect()->route('tasks.index')
                ->with('success' , 'Task deleted successfully');
        }

        return back()->withInput()->with('errors' , 'Task could not be deleted');
    }
}
