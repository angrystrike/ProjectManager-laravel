<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Mail\UserAttachedToTask;
use App\Mail\UserRemovedFromTask;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TasksController extends Controller
{
    public function deleteMember($task_id, $user_id)
    {
        TaskUser::findOneByUserIdAndTaskId($user_id, $task_id)->delete();
        $user = User::find($user_id);
        $task = Task::find($task_id);
        Mail::to($user)->send(new UserRemovedFromTask($task));

        return response()->json(['message' => 'Task member was removed and was notified via email']);
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

            $taskUser = TaskUser::findOneByUserIdAndTaskId($user->id, $task->id);

            if ($taskUser) {
                return redirect()->route('tasks.show', ['task' => $task])
                    ->with('errors', $request->input('email') . ' is already working on this Task');
            }

            $task->users()->attach($user->id);
            Mail::to($user)->send(new UserAttachedToTask($task));

            return redirect()->route('tasks.show', ['task' => $task])
                ->with('success', $request->input('email') . ' was added for this Task successfully and notified via email');
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
        $tasks = null;
        if (Auth::check()) {
            $tasks = Task::findByUserId(Auth::id());
        }
        return view('tasks.index', ['tasks' => $tasks]);
    }


    public function create($project_id = null)
    {
        $projects = null;
        if (!$project_id) {
            $projects = Project::findByUserId(Auth::id());
        }

        return view('tasks.create', ['project_id' => $project_id, 'projects' => $projects]);
    }


    public function store(TasksRequest $request)
    {
        $request->validated();

        $task = Task::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'kind' => $request->input('kind'),
            'priority' => $request->input('priority'),
            'days' => $request->input('days'),
            'project_id' => $request->input('project_id'),
            'user_id' => Auth::id()
        ]);

        if ($task) {
            return redirect()->route('tasks.show', ['task' => $task->id])
                ->with('success' , 'Task created successfully');
        }

        return back()->withInput()->with('errors', 'Error creating new Task');
    }


    public function show(Task $task)
    {
        $itemsPerPage = 3;
        $comments = Comment::findByIdAndType('App\Models\Task',  $task->id, $itemsPerPage);
        $creator = User::find($task->user_id);
        $project = Project::find($task->project_id);

        return view('tasks.show', ['task' => $task, 'comments' => $comments, 'creator' => $creator, 'project' => $project]);
    }


    public function edit(Task $task)
    {
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(TasksRequest $request, Task $task)
    {
        $request->validated();

        $taskUpdate = $task->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'kind' => $request->input('kind'),
            'priority' => $request->input('priority'),
            'days' => $request->input('days'),
        ]);

        if ($taskUpdate) {
            return redirect()->route('tasks.show', ['task' => $task->id])
                ->with('success' , 'Task updated successfully');
        }

        return back()->withInput();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        Comment::deleteByTypeAndId('App\Models\Task', $task->id);

        if (Auth::user()->role_id == 1) {
            return redirect()->route('admin.tasks')->with('success' , 'Task deleted successfully');
        }

        return redirect()->route('tasks.index')->with('success' , 'Task deleted successfully');
    }
}
