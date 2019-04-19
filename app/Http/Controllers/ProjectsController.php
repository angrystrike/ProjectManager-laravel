<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function deleteMember($project_id, $user_id)
    {
        $isDeleted = ProjectUser::where('project_id', '=', $project_id)->where('user_id', '=', $user_id)->delete();
        return response()->json([
            'success' => $isDeleted
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
                ->with('errors', $request->input('email') . ' is already a member of this project');
            }

            $project->users()->attach($user->id);
            return redirect()->route('projects.show', ['project' => $project])
                ->with('success', $request->input('email') . ' was added to Project successfully');
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
        if (Auth::check()) {
            $projects = Project::where('user_id', Auth::user()->id)->get();

            return view('projects.index', ['projects'=> $projects]);
        }
        return view('auth.login');
    }

    public function create($company_id = null)
    {
        $companies = null;
        if (!$company_id) {
            $companies = Company::where('user_id', Auth::user()->id)->get();
        }

        return view('projects.create', ['company_id' => $company_id, 'companies' => $companies]);
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
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

    public function update(Request $request, project $project)
    {
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
        ProjectUser::where('project_id', '=', $project->id)->delete();
        $task =  Task::where('project_id', '=', $project->id)->first();
        if ($task) {
            TaskUser::where('task_id', '=', $task->id)->delete();
        }
        Task::where('project_id', '=', $project->id)->delete();

        if ($project && $project->delete()) {

            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.projects')
                    ->with('success' , 'Project deleted successfully');
            }

            return redirect()->route('projects.index')
                ->with('success' , 'Project deleted successfully');
        }

        return back()->withInput()->with('errors' , 'Project could not be deleted');
    }
}
