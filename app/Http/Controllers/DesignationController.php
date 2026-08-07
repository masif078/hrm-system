<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $query = Designation::with('department');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $designations = $query->latest()->get();

        return view('designations.index', compact('designations'));
    }

    public function create()
    {
        $departments = Department::all();

        return view('designations.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|max:100|unique:designations,title|regex:/^[a-zA-Z0-9\s]+$/',
            'description' => 'nullable',
        ], [
            'title.regex' => 'The designation title may only contain letters, numbers, and spaces.',
        ]);

        Designation::create([
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation added successfully.');
    }

    public function edit(Designation $designation)
    {
        $departments = Department::all();

        return view('designations.edit', compact('designation', 'departments'));
    }

    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|max:100|unique:designations,title,' . $designation->id . '|regex:/^[a-zA-Z0-9\s]+$/',
            'description' => 'nullable',
        ], [
            'title.regex' => 'The designation title may only contain letters, numbers, and spaces.',
        ]);

        $designation->update([
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        if ($designation->employees()->exists()) {
            return redirect()
                ->route('designations.index')
                ->with('error', 'Cannot delete designation that has employees linked.');
        }

        $designation->delete();

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation deleted successfully.');
    }

    public function show(Designation $designation) {}
}
