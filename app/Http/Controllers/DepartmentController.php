<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::withCount('employees');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $departments = $query->latest()->get();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:departments,name|regex:/^[a-zA-Z0-9\s]+$/',
            'code' => 'required|max:50|unique:departments,code|regex:/^[a-zA-Z0-9\s]+$/',
            'description' => 'nullable',
        ], [
            'name.regex' => 'The name may only contain letters, numbers, and spaces.',
            'code.regex' => 'The code may only contain letters, numbers, and spaces.',
        ]);

        Department::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department added successfully.');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|max:100|unique:departments,name,' . $department->id . '|regex:/^[a-zA-Z0-9\s]+$/',
            'code' => 'required|max:50|unique:departments,code,' . $department->id . '|regex:/^[a-zA-Z0-9\s]+$/',
            'description' => 'nullable',
        ], [
            'name.regex' => 'The name may only contain letters, numbers, and spaces.',
            'code.regex' => 'The code may only contain letters, numbers, and spaces.',
        ]);

        $department->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->exists() || $department->designations()->exists()) {
            return redirect()
                ->route('departments.index')
                ->with('error', 'Cannot delete department that has employees or designations linked.');
        }

        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    public function show(Department $department) {}
}
