<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveBalance;
use App\Models\Employee;

class LeaveBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $balances = LeaveBalance::with('employee')->latest()->paginate(15);
        return view('leave-balances.index', compact('balances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();
        return view('leave-balances.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string|max:255',
            'allocated' => 'required|integer|min:0',
        ]);

        // Check if balance for this employee and type already exists
        $exists = LeaveBalance::where('employee_id', $validated['employee_id'])
            ->where('leave_type', $validated['leave_type'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['leave_type' => 'Leave balance of this type has already been allocated to this employee.']);
        }

        LeaveBalance::create($validated);

        return redirect()->route('leave-balances.index')
            ->with('success', 'Leave balance allocated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $balance = LeaveBalance::findOrFail($id);
        $employees = Employee::all();
        return view('leave-balances.edit', compact('balance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $balance = LeaveBalance::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string|max:255',
            'allocated' => 'required|integer|min:0',
            'used' => 'required|integer|min:0',
        ]);

        $balance->update($validated);

        return redirect()->route('leave-balances.index')
            ->with('success', 'Leave balance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $balance = LeaveBalance::findOrFail($id);
        $balance->delete();

        return redirect()->route('leave-balances.index')
            ->with('success', 'Leave balance deleted successfully.');
    }
}
