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
        $employees = Employee::orderBy('first_name')->get();
        return view('branches.index', compact('branches', 'employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('branches.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'location'   => 'required|string|max:255',
            'manager_id' => 'nullable|exists:employees,id',
            'status'     => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $branch = Branch::create($validator->validated());
        $branch->load('manager');
        ActivityLog::log('Created Branch', "Branch: {$branch->name}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Branch created successfully.',
                'branch'  => [
                    'id'           => $branch->id,
                    'name'         => $branch->name,
                    'location'     => $branch->location,
                    'manager_name' => $branch->manager ? ($branch->manager->first_name . ' ' . $branch->manager->last_name) : 'N/A',
                    'status'       => $branch->status,
                    'edit_url'     => route('branches.edit', $branch->id),
                    'destroy_url'  => route('branches.destroy', $branch->id),
                ]
            ]);
        }

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
