<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
    public function all()
    {
        $companies = Company::all();
        return view('admin.companies', ['companies' => $companies]);
    }

    public function index()
    {
        $companies = Company::where('user_id', Auth::user()->id)->get();
        return view('companies.index', ['companies' => $companies]);
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(CompanyRequest $request)
    {
        $request->validated();

        $company = Company::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'user_id' => Auth::user()->id
        ]);

        if ($company) {
            return redirect()->route('companies.show', ['company' => $company->id])
                ->with('success', 'Company created successfully');
        }


        return back()->withInput()->with('errors', 'Error creating new Company');
    }

    public function show(Company $company)
    {
        $comments = Comment::where('commentable_type', 'App\Models\Company')
            ->where('commentable_id', $company->id)
            ->paginate(4);

        $creator = User::where('id', $company->user_id)->first();
        return view('companies.show', ['company' => $company, 'comments' => $comments, 'creator' => $creator]);
    }

    public function edit(Company $company)
    {
        return view('companies.edit', ['company' => $company]);
    }


    public function update(CompanyRequest $request, Company $company)
    {
        $request->validated();

        $companyUpdate = Company::where('id', $company->id)
            ->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);

        if ($companyUpdate) {
            return redirect()->route('companies.show', ['company' => $company->id])
                ->with('success', 'Company updated successfully');
        }

        return back()->withInput();
    }

    public function destroy(Company $company)
    {
        Project::where('company_id', '=', $company->id)
            ->update([
                'company_id' => null
            ]);

        Task::where('company_id', '=', $company->id)
            ->update([
                'company_id' => null
            ]);

        $findCompany = Company::find($company->id);

        if ($findCompany->delete()) {
            return redirect()->route('companies.index')
                ->with('success' , 'Company deleted successfully');
        }

        return back()->withInput()->with('error', 'Company could not be deleted');
    }
}
