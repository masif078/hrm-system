<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{
    /**
     * Display a listing of the payslips.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Payroll::with('employee.department', 'employee.designation');

        // Security check: Employees can only see their own payslips
        if ($user->role === 'employee') {
            $query->whereHas('employee', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('payment_status', 'paid');
        }

        // Filters
        if ($request->filled('search') && $user->role === 'admin') {
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

        $payslips = $query->latest()->paginate(10);

        return view('payslips.index', compact('payslips'));
    }

    /**
     * Display the specified payslip.
     */
    public function show(string $id)
    {
        $payroll = Payroll::with('employee.department', 'employee.designation', 'salaryStructure')->findOrFail($id);
        $user = auth()->user();

        // Security check
        if ($user->role === 'employee' && $payroll->employee->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('payslips.show', compact('payroll'));
    }

    /**
     * Download the payslip as a PDF.
     */
    public function download(string $id)
    {
        $payroll = Payroll::with('employee.department', 'employee.designation', 'salaryStructure')->findOrFail($id);
        $user = auth()->user();

        // Security check
        if ($user->role === 'employee' && $payroll->employee->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $pdf = Pdf::loadView('payslips.pdf', compact('payroll'));
        
        $filename = 'Payslip_' . str_replace(' ', '_', $payroll->employee->first_name) . '_' . date('F_Y', mktime(0, 0, 0, $payroll->month, 10, $payroll->year)) . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Print the payslip.
     */
    public function print(string $id)
    {
        $payroll = Payroll::with('employee.department', 'employee.designation', 'salaryStructure')->findOrFail($id);
        $user = auth()->user();

        // Security check
        if ($user->role === 'employee' && $payroll->employee->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('payslips.print', compact('payroll'));
    }
}
