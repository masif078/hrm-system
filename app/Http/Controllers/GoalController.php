<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Employee;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            $query = Goal::with('employee');
            
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $goals = $query->latest()->paginate(10);
            $employees = Employee::orderBy('first_name')->get();
            
            return view('goals.index', compact('goals', 'employees'));
        } else {
            $employee = $user->employee;
            if (!$employee) {
                return redirect()->back()->with('error', 'Employee profile not found.');
            }
            
            $goals = Goal::where('employee_id', $employee->id)->latest()->paginate(10);
            return view('goals.index', compact('goals'));
        }
    }

    public function create()
    {
        $user = auth()->user();
        $employees = $user->role === 'admin' ? Employee::all() : collect([$user->employee]);
        return view('goals.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_date' => 'required|date',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required|string',
        ];

        if ($user->role === 'admin') {
            $rules['employee_id'] = 'required|exists:employees,id';
        }

        $validated = $request->validate($rules);

        if ($user->role !== 'admin') {
            $validated['employee_id'] = $user->employee->id;
        }

        // Auto status mapping based on progress
        if ($validated['progress'] == 100) {
            $validated['status'] = 'Completed';
        }

        Goal::create($validated);

        return redirect()->route('goals.index')->with('success', 'Goal created successfully.');
    }

    public function edit(Goal $goal)
    {
        $user = auth()->user();
        // check permission
        if ($user->role !== 'admin' && $goal->employee_id !== $user->employee->id) {
            abort(403);
        }

        $employees = $user->role === 'admin' ? Employee::all() : collect([$user->employee]);
        return view('goals.edit', compact('goal', 'employees'));
    }

    public function update(Request $request, Goal $goal)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $goal->employee_id !== $user->employee->id) {
            abort(403);
        }

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_date' => 'required|date',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required|string',
        ];

        if ($user->role === 'admin') {
            $rules['employee_id'] = 'required|exists:employees,id';
        }

        $validated = $request->validate($rules);

        if ($validated['progress'] == 100) {
            $validated['status'] = 'Completed';
        }

        $goal->update($validated);

        return redirect()->route('goals.index')->with('success', 'Goal updated successfully.');
    }

    public function destroy(Goal $goal)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Goal deleted successfully.');
    }
}
