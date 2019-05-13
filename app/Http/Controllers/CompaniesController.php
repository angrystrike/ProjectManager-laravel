<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Comment;
use App\Models\Company;;
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
        $companies = null;
        if (Auth::check()) {
            $companies = Company::findByUserId(Auth::id());
        }
        return view('companies.index', ['companies' => $companies]);
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(CompanyRequest $request)
    {
        $request->validated();
        $company = Company::createOne($request->input('name'), $request->input('description'), Auth::id());

        return redirect()->route('companies.show', ['company' => $company])
            ->with('success', 'Company created successfully');
    }

    public function show(Company $company)
    {
        $itemsPerPage = 4;
        $comments = Comment::findByIdAndType('App\Models\Company', $company->id, $itemsPerPage);
        $creator = User::find($company->user_id);

        return view('companies.show', ['company' => $company, 'comments' => $comments, 'creator' => $creator]);
    }

    public function edit(Company $company)
    {
        return view('companies.edit', ['company' => $company]);
    }

    public function update(CompanyRequest $request, Company $company)
    {
        $request->validated();

        $company->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);

        return redirect()->route('companies.show', ['company' => $company])
            ->with('success', 'Company updated successfully');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        Comment::deleteByTypeAndId('App\Models\Company', $company->id);

        if (Auth::user()->role_id == 1) {
            return redirect()->route('admin.companies')->with('success' , 'Company deleted successfully');
        }
        return redirect()->route('companies.index')->with('success' , 'Company deleted successfully');
    }
}
