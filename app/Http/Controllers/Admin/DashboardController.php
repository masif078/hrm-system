<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Leave;
use App\Models\Project;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPaid = \App\Models\Payroll::where('payment_status', 'paid')->sum('net_salary');
        $pendingPayroll = \App\Models\Payroll::where('payment_status', 'pending')->sum('net_salary');
        $currentMonthPayroll = \App\Models\Payroll::where('month', date('n'))->where('year', date('Y'))->sum('net_salary');
        $latestPayslips = \App\Models\Payroll::with('employee')->where('payment_status', 'paid')->latest()->take(5)->get();

        $topPerformersCount = Employee::has('performanceReviews')
            ->withAvg('performanceReviews', 'rating')
            ->having('performance_reviews_avg_rating', '>=', 4.0)
            ->get()
            ->count();
        $averageKpiScore = \App\Models\KpiScore::avg('score') ?: 0;
        $pendingReviewsCount = \App\Models\PerformanceReview::where('status', 'Pending')->count();

        // Recruitment dashboard counts
        $openJobsCount = \App\Models\JobOpening::where('status', 'Open')->count();
        $candidatesCount = \App\Models\Candidate::count();
        $interviewsTodayCount = \App\Models\Interview::whereDate('date', date('Y-m-d'))->count();
        $offersPendingCount = \App\Models\OfferLetter::where('status', 'Pending')->count();
        $newHiresCount = \App\Models\Candidate::where('status', 'Hired')->count();

        return view('admin.dashboard', [
            'totalEmployees' => Employee::count(),
            'totalDepartments' => Department::count(),
            'totalDesignations' => Designation::count(),
            'activeEmployees' => Employee::where('status', 'Active')->count(),
            'pendingLeavesCount' => Leave::where('status', 'Pending')->count(),
            'totalProjectsCount' => Project::count(),
            'activeProjectsCount' => Project::where('status', 'In Progress')->count(),
            'totalPaid' => $totalPaid,
            'pendingPayroll' => $pendingPayroll,
            'currentMonthPayroll' => $currentMonthPayroll,
            'latestPayslips' => $latestPayslips,
            'topPerformersCount' => $topPerformersCount,
            'averageKpiScore' => $averageKpiScore,
            'pendingReviewsCount' => $pendingReviewsCount,
            'openJobsCount' => $openJobsCount,
            'candidatesCount' => $candidatesCount,
            'interviewsTodayCount' => $interviewsTodayCount,
            'offersPendingCount' => $offersPendingCount,
            'newHiresCount' => $newHiresCount,
            'recentTasks' => \App\Models\Task::with('employee')->latest()->take(5)->get(),
            'recentEmployees' => Employee::with('department')->latest()->take(5)->get(),
            'departments' => Department::orderBy('name')->get(),
            'designations' => Designation::orderBy('title')->get(),
            'branches' => \App\Models\Branch::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'projects' => Project::orderBy('project_name')->get(),
            'clients' => \App\Models\Client::orderBy('company_name')->get(),
        ]);
    }
}
