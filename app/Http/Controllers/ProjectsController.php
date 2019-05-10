<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectsRequest;
use App\Mail\UserAttachedToProject;
use App\Mail\UserRemovedFromProject;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProjectsController extends Controller
{
    public function deleteMember($project_id, $user_id)
    {
        ProjectUser::findOneByUserIdAndProjectId($user_id, $project_id)->delete();

        $user = User::find($user_id);
        $project = Project::find($project_id);
        Mail::to($user)->send(new UserRemovedFromProject($project));

        return response()->json([
            'message' => 'Project member was removed'
        ]);
    }

    public function addUser(Request $request)
    {
        $project = Project::find($request->input('project_id'));
        if (Auth::id() == $project->user_id) {

            $user = User::findOneByEmail($request->input('email'));

            if (!$user) {
                return redirect()->route('projects.show', ['project' => $project])
                    ->with('errors', $request->input('email').' doesnt exist');
            }

            $projectUser = ProjectUser::findOneByUserIdAndProjectId($user->id, $project->id);

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
            $projects = Project::findByUserId(Auth::id());
        }
        return view('projects.index', ['projects' => $projects]);
    }

    public function create($company_id = null)
    {
        $companies = null;
        if (!$company_id) {
            $companies = Company::findByUserId(Auth::id());
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
            return redirect()->route('projects.show', ['project' => $project])
                ->with('success' , 'Project created successfully');
        }

        return back()->withInput()->with('errors', 'Error creating new Project');
    }

    public function show(Project $project)
    {
        $comments = Comment::findByIdAndType('App\Models\Project', $project->id, 4);
        $creator = User::find($project->user_id);
        $company = Company::find($project->company_id);

        return view('projects.show', ['project' => $project, 'comments' => $comments, 'creator' => $creator, 'company' => $company]);
    }

    public function edit(Project $project)
    {
        return view('projects.edit', ['project' => $project]);
    }

    public function update(ProjectsRequest $request, Project $project)
    {
        $request->validated();

        $project->update([
            'name'=> $request->input('name'),
            'description'=> $request->input('description')
        ]);

        return redirect()->route('projects.show', ['project'=> $project])
            ->with('success' , 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        Comment::deleteByTypeAndId('App\Models\Project', $project->id);

        if (Auth::user()->role_id == 1) {
            return redirect()->route('admin.projects')->with('success' , 'Project deleted successfully');
        }
        return redirect()->route('projects.index')->with('success' , 'Project deleted successfully');
    }
}
