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

    <div id="alertContainer">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
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
                    link="#addEmployeeDrawer" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#addEmployeeDrawer" 
                />
            </div>
            <div class="col-xl-3 col-md-6">
                <x-quick-action 
                    title="New Project" 
                    subtitle="Setup new project" 
                    icon="bi-folder-plus" 
                    color="green" 
                    link="#createProjectModal" 
                    data-bs-toggle="modal" 
                    data-bs-target="#createProjectModal" 
                />
            </div>
            <div class="col-xl-3 col-md-6">
                <x-quick-action 
                    title="Assign Task" 
                    subtitle="Create new task" 
                    icon="bi-check-circle-fill" 
                    color="orange" 
                    link="#assignTaskModal" 
                    data-bs-toggle="modal" 
                    data-bs-target="#assignTaskModal" 
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
    <div class="row g-4 mb-4">
        {{-- Recent Tasks --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Recent Tasks</h5>
                    <a href="{{ route('tasks.index') }}" class="text-decoration-none text-primary fw-semibold small">View All</a>
                </div>

                @if(isset($recentTasks) && $recentTasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="recentTasksTable">
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
                        <table class="table align-middle mb-0" id="recentMembersTable">
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

{{-- 1. Add Employee Slide-over Right Drawer (480px-520px, Sticky Footer) --}}
<div class="offcanvas offcanvas-end border-0 shadow-2xl d-flex flex-column h-100" id="addEmployeeDrawer" tabindex="-1" aria-labelledby="addEmployeeDrawerLabel" style="width: 500px; max-width: 90vw; backdrop-filter: blur(4px);">
    {{-- Fixed Drawer Header --}}
    <div class="offcanvas-header border-bottom px-4 py-3 bg-white flex-shrink-0">
        <h5 class="offcanvas-title fw-bold text-dark d-flex align-items-center gap-2" id="addEmployeeDrawerLabel">
            <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-person-plus-fill fs-6"></i>
            </div>
            Register New Employee
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    {{-- Form Container --}}
    <form id="addEmployeeDrawerForm" action="{{ route('employees.store') }}" method="POST" class="d-flex flex-column flex-grow-1 overflow-hidden">
        @csrf

        {{-- Scrollable Drawer Body --}}
        <div class="offcanvas-body p-4 bg-white flex-grow-1" style="overflow-y: auto;">
            <div id="drawerEmployeeErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

            {{-- Section 1: Personal Details --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary border-bottom border-primary-subtle pb-2 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-person-badge-fill me-1"></i> Section 1: Personal Details
                </h6>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="drawer_employee_id" class="form-label fw-bold text-dark small">Employee ID / Code <span class="text-danger">*</span></label>
                        <input type="text" name="employee_id" id="drawer_employee_id" class="form-control rounded-3 border-light-subtle" placeholder="e.g. EMP-1001" required>
                    </div>

                    <div class="col-md-6">
                        <label for="drawer_first_name" class="form-label fw-bold text-dark small">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="drawer_first_name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Ali" required>
                    </div>
                    <div class="col-md-6">
                        <label for="drawer_last_name" class="form-label fw-bold text-dark small">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="drawer_last_name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Khan" required>
                    </div>

                    <div class="col-md-6">
                        <label for="drawer_email" class="form-label fw-bold text-dark small">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="drawer_email" class="form-control rounded-3 border-light-subtle" placeholder="e.g. ali.khan@company.com" required>
                    </div>

                    <div class="col-md-6">
                        <label for="drawer_phone" class="form-label fw-bold text-dark small">Phone Number</label>
                        <input type="text" name="phone" id="drawer_phone" class="form-control rounded-3 border-light-subtle" placeholder="e.g. +92 300 1234567">
                    </div>
                </div>
            </div>

            {{-- Section 2: Company & Job Info --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary border-bottom border-primary-subtle pb-2 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-building-fill me-1"></i> Section 2: Company & Job Info
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="drawer_department_id" class="form-label fw-bold text-dark small">Department <span class="text-danger">*</span></label>
                        <select name="department_id" id="drawer_department_id" class="form-select rounded-3 border-light-subtle" required>
                            <option value="">Select Department...</option>
                            @foreach($departments ?? [] as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="drawer_designation_id" class="form-label fw-bold text-dark small">Designation <span class="text-danger">*</span></label>
                        <select name="designation_id" id="drawer_designation_id" class="form-select rounded-3 border-light-subtle" required>
                            <option value="">Select Designation...</option>
                            @foreach($designations ?? [] as $desg)
                                <option value="{{ $desg->id }}">{{ $desg->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="drawer_joining_date" class="form-label fw-bold text-dark small">Joining Date <span class="text-danger">*</span></label>
                        <input type="date" name="joining_date" id="drawer_joining_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="drawer_status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
                        <select name="status" id="drawer_status" class="form-select rounded-3 border-light-subtle" required>
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="drawer_user_id" class="form-label fw-bold text-dark small">Link User Account (Optional)</label>
                        <select name="user_id" id="drawer_user_id" class="form-select rounded-3 border-light-subtle">
                            <option value="">-- Select Linked User --</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section 3: Financials & Compensation --}}
            <div class="mb-2">
                <h6 class="fw-bold text-primary border-bottom border-primary-subtle pb-2 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-cash-stack me-1"></i> Section 3: Financials & Compensation
                </h6>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="drawer_salary" class="form-label fw-bold text-dark small">Monthly Net Base Salary (PKR) <span class="text-danger">*</span></label>
                        <input type="number" name="salary" id="drawer_salary" step="0.01" class="form-control rounded-3 border-light-subtle" placeholder="e.g. 150000" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fixed Sticky Drawer Footer --}}
        <div class="offcanvas-footer border-top p-4 bg-white d-flex justify-content-end gap-2 flex-shrink-0" style="position: sticky; bottom: 0; z-index: 1055;">
            <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="offcanvas">Cancel</button>
            <button type="submit" id="saveEmployeeBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                <i class="bi bi-check-circle-fill me-1"></i> Save Employee
            </button>
        </div>
    </form>
</div>

{{-- 2. Create New Project Centered Modal Overlay (Green Theme) --}}
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="createProjectModalLabel">
                    <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-folder-plus fs-6"></i>
                    </div>
                    Create New Project
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="createProjectForm" action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalProjectErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="project_code" class="form-label fw-bold text-dark small">Project Code <span class="text-danger">*</span></label>
                            <input type="text" name="project_code" id="project_code" class="form-control rounded-3 border-light-subtle" placeholder="e.g. PRJ-101" required>
                        </div>

                        <div class="col-md-6">
                            <label for="project_name" class="form-label fw-bold text-dark small">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="project_name" id="project_name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Enterprise ERP Platform" required>
                        </div>

                        <div class="col-md-6">
                            <label for="project_client_id" class="form-label fw-bold text-dark small">Client Organization <span class="text-danger">*</span></label>
                            <select name="client_id" id="project_client_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Client...</option>
                                @foreach($clients ?? [] as $client)
                                    <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="project_manager_id" class="form-label fw-bold text-dark small">Project Manager <span class="text-danger">*</span></label>
                            <select name="project_manager_id" id="project_manager_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Manager...</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="project_start_date" class="form-label fw-bold text-dark small">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="project_start_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="project_end_date" class="form-label fw-bold text-dark small">End Date / Deadline</label>
                            <input type="date" name="end_date" id="project_end_date" class="form-control rounded-3 border-light-subtle">
                        </div>

                        <div class="col-md-6">
                            <label for="project_budget" class="form-label fw-bold text-dark small">Total Budget (PKR) <span class="text-danger">*</span></label>
                            <input type="number" name="budget" id="project_budget" step="0.01" class="form-control rounded-3 border-light-subtle" placeholder="e.g. 500000" required>
                        </div>

                        <div class="col-md-6">
                            <label for="project_status" class="form-label fw-bold text-dark small">Project Status <span class="text-danger">*</span></label>
                            <select name="status" id="project_status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="In Progress" selected>In Progress</option>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                                <option value="On Hold">On Hold</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="project_description" class="form-label fw-bold text-dark small">Project Overview / Description</label>
                            <textarea name="description" id="project_description" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="Enter key deliverables and project scope..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveProjectBtn" class="btn btn-success rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Create Project
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- 3. Assign Task Centered Modal Overlay (Orange Theme) --}}
<div class="modal fade" id="assignTaskModal" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="assignTaskModalLabel">
                    <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-check-circle-fill fs-6"></i>
                    </div>
                    Assign New Task
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="assignTaskForm" action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalTaskErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="task_title" class="form-label fw-bold text-dark small">Task Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="task_title" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Design Homepage Wireframes & Assets" required>
                        </div>

                        <div class="col-md-6">
                            <label for="task_project_id" class="form-label fw-bold text-dark small">Project <span class="text-danger">*</span></label>
                            <select name="project_id" id="task_project_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Project...</option>
                                @foreach($projects ?? [] as $project)
                                    <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="task_employee_id" class="form-label fw-bold text-dark small">Assigned Staff / Employee</label>
                            <select name="employee_id" id="task_employee_id" class="form-select rounded-3 border-light-subtle">
                                <option value="">Leave Unassigned</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="task_priority" class="form-label fw-bold text-dark small">Priority <span class="text-danger">*</span></label>
                            <select name="priority" id="task_priority" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="task_status" class="form-label fw-bold text-dark small">Initial Status <span class="text-danger">*</span></label>
                            <select name="status" id="task_status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="To Do" selected>To Do</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Doing">Doing</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="task_due_date" class="form-label fw-bold text-dark small">Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" id="task_due_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-12">
                            <label for="task_description" class="form-label fw-bold text-dark small">Task Instructions & Description</label>
                            <textarea name="description" id="task_description" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="Enter task expectations and criteria..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveTaskBtn" class="btn rounded-3 px-4 py-2 fw-bold text-white shadow-sm" style="background-color: #F97316; border-color: #F97316;">
                        <i class="bi bi-check-circle-fill me-1"></i> Assign Task
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addEmployeeDrawerForm = document.getElementById('addEmployeeDrawerForm');
    const saveEmployeeBtn = document.getElementById('saveEmployeeBtn');
    const drawerEmployeeErrors = document.getElementById('drawerEmployeeErrors');
    const drawerDepartmentSelect = document.getElementById('drawer_department_id');
    const drawerDesignationSelect = document.getElementById('drawer_designation_id');
    const alertContainer = document.getElementById('alertContainer');

    const createProjectForm = document.getElementById('createProjectForm');
    const saveProjectBtn = document.getElementById('saveProjectBtn');
    const modalProjectErrors = document.getElementById('modalProjectErrors');

    const assignTaskForm = document.getElementById('assignTaskForm');
    const saveTaskBtn = document.getElementById('saveTaskBtn');
    const modalTaskErrors = document.getElementById('modalTaskErrors');

    // Dynamic Designation loading on Department change
    if (drawerDepartmentSelect) {
        drawerDepartmentSelect.addEventListener('change', function () {
            const deptId = this.value;
            if (!deptId) return;

            drawerDesignationSelect.innerHTML = '<option value="">Loading...</option>';

            fetch('/departments/' + deptId + '/designations', {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(response => response.json())
            .then(data => {
                drawerDesignationSelect.innerHTML = '<option value="">Select Designation...</option>';
                data.forEach(function(item){
                    drawerDesignationSelect.innerHTML += `<option value="${item.id}">${item.title}</option>`;
                });
            })
            .catch(() => {
                drawerDesignationSelect.innerHTML = '<option value="">Select Designation...</option>';
            });
        });
    }

    // Submit Drawer Form via AJAX (Employee)
    if (addEmployeeDrawerForm) {
        addEmployeeDrawerForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveEmployeeBtn.disabled = true;
            saveEmployeeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            drawerEmployeeErrors.classList.add('d-none');
            drawerEmployeeErrors.innerHTML = '';

            const formData = new FormData(addEmployeeDrawerForm);

            fetch("{{ route('employees.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveEmployeeBtn.disabled = false;
                saveEmployeeBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Employee';

                if (data.success) {
                    const drawerEl = document.getElementById('addEmployeeDrawer');
                    const drawerInstance = bootstrap.Offcanvas.getInstance(drawerEl);
                    if (drawerInstance) {
                        drawerInstance.hide();
                    }

                    addEmployeeDrawerForm.reset();
                    document.getElementById('drawer_joining_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('drawer_status').value = "Active";

                    alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i> ${data.message}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                    const recentMembersTable = document.querySelector('#recentMembersTable tbody');
                    if (recentMembersTable && data.employee) {
                        const emp = data.employee;
                        const initialChar = emp.first_name ? emp.first_name.charAt(0).toUpperCase() : 'E';
                        const deptName = emp.department ? emp.department.name : 'N/A';

                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        ${initialChar}
                                    </div>
                                    <span class="fw-bold text-dark">${emp.first_name} ${emp.last_name}</span>
                                </div>
                            </td>
                            <td class="text-secondary small">${deptName}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold fs-7">
                                    ${emp.status}
                                </span>
                            </td>
                        `;
                        recentMembersTable.prepend(newRow);
                    }
                } else {
                    let errHtml = '<ul class="mb-0 ps-3">';
                    if (data.errors) {
                        Object.values(data.errors).forEach(errArray => {
                            errArray.forEach(err => {
                                errHtml += `<li>${err}</li>`;
                            });
                        });
                    } else if (data.message) {
                        errHtml += `<li>${data.message}</li>`;
                    }
                    errHtml += '</ul>';
                    drawerEmployeeErrors.innerHTML = errHtml;
                    drawerEmployeeErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveEmployeeBtn.disabled = false;
                saveEmployeeBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Employee';
                drawerEmployeeErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                drawerEmployeeErrors.classList.remove('d-none');
            });
        });
    }

    // Submit Create Project Form via AJAX
    if (createProjectForm) {
        createProjectForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveProjectBtn.disabled = true;
            saveProjectBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalProjectErrors.classList.add('d-none');
            modalProjectErrors.innerHTML = '';

            const formData = new FormData(createProjectForm);

            fetch("{{ route('projects.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveProjectBtn.disabled = false;
                saveProjectBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Create Project';

                if (data.success) {
                    const modalEl = document.getElementById('createProjectModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    createProjectForm.reset();
                    document.getElementById('project_start_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('project_status').value = "In Progress";

                    alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i> ${data.message}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                } else {
                    let errHtml = '<ul class="mb-0 ps-3">';
                    if (data.errors) {
                        Object.values(data.errors).forEach(errArray => {
                            errArray.forEach(err => {
                                errHtml += `<li>${err}</li>`;
                            });
                        });
                    } else if (data.message) {
                        errHtml += `<li>${data.message}</li>`;
                    }
                    errHtml += '</ul>';
                    modalProjectErrors.innerHTML = errHtml;
                    modalProjectErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveProjectBtn.disabled = false;
                saveProjectBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Create Project';
                modalProjectErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalProjectErrors.classList.remove('d-none');
            });
        });
    }

    // Submit Assign Task Form via AJAX
    if (assignTaskForm) {
        assignTaskForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveTaskBtn.disabled = true;
            saveTaskBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalTaskErrors.classList.add('d-none');
            modalTaskErrors.innerHTML = '';

            const formData = new FormData(assignTaskForm);

            fetch("{{ route('tasks.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveTaskBtn.disabled = false;
                saveTaskBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Assign Task';

                if (data.success) {
                    const modalEl = document.getElementById('assignTaskModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    assignTaskForm.reset();
                    document.getElementById('task_due_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('task_priority').value = "Medium";
                    document.getElementById('task_status').value = "To Do";

                    alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i> ${data.message}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                    const recentTasksTable = document.querySelector('#recentTasksTable tbody');
                    if (recentTasksTable && data.task) {
                        const t = data.task;
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td class="fw-bold text-dark">${t.title}</td>
                            <td class="text-secondary small">${t.employee_name || 'Unassigned'}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fw-bold fs-7">
                                    ${t.status}
                                </span>
                            </td>
                        `;
                        recentTasksTable.prepend(newRow);
                    }
                } else {
                    let errHtml = '<ul class="mb-0 ps-3">';
                    if (data.errors) {
                        Object.values(data.errors).forEach(errArray => {
                            errArray.forEach(err => {
                                errHtml += `<li>${err}</li>`;
                            });
                        });
                    } else if (data.message) {
                        errHtml += `<li>${data.message}</li>`;
                    }
                    errHtml += '</ul>';
                    modalTaskErrors.innerHTML = errHtml;
                    modalTaskErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveTaskBtn.disabled = false;
                saveTaskBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Assign Task';
                modalTaskErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalTaskErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
