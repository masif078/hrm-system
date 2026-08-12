@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="container-fluid px-0">
    @if(isset($noProfile) && $noProfile)
        <div class="card border-0 shadow-sm mt-4 bg-white rounded-4">
            <div class="card-body p-5 text-center">
                <h3 class="fw-bold">No Employee Profile Found</h3>
                <p class="text-muted mb-4">Your user account is not linked to any employee record. Please contact the administrator to create your employee profile.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger px-4 rounded-3">Logout</button>
                </form>
            </div>
        </div>
    @else
        {{-- Header Banner Card --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Hello, {{ $employee->first_name }} {{ $employee->last_name }} 👋</h3>
                    <p class="text-secondary small mb-0">Here is your daily workload, attendance log, and personal performance overview.</p>
                </div>
                <div class="text-secondary small fw-semibold bg-light px-3.5 py-2 rounded-3 border border-light-subtle">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> {{ today()->format('F d, Y') }}
                </div>
            </div>
        </div>

        {{-- Session Flash Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 shadow-sm border-0" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-4 shadow-sm border-0" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Stats Cards Row --}}
        <div class="row g-4 mb-4">
            {{-- Card 1: Attendance Status --}}
            <div class="col-xl-3 col-md-6">
                <x-stat-card 
                    title="Today's Status" 
                    :value="!$todayAttendance ? 'Absent' : (!$todayAttendance->check_out ? 'Checked In' : 'Checked Out')" 
                    color="blue" 
                    icon="bi-clock-history" 
                    trend="Attendance" 
                    trendType="neutral" 
                    :link="route('employee.attendances.index')" 
                />
            </div>

            {{-- Card 2: Pending Tasks --}}
            <div class="col-xl-3 col-md-6">
                <x-stat-card 
                    title="Pending Tasks" 
                    :value="$pendingTasksCount" 
                    color="amber" 
                    icon="bi-check2-square" 
                    trend="Completed: {$completedTasksCount}" 
                    trendType="down" 
                    :link="route('employee.tasks.index')" 
                />
            </div>

            {{-- Card 3: Pending Leaves --}}
            <div class="col-xl-3 col-md-6">
                <x-stat-card 
                    title="Pending Leaves" 
                    :value="$pendingLeaves" 
                    color="purple" 
                    icon="bi-calendar-event" 
                    trend="Approved: {$approvedLeaves}" 
                    trendType="up" 
                    :link="route('employee.leaves.index')" 
                />
            </div>

            {{-- Card 4: Joining Date Info --}}
            <div class="col-xl-3 col-md-6">
                <x-stat-card 
                    title="Member Since" 
                    :value="\Carbon\Carbon::parse($employee->joining_date)->format('M d, Y')" 
                    color="green" 
                    icon="bi-award" 
                    trend="Active Employee" 
                    trendType="up" 
                    :link="route('employee.dashboard')" 
                />
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- Check-In/Check-Out Widget --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-person-check text-primary me-2"></i>Attendance Mark</h5>
                    </div>
                    <div class="card-body px-4 pb-4 d-flex flex-column justify-content-center align-items-center text-center">
                        @if(!$todayAttendance)
                            <h5 class="fw-bold mb-2">Not Checked In Yet</h5>
                            <p class="text-secondary px-4 mb-4 small">To mark your attendance for today, please click the check-in button below.</p>
                            <form action="{{ route('employee.attendances.checkin') }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100 rounded-3 shadow-sm fw-bold">
                                    Check In Now
                                </button>
                            </form>
                        @elseif(!$todayAttendance->check_out)
                            <h5 class="fw-bold mb-1">Checked In</h5>
                            <p class="text-secondary small mb-0">Check In Time: <strong class="text-dark">{{ $todayAttendance->formatted_check_in }}</strong></p>
                            <p class="text-secondary px-4 mb-4 small">Don't forget to check out before leaving the office today.</p>
                            <form action="{{ route('employee.attendances.checkout') }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg w-100 rounded-3 shadow-sm fw-bold">
                                    Check Out Now
                                </button>
                            </form>
                        @else
                            <h5 class="fw-bold mb-1 text-success">Attendance Done</h5>
                            <p class="text-secondary small mb-4">Checked in at <strong class="text-dark">{{ $todayAttendance->formatted_check_in }}</strong> and checked out at <strong class="text-dark">{{ $todayAttendance->formatted_check_out }}</strong>.</p>
                            <div class="bg-light rounded-3 p-3 text-secondary small w-100 text-center">
                                You are all set for today. Enjoy the rest of your day!
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Stats / Contact Manager --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-person-lines-fill text-primary me-2"></i>Profile & Leaves Info</h5>
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
                                    <p class="mb-0"><x-status-badge :status="$employee->status" /></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 text-secondary small text-uppercase">Leaves Statistics</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Pending Requests:</span>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill fw-bold px-3 py-1">{{ $pendingLeaves }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Approved Leaves:</span>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-bold px-3 py-1">{{ $approvedLeaves }}</span>
                                </div>
                                <hr class="my-3 opacity-25">
                                <a href="{{ route('employee.leaves.create') }}" class="btn btn-outline-primary btn-sm w-100 rounded-3 py-2 fw-semibold">
                                    Apply for Leave &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tasks Row --}}
        <div class="row g-4 mb-4">
            {{-- My Tasks --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-check text-primary me-2"></i>My Active Tasks</h5>
                        <a href="{{ route('employee.tasks.index') }}" class="text-decoration-none text-primary small fw-semibold">View All &rarr;</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-dark" style="background-color: #0F172A;">
                                <tr>
                                    <th class="ps-4 py-3">Task Details</th>
                                    <th class="py-3">Project</th>
                                    <th class="py-3">Priority</th>
                                    <th class="pe-4 py-3">Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myTasks as $task)
                                    <tr class="hover-row">
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark">{{ $task->title }}</span>
                                            <small class="text-muted d-block">{{ Str::limit($task->description, 40) }}</small>
                                        </td>
                                        <td>
                                            <span class="text-secondary small fw-semibold">{{ $task->project->project_name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <x-status-badge :status="$task->priority" />
                                        </td>
                                        <td class="pe-4">
                                            <small class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-danger fw-bold' : 'text-secondary' }}">
                                                {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                            </small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-0">
                                            <x-empty-state title="No tasks assigned to you" icon="bi-check2-square" />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Recent Leaves History --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-primary me-2"></i>My Leave History</h5>
                        <a href="{{ route('employee.leaves.index') }}" class="text-decoration-none text-primary small fw-semibold">View All &rarr;</a>
                    </div>
                    <div class="list-group list-group-flush border-top border-light-subtle">
                        @forelse($myLeaves as $leave)
                            <div class="list-group-item px-4 py-3 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold text-dark">{{ $leave->leave_type }}</h6>
                                    <x-status-badge :status="$leave->status" />
                                </div>
                                <small class="text-secondary d-block">Dates: {{ $leave->start_date }} to {{ $leave->end_date }}</small>
                                @if($leave->admin_remarks)
                                    <small class="text-muted d-block mt-1"><em>Remarks: {{ $leave->admin_remarks }}</em></small>
                                @endif
                            </div>
                        @empty
                            <x-empty-state title="No leave requests found" icon="bi-calendar-x" />
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection