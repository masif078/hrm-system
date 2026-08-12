@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="container-fluid px-0">

    {{-- Welcome Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="fw-extrabold text-dark mb-1 tracking-tight" style="font-size: 1.85rem;">
                Welcome back, {{ Auth::user()->name }}! 👋
            </h1>
            <p class="text-secondary mb-0 small">Here's what's happening across your organization today.</p>
        </div>

        {{-- Date Indicator Pill --}}
        <div class="bg-white border border-light-subtle rounded-pill px-3.5 py-2 text-secondary small fw-medium shadow-2xs d-inline-flex align-items-center gap-2">
            <i class="bi bi-calendar3 text-primary"></i>
            <span>{{ today()->format('F d, Y') }}</span>
        </div>
    </div>

    {{-- Top 4 Stat Cards Row --}}
    <div class="row g-4 mb-4">
        {{-- Card 1: Employees --}}
        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Total Employees" 
                :value="$totalEmployees" 
                color="blue" 
                icon="bi-people-fill" 
                trend="{{ $activeEmployees }} Active" 
                trendType="up" 
                link="{{ route('employees.index') }}" 
            />
        </div>

        {{-- Card 2: Projects --}}
        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Active Projects" 
                :value="$activeProjectsCount" 
                color="green" 
                icon="bi-briefcase-fill" 
                trend="{{ $totalProjectsCount }} Total" 
                trendType="neutral" 
                link="{{ route('projects.index') }}" 
            />
        </div>

        {{-- Card 3: Pending Leaves --}}
        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Pending Leaves" 
                :value="$pendingLeavesCount" 
                color="amber" 
                icon="bi-calendar-event-fill" 
                trend="Needs Action" 
                trendType="down" 
                link="{{ route('leaves.index') }}?status=Pending" 
            />
        </div>

        {{-- Card 4: Departments --}}
        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Departments" 
                :value="$totalDepartments" 
                color="purple" 
                icon="bi-building-fill" 
                trend="{{ $totalDesignations }} Designations" 
                trendType="up" 
                link="{{ route('departments.index') }}" 
            />
        </div>
    </div>

    {{-- Payroll Stats Row --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <x-financial-card 
                title="Total Salary Paid" 
                value="PKR {{ number_format($totalPaid, 2) }}" 
                subtitle="This Month" 
                icon="bi-wallet2" 
                color="blue" 
            />
        </div>

        <div class="col-xl-4 col-md-6">
            <x-financial-card 
                title="Pending Payroll" 
                value="PKR {{ number_format($pendingPayroll, 2) }}" 
                subtitle="This Month" 
                icon="bi-file-earmark-text" 
                color="green" 
            />
        </div>

        <div class="col-xl-4 col-md-6">
            <x-financial-card 
                title="Current Month Payroll" 
                value="PKR {{ number_format($currentMonthPayroll, 2) }}" 
                subtitle="This Month" 
                icon="bi-cash-coin" 
                color="purple" 
            />
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <h5 class="fw-bold text-dark mb-3" style="font-size: 1.05rem;">Quick Actions</h5>
        <div class="row g-3">
            <div class="col-xl-3 col-md-6">
                <x-quick-action 
                    title="Add Employee" 
                    subtitle="Register new staff" 
                    icon="bi-person-plus-fill" 
                    color="blue" 
                    link="{{ route('employees.create') }}" 
                />
            </div>
            <div class="col-xl-3 col-md-6">
                <x-quick-action 
                    title="New Project" 
                    subtitle="Setup new project" 
                    icon="bi-folder-plus" 
                    color="green" 
                    link="{{ route('projects.create') }}" 
                />
            </div>
            <div class="col-xl-3 col-md-6">
                <x-quick-action 
                    title="Assign Task" 
                    subtitle="Create new task" 
                    icon="bi-check-circle-fill" 
                    color="orange" 
                    link="{{ route('tasks.create') }}" 
                />
            </div>
            <div class="col-xl-3 col-md-6">
                <x-quick-action 
                    title="View Reports" 
                    subtitle="Download reports" 
                    icon="bi-bar-chart-line-fill" 
                    color="purple" 
                    link="{{ route('reports.index') }}" 
                />
            </div>
        </div>
    </div>

    {{-- Recent Tasks & Recent Members Grid --}}
    <div class="row g-4">
        {{-- Recent Tasks --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Recent Tasks</h5>
                    <a href="{{ route('tasks.index') }}" class="text-decoration-none text-primary fw-semibold small">View All</a>
                </div>

                @if(isset($recentTasks) && $recentTasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr class="text-secondary small">
                                    <th>Task</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTasks as $task)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $task->title }}</td>
                                        <td class="text-secondary small">{{ $task->employee?->first_name }} {{ $task->employee?->last_name }}</td>
                                        <td><x-status-badge :status="$task->status" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-empty-state title="No tasks found." icon="bi-clipboard-x" />
                @endif
            </div>
        </div>

        {{-- Recent Members --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Recent Members</h5>
                    <a href="{{ route('employees.index') }}" class="text-decoration-none text-primary fw-semibold small">View All</a>
                </div>

                @if(isset($recentEmployees) && $recentEmployees->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr class="text-secondary small">
                                    <th>Member</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentEmployees as $emp)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <x-avatar :name="$emp->first_name . ' ' . $emp->last_name" size="sm" />
                                                <span class="fw-bold text-dark">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-secondary small">{{ $emp->department?->name ?? 'N/A' }}</td>
                                        <td><x-status-badge :status="$emp->status" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-empty-state title="No members found." icon="bi-person-x" />
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
