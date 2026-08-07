<?php

namespace App\Http\Controllers;

use App\Exports\EmployeesExport;
use App\Models\Attendance;
use App\Models\Client;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Project;
use App\Models\Task;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Employee Report
     */
    public function employees(Request $request)
    {
        $query = Employee::with(['department', 'designation']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $employees = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.employees', compact('employees'));
    }


    /**
     * Attendance Report
     */
    public function attendance(Request $request)
    {
        $query = Attendance::with('employee.department');

        // Filter by Employee
        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter by Month and Year
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        // Get attendance records
        $attendances = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // Get aggregates (Total late arrivals, early checkouts, working hours, overtime hours)
        $aggregatesQuery = clone $query;
        $totalLateArrivals = $aggregatesQuery->where('late_arrival', true)->count();
        
        $aggregatesQuery2 = clone $query;
        $totalEarlyCheckouts = $aggregatesQuery2->where('early_checkout', true)->count();

        $aggregatesQuery3 = clone $query;
        $totalWorkingHours = $aggregatesQuery3->sum('working_hours');

        $aggregatesQuery4 = clone $query;
        $totalOvertimeHours = $aggregatesQuery4->sum('overtime_hours');

        // Fetch Leave Balances for summary
        $leaveBalancesQuery = \App\Models\LeaveBalance::with('employee');
        if ($request->filled('employee')) {
            $leaveBalancesQuery->where('employee_id', $request->employee);
        }
        $leaveBalances = $leaveBalancesQuery->take(50)->get();

        // Get employees for dropdown
        $employees = Employee::orderBy('first_name')->get();

        return view('reports.attendance', compact(
            'attendances',
            'employees',
            'totalLateArrivals',
            'totalEarlyCheckouts',
            'totalWorkingHours',
            'totalOvertimeHours',
            'leaveBalances'
        ));
    }


    /**
     * Leave Report
     */
    public function leaves(Request $request)
    {
        $query = Leave::with('employee');

        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('date')) {
            $query->whereDate('start_date', $request->date);
        }

        $employees = Employee::orderBy('first_name')->get();

        $leaves = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.leaves', compact('leaves', 'employees'));
    }


    /**
     * Project Report
     */
    public function projects(Request $request)
    {
        $query = Project::with(['client', 'manager']);

        if ($request->filled('client')) {
            $query->where('client_id', $request->client);
        }

        if ($request->filled('manager')) {
            $query->where('project_manager_id', $request->manager);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('project_name', 'like', '%' . $request->search . '%');
        }

        $projects = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $clients = Client::orderBy('company_name')->get();
        $managers = Employee::orderBy('first_name')->get();

        return view('reports.projects', compact('projects', 'clients', 'managers'));
    }


    /**
     * Reports Dashboard
     */
    public function dashboard()
    {
        $employees   = Employee::count();
        $departments = Department::count();
        $clients     = Client::count();
        $projects    = Project::count();
        $tasks       = Task::count();
        $leaves      = Leave::count();

        $recentProjects  = Project::latest()->take(5)->get();
        $recentEmployees = Employee::latest()->take(5)->get();

        return view('reports.dashboard', compact(
            'employees',
            'departments',
            'clients',
            'projects',
            'tasks',
            'leaves',
            'recentProjects',
            'recentEmployees'
        ));
    }


    /**
     * Employee PDF
     */
    public function employeePdf()
    {
        $employees = Employee::with(['department', 'designation'])->get();

        $pdf = Pdf::loadView('reports.employee-pdf', compact('employees'));

        return $pdf->download('employees-report.pdf');
    }


    /**
     * Employee Excel
     */
    public function employeeExcel()
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }

    /**
     * Payroll Report
     */
    public function payroll(Request $request)
    {
        $query = \App\Models\Payroll::with('employee.department', 'employee.designation');

        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $payrolls = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalsQuery = clone $query;
        $totalPaid = $totalsQuery->where('payment_status', 'paid')->sum('net_salary');
        $totalAllowances = $totalsQuery->sum('total_allowances');
        $totalDeductions = $totalsQuery->sum('total_deductions');
        $totalNet = $totalsQuery->sum('net_salary');

        $employees = Employee::orderBy('first_name')->get();
        $departments = Department::orderBy('name')->get();

        return view('reports.payroll', compact(
            'payrolls', 'employees', 'departments', 'totalPaid', 'totalAllowances', 'totalDeductions', 'totalNet'
        ));
    }

    /**
     * Performance Report
     */
    public function performance(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        
        $topQuery = Employee::withAvg('performanceReviews', 'rating')
            ->withCount('goals')
            ->withCount(['goals as completed_goals_count' => function ($q) {
                $q->where('status', 'Completed');
            }]);
            
        $lowQuery = Employee::withAvg('performanceReviews', 'rating')
            ->withCount('goals');

        if ($request->filled('department_id')) {
            $topQuery->where('department_id', $request->department_id);
            $lowQuery->where('department_id', $request->department_id);
        }

        // Top Performers: average rating >= 4.0
        $topPerformers = $topQuery->having('performance_reviews_avg_rating', '>=', 4.0)
            ->orderByDesc('performance_reviews_avg_rating')
            ->get();

        // Low Performers: average rating < 3.0 (and must have at least one review to not show new employees as low performers)
        $lowPerformers = $lowQuery->has('performanceReviews')
            ->having('performance_reviews_avg_rating', '<', 3.0)
            ->orderBy('performance_reviews_avg_rating')
            ->get();

        // KPI Scores & Appraisals
        $kpiScoresQuery = \App\Models\KpiScore::with(['employee', 'kpi']);
        $appraisalsQuery = \App\Models\Appraisal::with(['employee', 'previousDesignation', 'newDesignation'])->where('status', 'Approved');

        if ($request->filled('department_id')) {
            $kpiScoresQuery->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
            $appraisalsQuery->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $kpiScores = $kpiScoresQuery->latest()->get();
        $appraisals = $appraisalsQuery->latest()->get();

        return view('reports.performance', compact(
            'departments', 'topPerformers', 'lowPerformers', 'kpiScores', 'appraisals'
        ));
    }

    /**
     * Recruitment Report
     */
    public function recruitment(Request $request)
    {
        $departments = Department::withCount(['employees'])->get();
        
        // Count job vacancies by department
        $jobsByDept = Department::withCount(['employees'])
            ->withCount(['employees as vacancies_count' => function ($q) {
                // simple subquery or fetch job openings vacancy sum
            }])
            ->get();
        
        $jobOpenings = \App\Models\JobOpening::with('department')->get();
        $candidates = \App\Models\Candidate::all();
        $applications = \App\Models\Application::with(['candidate', 'jobOpening'])->get();
        $offerLetters = \App\Models\OfferLetter::all();

        // Source statistics
        $sourceStats = \App\Models\Candidate::select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->get();

        // Stage distributions
        $stageStats = \App\Models\Candidate::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Offer Acceptance calculations
        $totalOffers = $offerLetters->count();
        $acceptedOffers = $offerLetters->where('status', 'Accepted')->count();
        $acceptanceRate = $totalOffers > 0 ? round(($acceptedOffers / $totalOffers) * 100, 1) : 0;

        return view('reports.recruitment', compact(
            'jobOpenings', 'candidates', 'applications', 'sourceStats', 'stageStats', 'acceptanceRate', 'totalOffers', 'acceptedOffers'
        ));
    }

    /**
     * Asset Report
     */
    public function assets(Request $request)
    {
        $assets = \App\Models\Asset::with(['category', 'assignments' => function ($q) {
            $q->where('status', 'Assigned');
        }, 'assignments.employee'])->get();

        $maintenanceLogs = \App\Models\AssetMaintenanceLog::with('asset')->get();

        // Group by category counts
        $categoryStats = \App\Models\AssetCategory::withCount('assets')->get();

        // Group by status counts
        $statusStats = \App\Models\Asset::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $totalMaintenanceCost = $maintenanceLogs->sum('cost');

        return view('reports.assets', compact(
            'assets', 'maintenanceLogs', 'categoryStats', 'statusStats', 'totalMaintenanceCost'
        ));
    }
}