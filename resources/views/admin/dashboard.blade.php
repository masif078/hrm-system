@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Welcome back, {{ Auth::user()->name }}</h2>
            <p class="text-muted mb-0">Here's what's happening across your organization today.</p>
        </div>
        <div class="text-secondary small fw-semibold bg-white px-3 py-2 rounded shadow-sm">
            {{ today()->format('F d, Y') }}
        </div>
    </div>

    {{-- Stats Cards Row --}}
    <div class="row g-4 mb-4">
        {{-- Card 1: Employees --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px;">
                        EMP
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Total Employees</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalEmployees }}</h3>
                        <small class="text-success fw-semibold">{{ $activeEmployees }} Active</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Projects --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-info text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px;">
                        PRJ
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Active Projects</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ $activeProjectsCount }}</h3>
                        <small class="text-secondary fw-semibold">{{ $totalProjectsCount }} Total Projects</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Pending Leaves --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-warning text-white d-flex align-items-center justify-content-center me-3 fw-bold text-dark" style="width: 56px; height: 56px;">
                        LV
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Pending Leaves</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ $pendingLeavesCount }}</h3>
                        <small class="text-warning fw-semibold">
                            <a href="{{ route('leaves.index') }}?status=Pending" class="text-decoration-none text-warning">Needs Approval</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Departments --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-success text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px;">
                        DPT
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Departments</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalDepartments }}</h3>
                        <small class="text-secondary fw-semibold">{{ $totalDesignations }} Designations</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payroll Stats Row --}}
    <div class="row g-4 mb-4">
        {{-- Card 1: Total Salary Paid --}}
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-success text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px;">
                        SAL
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Total Salary Paid</span>
                        <h4 class="fw-bold mb-0 text-dark">PKR {{ number_format($totalPaid, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Pending Payroll --}}
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-danger text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px;">
                        PEND
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Pending Payroll</span>
                        <h4 class="fw-bold mb-0 text-dark">PKR {{ number_format($pendingPayroll, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Current Month Payroll --}}
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-info text-white d-flex align-items-center justify-content-center me-3 fw-bold text-dark" style="width: 56px; height: 56px;">
                        CURR
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Current Month Payroll</span>
                        <h4 class="fw-bold mb-0 text-dark">PKR {{ number_format($currentMonthPayroll, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Quick Actions</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('employees.create') }}" class="btn btn-outline-primary w-100 py-3 text-start d-flex align-items-center shadow-sm">
                                <div class="ps-2">
                                    <h6 class="fw-bold mb-0">Add Employee</h6>
                                    <small class="text-muted">Register staff</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('projects.create') }}" class="btn btn-outline-info w-100 py-3 text-start d-flex align-items-center shadow-sm">
                                <div class="ps-2">
                                    <h6 class="fw-bold mb-0 text-info">New Project</h6>
                                    <small class="text-muted">Setup project</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('tasks.create') }}" class="btn btn-outline-success w-100 py-3 text-start d-flex align-items-center shadow-sm">
                                <div class="ps-2">
                                    <h6 class="fw-bold mb-0 text-success">Assign Task</h6>
                                    <small class="text-muted">Create todo</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary w-100 py-3 text-start d-flex align-items-center shadow-sm">
                                <div class="ps-2">
                                    <h6 class="fw-bold mb-0">View Reports</h6>
                                    <small class="text-muted">Download logs</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tasks & Attendance Row --}}
    <div class="row g-4">
        {{-- Recent Tasks --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Recent Tasks</h5>
                    <a href="{{ route('tasks.index') }}" class="text-decoration-none text-primary small fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Title</th>
                                    <th>Assigned To</th>
                                    <th>Priority</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Task::with('employee')->latest()->take(5)->get() as $task)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-semibold text-dark">{{ $task->title }}</span>
                                            <small class="text-muted d-block">{{ $task->project->project_name ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $task->employee->first_name ?? '' }} {{ $task->employee->last_name ?? '' }}</td>
                                        <td>
                                            @if($task->priority === 'High')
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">High</span>
                                            @elseif($task->priority === 'Medium')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Medium</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Low</span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            @if($task->status === 'Completed')
                                                <span class="badge bg-success text-white">Completed</span>
                                            @elseif($task->status === 'In Progress')
                                                <span class="badge bg-info text-white">In Progress</span>
                                            @else
                                                <span class="badge bg-secondary text-white">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No tasks available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Attendance --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Recent Attendance</h5>
                    <a href="{{ route('attendances.index') }}" class="text-decoration-none text-primary small fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse(\App\Models\Attendance::with('employee')->latest()->take(5)->get() as $attendance)
                            <div class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center border-0 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary-subtle text-secondary fw-bold d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        {{ substr($attendance->employee->first_name ?? 'E', 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $attendance->employee->first_name ?? '' }} {{ $attendance->employee->last_name ?? '' }}</h6>
                                        <small class="text-muted">In: {{ $attendance->formatted_check_in }} | Out: {{ $attendance->formatted_check_out }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                    {{ $attendance->status }}
                                </span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">No logs recorded today</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest Payslips --}}
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Latest Payslips</h5>
                    <a href="{{ route('payslips.index') }}" class="text-decoration-none text-primary small fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Employee</th>
                                    <th>Period</th>
                                    <th>Net Salary</th>
                                    <th>Paid Date</th>
                                    <th class="pe-4" width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestPayslips as $slip)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-semibold text-dark">{{ $slip->employee->first_name }} {{ $slip->employee->last_name }}</span>
                                            <small class="text-muted d-block">{{ $slip->employee->department?->name }}</small>
                                        </td>
                                        <td>{{ date('F Y', mktime(0, 0, 0, $slip->month, 10, $slip->year)) }}</td>
                                        <td>PKR {{ number_format($slip->net_salary, 2) }}</td>
                                        <td>{{ $slip->payment_date }}</td>
                                        <td class="pe-4">
                                            <a href="{{ route('payslips.show', $slip->id) }}" class="btn btn-outline-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No paid payslips available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Performance Widget --}}
    <div class="row g-4 mt-2 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-success text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px;">
                        TOP
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Top Performers</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $topPerformersCount }}</h4>
                        <small class="text-muted">Rating >= 4.0</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px;">
                        KPI
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Average KPI Score</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($averageKpiScore, 2) }}</h4>
                        <small class="text-muted">Total Score Avg</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="rounded-3 bg-warning text-white d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px;">
                        REV
                    </div>
                    <div>
                        <span class="text-muted d-block small mb-1">Pending Reviews</span>
                        <h4 class="fw-bold mb-0 text-dark">{{ $pendingReviewsCount }}</h4>
                        <small class="text-muted">Needs assessment</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recruitment Dashboard Row --}}
    <h5 class="fw-bold text-dark mt-4 mb-3">Recruitment & Hiring</h5>
    <div class="row g-4 mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3">
                    <span class="text-muted d-block small mb-1">Open Jobs</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $openJobsCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3">
                    <span class="text-muted d-block small mb-1">Candidates</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $candidatesCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3">
                    <span class="text-muted d-block small mb-1">Interviews Today</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $interviewsTodayCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3">
                    <span class="text-muted d-block small mb-1">Offers Pending</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $offersPendingCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6">
            <div class="card border-0 shadow-sm h-100 bg-white">
                <div class="card-body p-3">
                    <span class="text-muted d-block small mb-1">New Hires</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $newHiresCount }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
