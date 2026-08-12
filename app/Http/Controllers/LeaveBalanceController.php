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
        $employees = Employee::orderBy('first_name')->get();

        return view('leave-balances.index', compact('balances', 'employees'));
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
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'leave_type'  => 'required|string|max:255',
            'allocated'   => 'required|integer|min:1',
            'year'        => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if balance for this employee and type already exists
        $exists = LeaveBalance::where('employee_id', $request->employee_id)
            ->where('leave_type', $request->leave_type)
            ->exists();

        if ($exists) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['leave_type' => ['Leave balance of this type has already been allocated to this employee.']]
                ], 422);
            }
            return redirect()->back()->withErrors(['leave_type' => 'Leave balance of this type has already been allocated to this employee.']);
        }

        $balance = LeaveBalance::create([
            'employee_id' => $request->employee_id,
            'leave_type'  => $request->leave_type,
            'allocated'   => $request->allocated,
            'used'        => 0,
        ]);
        $balance->load('employee');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Leave balance allocated successfully.',
                'balance' => [
                    'id'            => $balance->id,
                    'employee_name' => $balance->employee->first_name . ' ' . $balance->employee->last_name,
                    'employee_code' => $balance->employee->employee_id ?? ('EMP-' . $balance->employee->id),
                    'leave_type'    => $balance->leave_type,
                    'allocated'     => $balance->allocated,
                    'used'          => $balance->used,
                    'remaining'     => $balance->allocated - $balance->used,
                    'edit_url'      => route('leave-balances.edit', $balance->id),
                    'destroy_url'   => route('leave-balances.destroy', $balance->id),
                ]
            ]);
        }

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
