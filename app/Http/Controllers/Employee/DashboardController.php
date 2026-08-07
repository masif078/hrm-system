<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return view('employee.dashboard', ['noProfile' => true]);
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        $pendingLeaves = Leave::where('employee_id', $employee->id)
            ->where('status', 'Pending')
            ->count();

        $approvedLeaves = Leave::where('employee_id', $employee->id)
            ->where('status', 'Approved')
            ->count();

        $pendingTasksCount = Task::where('employee_id', $employee->id)
            ->whereIn('status', ['Pending', 'In Progress'])
            ->count();

        $completedTasksCount = Task::where('employee_id', $employee->id)
            ->where('status', 'Completed')
            ->count();

        $myTasks = Task::where('employee_id', $employee->id)
            ->with('project')
            ->latest()
            ->take(5)
            ->get();

        $myLeaves = Leave::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        $myActiveSalaryStructure = \App\Models\SalaryStructure::where('employee_id', $employee->id)
            ->where('status', 'active')
            ->first();

        $latestPayslip = \App\Models\Payroll::where('employee_id', $employee->id)
            ->where('payment_status', 'paid')
            ->latest()
            ->first();

        $paymentHistory = \App\Models\Payroll::where('employee_id', $employee->id)
            ->where('payment_status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        $myGoalsCount = \App\Models\Goal::where('employee_id', $employee->id)->count();
        $completedGoalsCount = \App\Models\Goal::where('employee_id', $employee->id)->where('status', 'Completed')->count();
        $latestReview = \App\Models\PerformanceReview::where('employee_id', $employee->id)->latest()->first();
        $latestReviewRating = $latestReview ? $latestReview->rating : null;

        return view('employee.dashboard', compact(
            'employee',
            'todayAttendance',
            'pendingLeaves',
            'approvedLeaves',
            'pendingTasksCount',
            'completedTasksCount',
            'myTasks',
            'myLeaves',
            'myActiveSalaryStructure',
            'latestPayslip',
            'paymentHistory',
            'myGoalsCount',
            'completedGoalsCount',
            'latestReviewRating'
        ));
    }
}