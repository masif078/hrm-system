@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="container-fluid">
    @if(isset($noProfile) && $noProfile)
        <div class="card border-0 shadow-sm mt-4 bg-white">
            <div class="card-body p-5 text-center">
                <h3 class="fw-bold">No Employee Profile Found</h3>
                <p class="text-muted mb-4">Your user account is not linked to any employee record. Please contact the administrator to create your employee profile.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger px-4">Logout</button>
                </form>
            </div>
        </div>
    @else
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Hello, {{ $employee->first_name }} {{ $employee->last_name }}</h2>
                <p class="text-muted mb-0">Here is your workload and attendance overview for today.</p>
            </div>
            <div class="text-secondary small fw-semibold bg-white px-3 py-2 rounded shadow-sm">
                {{ today()->format('F d, Y') }}
            </div>
        </div>

        {{-- Session Flash Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Stats Cards Row --}}
        <div class="row g-4 mb-4">
            {{-- Card 1: Attendance Status --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4">
                        <span class="text-muted d-block small mb-1">Today's Status</span>
                        @if(!$todayAttendance)
                            <h5 class="fw-bold mb-0 text-danger">Not Checked In</h5>
                        @elseif(!$todayAttendance->check_out)
                            <h5 class="fw-bold mb-0 text-success">Checked In</h5>
                            <small class="text-secondary small">at {{ $todayAttendance->formatted_check_in }}</small>
                        @else
                            <h5 class="fw-bold mb-0 text-secondary">Checked Out</h5>
                            <small class="text-secondary small">In: {{ $todayAttendance->formatted_check_in }} | Out: {{ $todayAttendance->formatted_check_out }}</small>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card 2: Pending/In Progress Tasks --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4">
                        <span class="text-muted d-block small mb-1">Pending Tasks</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ $pendingTasksCount }}</h3>
                        <small class="text-secondary fw-semibold">{{ $completedTasksCount }} Tasks Completed</small>
                    </div>
                </div>
            </div>

            {{-- Card 3: Pending Leaves --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4">
                        <span class="text-muted d-block small mb-1">Pending Leaves</span>
                        <h3 class="fw-bold mb-0 text-dark">{{ $pendingLeaves }}</h3>
                        <small class="text-success fw-semibold">{{ $approvedLeaves }} Approved</small>
                    </div>
                </div>
            </div>

            {{-- Card 4: Joining Date Info --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4">
                        <span class="text-muted d-block small mb-1">Joined Company</span>
                        <h5 class="fw-bold mb-0 text-dark">{{ \Carbon\Carbon::parse($employee->joining_date)->format('M d, Y') }}</h5>
                        <small class="text-secondary small">Member since registration</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- Check-In/Check-Out Widget --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold text-dark mb-0">Attendance Mark</h5>
                    </div>
                    <div class="card-body px-4 pb-4 d-flex flex-column justify-content-center align-items-center text-center">
                        @if(!$todayAttendance)
                            <h5 class="fw-bold mb-2">Not Checked In Yet</h5>
                            <p class="text-muted px-4 mb-4 small">To mark your attendance for today, please click the check-in button below.</p>
                            <form action="{{ route('employee.attendances.checkin') }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm">
                                    Check In Now
                                </button>
                            </form>
                        @elseif(!$todayAttendance->check_out)
                            <h5 class="fw-bold mb-1">Checked In</h5>
                            <p class="text-muted small mb-0">Check In Time: <strong class="text-dark">{{ $todayAttendance->formatted_check_in }}</strong></p>
                            <p class="text-muted px-4 mb-4 small">Don't forget to check out before leaving the office today.</p>
                            <form action="{{ route('employee.attendances.checkout') }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg w-100 shadow-sm">
                                    Check Out Now
                                </button>
                            </form>
                        @else
                            <h5 class="fw-bold mb-1 text-success">Attendance Done</h5>
                            <p class="text-muted small mb-4">Checked in at <strong class="text-dark">{{ $todayAttendance->formatted_check_in }}</strong> and checked out at <strong class="text-dark">{{ $todayAttendance->formatted_check_out }}</strong>.</p>
                            <div class="bg-light rounded p-3 text-secondary small w-100 text-center">
                                You are all set for today. Enjoy the rest of your day!
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Stats / Contact Manager --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold text-dark mb-0">Profile & Leaves Info</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row g-4">
                            <div class="col-md-6 border-end border-light">
                                <h6 class="fw-bold mb-3 text-secondary small text-uppercase">Emergency Contact</h6>
                                @if($employee->phone)
                                    <div class="mb-2">
                                        <span class="text-muted small">My Phone:</span>
                                        <p class="fw-semibold text-dark mb-0">{{ $employee->phone }}</p>
                                    </div>
                                @endif
                                <div class="mb-2">
                                    <span class="text-muted small">Joining Date:</span>
                                    <p class="fw-semibold text-dark mb-0">{{ \Carbon\Carbon::parse($employee->joining_date)->format('F d, Y') }}</p>
                                </div>
                                <div class="mb-0">
                                    <span class="text-muted small">Employment Status:</span>
                                    <p class="mb-0"><span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">{{ $employee->status }}</span></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-secondary small text-uppercase">Leaves Statistics</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Pending Requests:</span>
                                    <span class="badge bg-warning text-dark fw-bold">{{ $pendingLeaves }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Approved Leaves:</span>
                                    <span class="badge bg-success text-white fw-bold">{{ $approvedLeaves }}</span>
                                </div>
                                <hr class="my-3 opacity-25">
                                <a href="{{ route('employee.leaves.create') }}" class="btn btn-outline-primary btn-sm w-100 py-2">
                                    Apply for Leave
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tasks Row --}}
        <div class="row g-4">
            {{-- My Tasks --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0">My Active Tasks</h5>
                        <a href="{{ route('employee.tasks.index') }}" class="text-decoration-none text-primary small fw-semibold">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Task Details</th>
                                        <th>Project</th>
                                        <th>Priority</th>
                                        <th class="pe-4">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myTasks as $task)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-semibold text-dark">{{ $task->title }}</span>
                                                <small class="text-muted d-block">{{ Str::limit($task->description, 40) }}</small>
                                            </td>
                                            <td>
                                                <span class="text-secondary small fw-semibold">{{ $task->project->project_name ?? 'N/A' }}</span>
                                            </td>
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
                                                <small class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-danger fw-bold' : 'text-muted' }}">
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No tasks assigned to you.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Leaves History --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0">My Leave History</h5>
                        <a href="{{ route('employee.leaves.index') }}" class="text-decoration-none text-primary small fw-semibold">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($myLeaves as $leave)
                                <div class="list-group-item px-4 py-3 border-0 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $leave->leave_type }}</h6>
                                        @if($leave->status === 'Approved')
                                            <span class="badge bg-success text-white">Approved</span>
                                        @elseif($leave->status === 'Rejected')
                                            <span class="badge bg-danger text-white">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </div>
                                    <small class="text-muted d-block">Dates: {{ $leave->start_date }} to {{ $leave->end_date }}</small>
                                    @if($leave->admin_remarks)
                                        <small class="text-secondary d-block mt-1"><em>Remarks: {{ $leave->admin_remarks }}</em></small>
                                    @endif
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">No leave requests found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payroll & Salary Info Row --}}
        <div class="row g-4 mt-2">
            {{-- My Salary Structure --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold text-dark mb-0">My Salary Structure</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        @if($myActiveSalaryStructure)
                            <div class="text-center mb-4">
                                <span class="text-muted d-block small mb-1">Monthly Net Salary</span>
                                <h3 class="fw-bold text-primary mb-0">PKR {{ number_format($myActiveSalaryStructure->net_salary, 2) }}</h3>
                            </div>
                            <div class="row g-3 small">
                                <div class="col-6 border-end">
                                    <span class="text-muted d-block">Basic Salary:</span>
                                    <span class="fw-semibold text-dark">PKR {{ number_format($myActiveSalaryStructure->basic_salary, 2) }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">Effective From:</span>
                                    <span class="fw-semibold text-dark">{{ $myActiveSalaryStructure->effective_from }}</span>
                                </div>
                            </div>
                            <hr class="my-3 opacity-25">
                            <div class="row g-3 small">
                                <div class="col-6 border-end">
                                    <span class="text-success fw-semibold">Allowances:</span>
                                    <?php
                                        $total_allow = floatval($myActiveSalaryStructure->house_allowance) + 
                                                       floatval($myActiveSalaryStructure->medical_allowance) + 
                                                       floatval($myActiveSalaryStructure->transport_allowance) + 
                                                       floatval($myActiveSalaryStructure->other_allowance);
                                    ?>
                                    <span class="fw-semibold text-dark d-block">PKR {{ number_format($total_allow, 2) }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-danger fw-semibold">Deductions:</span>
                                    <?php
                                        $total_ded = floatval($myActiveSalaryStructure->tax) + 
                                                     floatval($myActiveSalaryStructure->provident_fund) + 
                                                     floatval($myActiveSalaryStructure->other_deduction);
                                    ?>
                                    <span class="fw-semibold text-dark d-block">PKR {{ number_format($total_ded, 2) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <p class="mb-0">No active salary structure assigned yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Latest Payslip & History --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0">My Payment History</h5>
                        <a href="{{ route('payslips.index') }}" class="text-decoration-none text-primary small fw-semibold">View All</a>
                    </div>
                    <div class="card-body p-0">
                        @if($latestPayslip)
                            <div class="bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-semibold text-dark d-block">Latest Payslip: {{ date('F Y', mktime(0, 0, 0, $latestPayslip->month, 10, $latestPayslip->year)) }}</span>
                                    <small class="text-muted">Paid on: {{ $latestPayslip->payment_date }}</small>
                                </div>
                                <a href="{{ route('payslips.download', $latestPayslip->id) }}" class="btn btn-primary btn-sm">Download PDF</a>
                            </div>
                        @endif
                        <div class="list-group list-group-flush">
                            @forelse($paymentHistory as $history)
                                <div class="list-group-item px-4 py-3 border-0 border-bottom d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">{{ date('F Y', mktime(0, 0, 0, $history->month, 10, $history->year)) }}</h6>
                                        <small class="text-muted">PKR {{ number_format($history->net_salary, 2) }} | Paid: {{ $history->payment_date }}</small>
                                    </div>
                                    <a href="{{ route('payslips.show', $history->id) }}" class="btn btn-outline-info btn-sm">View</a>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">No payment records found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Performance & Goals Row --}}
        <div class="row g-4 mt-2 mb-4">
            {{-- Goals progress widget --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0">My Performance Goals</h5>
                        <a href="{{ route('goals.index') }}" class="text-decoration-none text-primary small fw-semibold">View Goals</a>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="text-center mb-4">
                            <span class="text-muted d-block small mb-1">Completed Goals</span>
                            <h3 class="fw-bold text-success mb-0">{{ $completedGoalsCount }} / {{ $myGoalsCount }}</h3>
                            <small class="text-muted">Active goals tracked</small>
                        </div>
                        
                        @if($myGoalsCount > 0)
                            <?php
                                $percent = round(($completedGoalsCount / $myGoalsCount) * 100);
                            ?>
                            <span class="text-muted small d-block mb-2">Overall Goal Achievement Rate:</span>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $percent }}%
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3 text-muted small">No goals assigned for tracking yet.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Performance Review Scorecard --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0">My Latest Review Score</h5>
                        <a href="{{ route('performance-reviews.index') }}" class="text-decoration-none text-primary small fw-semibold">View Reviews</a>
                    </div>
                    <div class="card-body px-4 pb-4 d-flex flex-column justify-content-center align-items-center">
                        @if($latestReviewRating)
                            <div class="text-center mb-3">
                                <span class="text-muted d-block small mb-1">Last Review Rating</span>
                                <h1 class="fw-bold text-primary mb-0" style="font-size: 3.5rem;">{{ number_format($latestReviewRating, 2) }}</h1>
                                <span class="text-muted small">Out of 5.00</span>
                            </div>
                            
                            <span class="badge {{ $latestReviewRating >= 4.0 ? 'bg-success' : ($latestReviewRating >= 3.0 ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2 fs-6">
                                @if($latestReviewRating >= 4.5)
                                    Outstanding Performer
                                @elseif($latestReviewRating >= 4.0)
                                    High Performer
                                @elseif($latestReviewRating >= 3.0)
                                    Good Performer
                                @else
                                    Needs Improvement
                                @endif
                            </span>
                        @else
                            <div class="text-center py-4 text-muted">
                                <p class="mb-0">No performance review score has been recorded yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection