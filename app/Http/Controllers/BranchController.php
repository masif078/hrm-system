<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('manager')->latest()->get();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('branches.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'manager_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:Active,Inactive',
        ]);

        $branch = Branch::create($validated);
        ActivityLog::log('Created Branch', "Branch: {$branch->name}");

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        $employees = Employee::all();
        return view('branches.edit', compact('branch', 'employees'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'manager_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:Active,Inactive',
        ]);

        $branch->update($validated);
        ActivityLog::log('Updated Branch', "Branch: {$branch->name}");

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        ActivityLog::log('Deleted Branch', "Branch: {$branch->name}");
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
