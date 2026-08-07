<?php

namespace App\Http\Controllers;

use App\Models\CompanyPolicy;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CompanyPolicyController extends Controller
{
    public function index()
    {
        $policies = CompanyPolicy::latest()->get();
        return view('company-policies.index', compact('policies'));
    }

    public function create()
    {
        return view('company-policies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $policy = CompanyPolicy::create($validated);
        ActivityLog::log('Created Company Policy', "Policy: {$policy->title}");

        return redirect()->route('company-policies.index')->with('success', 'Company policy created successfully.');
    }

    public function edit(CompanyPolicy $companyPolicy)
    {
        return view('company-policies.edit', compact('companyPolicy'));
    }

    public function update(Request $request, CompanyPolicy $companyPolicy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $companyPolicy->update($validated);
        ActivityLog::log('Updated Company Policy', "Policy: {$companyPolicy->title}");

        return redirect()->route('company-policies.index')->with('success', 'Company policy updated successfully.');
    }

    public function destroy(CompanyPolicy $companyPolicy)
    {
        ActivityLog::log('Deleted Company Policy', "Policy: {$companyPolicy->title}");
        $companyPolicy->delete();
        return redirect()->route('company-policies.index')->with('success', 'Company policy deleted successfully.');
    }
}
