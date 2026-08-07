<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\SalaryStructure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payroll::with('employee.department', 'employee.designation');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $payrolls = $query->latest()->paginate(10);

        return view('payrolls.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')
            ->whereHas('activeSalaryStructure')
            ->get();
        return view('payrolls.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'employee_id' => 'nullable|exists:employees,id',
            'remarks' => 'nullable|string',
        ]);

        $month = $validated['month'];
        $year = $validated['year'];
        $remarks = $validated['remarks'] ?? null;

        // If single employee specified
        if ($request->filled('employee_id')) {
            $employee = Employee::with('activeSalaryStructure')->findOrFail($request->employee_id);
            
            // Check duplicate
            $exists = Payroll::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                return back()->withErrors(['employee_id' => "Payroll already exists for this employee for {$month}/{$year}."])->withInput();
            }

            if (!$employee->activeSalaryStructure) {
                return back()->withErrors(['employee_id' => "Employee does not have an active salary structure."])->withInput();
            }

            $this->generatePayrollRecord($employee, $month, $year, $remarks);
            
            return redirect()->route('payrolls.index')
                ->with('success', 'Payroll generated successfully for ' . $employee->first_name . ' ' . $employee->last_name);
        }

        // Bulk generation for all active employees with active structures
        $employees = Employee::where('status', 'active')
            ->with('activeSalaryStructure')
            ->get();

        $generatedCount = 0;
        $skippedCount = 0;

        foreach ($employees as $employee) {
            if (!$employee->activeSalaryStructure) {
                $skippedCount++;
                continue;
            }

            // Check duplicate
            $exists = Payroll::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            $this->generatePayrollRecord($employee, $month, $year, $remarks);
            $generatedCount++;
        }

        return redirect()->route('payrolls.index')
            ->with('success', "Payroll generation complete. Generated: {$generatedCount}, Skipped/Duplicates: {$skippedCount}.");
    }

    /**
     * Helper to generate a single payroll record
     */
    private function generatePayrollRecord(Employee $employee, $month, $year, $remarks)
    {
        $structure = $employee->activeSalaryStructure;
        
        $total_allowances = floatval($structure->house_allowance) + 
                            floatval($structure->medical_allowance) + 
                            floatval($structure->transport_allowance) + 
                            floatval($structure->other_allowance);
                            
        $gross_salary = floatval($structure->basic_salary) + $total_allowances;
        
        $total_deductions = floatval($structure->tax) + 
                            floatval($structure->provident_fund) + 
                            floatval($structure->other_deduction);
                            
        $net_salary = $gross_salary - $total_deductions;

        Payroll::create([
            'employee_id' => $employee->id,
            'salary_structure_id' => $structure->id,
            'month' => $month,
            'year' => $year,
            'gross_salary' => $gross_salary,
            'total_allowances' => $total_allowances,
            'total_deductions' => $total_deductions,
            'net_salary' => $net_salary,
            'payment_status' => 'pending',
            'remarks' => $remarks,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payroll = Payroll::with('employee.department', 'employee.designation', 'salaryStructure')->findOrFail($id);
        return view('payrolls.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return view('payrolls.edit', compact('payroll'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payroll = Payroll::findOrFail($id);

        $validated = $request->validate([
            'payment_status' => 'required|in:paid,pending',
            'payment_date' => 'nullable|required_if:payment_status,paid|date',
            'remarks' => 'nullable|string',
        ]);

        if ($validated['payment_status'] === 'paid' && empty($validated['payment_date'])) {
            $validated['payment_date'] = date('Y-m-d');
        }

        $payroll->update($validated);

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll record deleted successfully.');
    }

    /**
     * Payroll Dashboard
     */
    public function dashboard()
    {
        $totalPayroll = Payroll::count();
        $totalPaid = Payroll::where('payment_status', 'paid')->sum('net_salary');
        $pendingPayroll = Payroll::where('payment_status', 'pending')->sum('net_salary');
        $employeesPaidCount = Payroll::where('payment_status', 'paid')->distinct('employee_id')->count('employee_id');

        $recentPayrolls = Payroll::with('employee.department')
            ->latest()
            ->limit(5)
            ->get();

        $pendingPayments = Payroll::with('employee.department')
            ->where('payment_status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('payrolls.dashboard', compact(
            'totalPayroll', 'totalPaid', 'pendingPayroll', 'employeesPaidCount', 'recentPayrolls', 'pendingPayments'
        ));
    }
}
