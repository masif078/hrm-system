<?php

namespace App\Http\Controllers;

use App\Models\SalaryStructure;
use App\Models\Employee;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SalaryStructure::with('employee.department', 'employee.designation');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $salaryStructures = $query->latest()->paginate(10);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('salary-structures.index', compact('salaryStructures', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        return view('salary-structures.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'employee_id'         => 'required|exists:employees,id',
            'basic_salary'        => 'required|numeric|min:0',
            'house_allowance'     => 'nullable|numeric|min:0',
            'medical_allowance'   => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'other_allowance'     => 'nullable|numeric|min:0',
            'tax'                 => 'nullable|numeric|min:0',
            'provident_fund'      => 'nullable|numeric|min:0',
            'other_deduction'     => 'nullable|numeric|min:0',
            'effective_from'      => 'required|date',
            'status'              => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Default nulls to 0
        $basic = floatval($validated['basic_salary']);
        $house = floatval($validated['house_allowance'] ?? 0);
        $medical = floatval($validated['medical_allowance'] ?? 0);
        $transport = floatval($validated['transport_allowance'] ?? 0);
        $other_allow = floatval($validated['other_allowance'] ?? 0);
        $tax = floatval($validated['tax'] ?? 0);
        $pf = floatval($validated['provident_fund'] ?? 0);
        $other_deduct = floatval($validated['other_deduction'] ?? 0);

        // Gross = Basic + Allowances
        $gross = $basic + $house + $medical + $transport + $other_allow;
        // Total Deductions = Tax + PF + Other Deductions
        $deductions = $tax + $pf + $other_deduct;
        // Net = Gross - Deductions
        $net = $gross - $deductions;

        if ($net < 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => ['basic_salary' => ['Net salary cannot be negative. Please adjust allowances and deductions.']]], 422);
            }
            return back()->withErrors(['basic_salary' => 'Net salary cannot be negative. Please adjust allowances and deductions.'])->withInput();
        }

        $validated['net_salary'] = $net;

        // If status is active, deactivate other structures of this employee
        if ($validated['status'] === 'active') {
            SalaryStructure::where('employee_id', $validated['employee_id'])
                ->update(['status' => 'inactive']);
        }

        $salaryStructure = SalaryStructure::create($validated);
        $salaryStructure->load('employee.department', 'employee.designation');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'          => true,
                'message'          => 'Salary Structure created successfully.',
                'salary_structure' => [
                    'id'                => $salaryStructure->id,
                    'employee_name'     => $salaryStructure->employee->first_name . ' ' . $salaryStructure->employee->last_name,
                    'department_name'   => $salaryStructure->employee->department?->name ?? 'N/A',
                    'designation_title' => $salaryStructure->employee->designation?->title ?? 'N/A',
                    'basic_salary'      => number_format($salaryStructure->basic_salary, 2),
                    'net_salary'        => number_format($salaryStructure->net_salary, 2),
                    'effective_from'    => $salaryStructure->effective_from,
                    'status'            => $salaryStructure->status,
                    'show_url'          => route('salary-structures.show', $salaryStructure->id),
                    'edit_url'          => route('salary-structures.edit', $salaryStructure->id),
                    'destroy_url'       => route('salary-structures.destroy', $salaryStructure->id),
                ]
            ]);
        }

        return redirect()->route('salary-structures.index')
            ->with('success', 'Salary Structure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salaryStructure = SalaryStructure::with('employee.department', 'employee.designation')->findOrFail($id);
        return view('salary-structures.show', compact('salaryStructure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $salaryStructure = SalaryStructure::findOrFail($id);
        $employees = Employee::where('status', 'active')->get();
        return view('salary-structures.edit', compact('salaryStructure', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $salaryStructure = SalaryStructure::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'house_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'other_deduction' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $basic = floatval($validated['basic_salary']);
        $house = floatval($validated['house_allowance'] ?? 0);
        $medical = floatval($validated['medical_allowance'] ?? 0);
        $transport = floatval($validated['transport_allowance'] ?? 0);
        $other_allow = floatval($validated['other_allowance'] ?? 0);
        $tax = floatval($validated['tax'] ?? 0);
        $pf = floatval($validated['provident_fund'] ?? 0);
        $other_deduct = floatval($validated['other_deduction'] ?? 0);

        $gross = $basic + $house + $medical + $transport + $other_allow;
        $deductions = $tax + $pf + $other_deduct;
        $net = $gross - $deductions;

        if ($net < 0) {
            return back()->withErrors(['basic_salary' => 'Net salary cannot be negative. Please adjust allowances and deductions.'])->withInput();
        }

        $validated['net_salary'] = $net;

        if ($validated['status'] === 'active') {
            SalaryStructure::where('employee_id', $validated['employee_id'])
                ->where('id', '!=', $id)
                ->update(['status' => 'inactive']);
        }

        $salaryStructure->update($validated);

        return redirect()->route('salary-structures.index')
            ->with('success', 'Salary Structure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salaryStructure = SalaryStructure::findOrFail($id);
        $salaryStructure->delete();

        return redirect()->route('salary-structures.index')
            ->with('success', 'Salary Structure deleted successfully.');
    }
}
