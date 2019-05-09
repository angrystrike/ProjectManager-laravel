<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectsRequest;
use App\Mail\UserAttachedToProject;
use App\Mail\UserRemovedFromProject;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProjectsController extends Controller
{
    public function deleteMember($project_id, $user_id)
    {
        ProjectUser::where('project_id', $project_id)
            ->where('user_id', $user_id)
            ->delete();

        $user = User::where('id', $user_id)->first();
        $project = Project::where('id', $project_id)->first();
        Mail::to($user)->send(new UserRemovedFromProject($project));

        return response()->json([
            'message' => 'Project member was removed'
        ]);
    }

    public function addUser(Request $request)
    {
        $project = Project::find($request->input('project_id'));
        if (Auth::user()->id == $project->user_id) {
            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return redirect()->route('projects.show', ['project' => $project])
                    ->with('errors', $request->input('email').' doesnt exist');
            }

            $projectUser = ProjectUser::where('user_id', $user->id)
                ->where('project_id', $project->id)
                ->first();

            if ($projectUser) {
                return redirect()->route('projects.show', ['project' => $project])
                    ->with('errors', $request->input('email') . ' is already a member of this Project');
            }

            $project->users()->attach($user->id);
            Mail::to($user)->send(new UserAttachedToProject($project));

            return redirect()->route('projects.show', ['project' => $project])
                ->with('success', $request->input('email') . ' was added to Project successfully and was notified via email');
        }
        return redirect()->route('projects.show', ['project' => $project])
            ->with('errors', 'Invalid action');
    }

    public function all()
    {
        $projects = Project::all();
        return view('admin.projects', ['projects' => $projects]);
    }

    public function index()
    {
        $projects = null;
        if (Auth::check()) {
            $projects = Project::where('user_id', Auth::user()->id)->get();
        }
        return view('projects.index', ['projects' => $projects]);
    }

    public function create($company_id = null)
    {
        $companies = null;
        if (!$company_id) {
            $companies = Company::where('user_id', Auth::user()->id)->get();
        }

        return view('projects.create', ['company_id' => $company_id, 'companies' => $companies]);
    }

    public function store(ProjectsRequest $request)
    {
        $request->validated();

        $project = Project::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'company_id' => $request->input('company_id'),
            'user_id' => Auth::user()->id
        ]);

        if ($project) {
            return redirect()->route('projects.show', ['project'=> $project->id])
                ->with('success' , 'Project created successfully');
        }

        return back()->withInput()->with('errors', 'Error creating new Project');
    }

    public function show(Project $project)
    {
        $comments = Comment::where('commentable_type', 'App\Models\Project')
            ->where('commentable_id', $project->id)
            ->paginate(3);
        $creator = User::where('id', $project->user_id)->first();
        $company = Company::where('id', $project->company_id)->first();

        return view('projects.show', ['project' => $project, 'comments' => $comments, 'creator' => $creator, 'company' => $company ]);
    }

    public function edit(Project $project)
    {
        return view('projects.edit', ['project' => $project]);
    }

    public function update(ProjectsRequest $request, project $project)
    {
       $request->validated();

        $projectUpdate = Project::where('id', $project->id)
            ->update([
                'name'=> $request->input('name'),
                'description'=> $request->input('description')
            ]);

        if ($projectUpdate) {
            return redirect()->route('projects.show', ['project'=> $project->id])
                ->with('success' , 'Project updated successfully');
        }
        return back()->withInput();
    }

    public function destroy(Project $project)
    {
        ProjectUser::where('project_id', $project->id)->delete();
        $tasks = Task::where('project_id', $project->id)->get();
        foreach ($tasks as $task) {
            TaskUser::where('task_id', $task->id)->delete();
        }
        Task::where('project_id', $project->id)->delete();
        $project->delete();

        if (Auth::user()->role_id == 1) {
            return redirect()->route('admin.projects')
                ->with('success' , 'Project deleted successfully');
        }
        return redirect()->route('projects.index')
            ->with('success' , 'Project deleted successfully');
    }
}
