@extends('layouts.app')

@section('title', 'Tasks Management')

@section('content')

<style>
.stat-card-hover {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.stat-card-hover:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1.25rem rgba(15, 23, 42, 0.08) !important;
}
</style>

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Tasks Management']
    ]" />

    <div id="alertContainer">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Task Management</h3>
                <p class="text-secondary small mb-0">Track project tasks, task priorities, employee assignments, and completion status.</p>
            </div>
            @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Task
                </button>
            @endif
        </div>
    </div>

    {{-- Stat Cards Row with Hover Effects --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-hover rounded-4">
                <x-stat-card 
                    title="Total Tasks" 
                    :value="$tasks->total()" 
                    color="blue" 
                    icon="bi-check2-square" 
                    trend="All Tasks" 
                    trendType="neutral" 
                />
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card-hover rounded-4">
                <x-stat-card 
                    title="To Do Tasks" 
                    :value="\App\Models\Task::where('status','To Do')->count()" 
                    color="amber" 
                    icon="bi-list-task" 
                    trend="Pending" 
                    trendType="down" 
                />
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card-hover rounded-4">
                <x-stat-card 
                    title="Completed" 
                    :value="\App\Models\Task::where('status','Completed')->count()" 
                    color="green" 
                    icon="bi-check-circle-fill" 
                    trend="Finished" 
                    trendType="up" 
                />
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card-hover rounded-4">
                <x-stat-card 
                    title="High Priority" 
                    :value="\App\Models\Task::where('priority','High')->count()" 
                    color="purple" 
                    icon="bi-exclamation-triangle-fill" 
                    trend="Urgent" 
                    trendType="down" 
                />
            </div>
        </div>
    </div>

    {{-- Search & Filters Card (Horizontal Clean Row) --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-3 align-items-end">
            {{-- Search Input --}}
            <div class="{{ auth()->user()->role === 'admin' ? 'col-md-3' : 'col-md-4' }}">
                <label class="form-label small fw-semibold text-secondary mb-1">Search Task</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-light-subtle text-muted rounded-start-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control rounded-end-3 border-light-subtle shadow-2xs"
                        placeholder="Search by title...">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">Status</label>
                <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs">
                    <option value="">All Status</option>
                    <option value="To Do" {{ request('status') == 'To Do' ? 'selected' : '' }}>To Do</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Doing" {{ request('status') == 'Doing' ? 'selected' : '' }}>Doing</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            {{-- Priority Filter --}}
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">Priority</label>
                <select name="priority" class="form-select rounded-3 border-light-subtle shadow-2xs">
                    <option value="">All Priority</option>
                    <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            {{-- Employee Filter (Admin Only) --}}
            @if(auth()->user()->role === 'admin')
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Employee</label>
                    <select name="employee_id" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Employees</option>
                        <option value="unassigned" {{ request('employee_id') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Filter & Reset Buttons --}}
            <div class="{{ auth()->user()->role === 'admin' ? 'col-md-2' : 'col-md-3' }} d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold py-2 text-white shadow-sm d-flex align-items-center justify-content-center gap-1.5">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold py-2 px-3">Reset</a>
            </div>
        </form>
    </div>

    {{-- Tasks Table Card (No Horizontal Scrollbar, Compact SaaS Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="tasksTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">ID</th>
                        <th class="px-2 py-2.5">Title</th>
                        <th class="px-2 py-2.5">Project</th>
                        <th class="px-2 py-2.5">Employee</th>
                        <th class="px-2 py-2.5">Priority</th>
                        <th class="px-2 py-2.5">Status</th>
                        <th class="px-2 py-2.5">Due Date</th>
                        <th class="pe-3 text-end py-2.5" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr class="hover-row" id="taskRow_{{ $task->id }}">
                            {{-- Task ID --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-3 px-2 py-1 fw-bold" style="font-size: 0.725rem;">
                                    #{{ $task->id }}
                                </span>
                            </td>

                            {{-- Task Title --}}
                            <td class="px-2 py-2.5 align-middle">
                                <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">{{ $task->title }}</span>
                            </td>

                            {{-- Project --}}
                            <td class="px-2 py-2.5 text-secondary small fw-medium align-middle">
                                {{ $task->project->project_name ?? 'N/A' }}
                            </td>

                            {{-- Employee --}}
                            <td class="px-2 py-2.5 align-middle">
                                @if($task->employee)
                                    <div class="d-flex align-items-center gap-2">
                                        <x-avatar :name="$task->employee->first_name . ' ' . $task->employee->last_name" size="sm" class="flex-shrink-0" />
                                        <span class="small fw-semibold text-dark text-nowrap" style="white-space: nowrap;">{{ $task->employee->first_name }} {{ $task->employee->last_name }}</span>
                                    </div>
                                @else
                                    <span class="badge bg-light text-secondary border border-light-subtle rounded-pill px-2.5 py-1">Unassigned</span>
                                @endif
                            </td>

                            {{-- Priority Pill Badge --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @php
                                    $prio = $task->priority;
                                    $prioClass = 'bg-info-subtle text-info border border-info-subtle';
                                    if ($prio === 'High') {
                                        $prioClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                    } elseif ($prio === 'Medium') {
                                        $prioClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                                    }
                                @endphp
                                <span class="badge {{ $prioClass }} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                    {{ $task->priority }}
                                </span>
                            </td>

                            {{-- Status Pill Badge --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @php
                                    $st = $task->status;
                                    $statusClass = 'bg-secondary-subtle text-secondary border border-secondary-subtle';
                                    if ($st === 'Completed') {
                                        $statusClass = 'bg-success-subtle text-success border border-success-subtle';
                                    } elseif (in_array($st, ['In Progress', 'Doing'])) {
                                        $statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                                    } elseif ($st === 'To Do') {
                                        $statusClass = 'bg-info-subtle text-info border border-info-subtle';
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                    {{ $task->status }}
                                </span>
                            </td>

                            {{-- Due Date --}}
                            <td class="px-2 py-2.5 text-secondary small align-middle text-nowrap" style="white-space: nowrap;">
                                @if(\Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'Completed')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                        <i class="bi bi-clock-history me-0.5"></i> {{ date('M d, Y', strtotime($task->due_date)) }}
                                    </span>
                                @else
                                    {{ date('M d, Y', strtotime($task->due_date)) }}
                                @endif
                            </td>

                            {{-- Action Icons (12px Gap, Vertically Centered) --}}
                            <td class="pe-3 py-2.5 text-end align-middle">
                                @if(auth()->user()->role === 'admin')
                                    <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-action-view" title="View Task" aria-label="View Task">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-action-edit" title="Edit Task" aria-label="Edit Task">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete" title="Delete Task" aria-label="Delete Task">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                @elseif(auth()->user()->role === 'employee')
                                    <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-action-view" title="View Task" aria-label="View Task">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm rounded-3 border-light-subtle" style="width: auto; font-size: 0.75rem;">
                                                <option value="To Do" {{ $task->status === 'To Do' ? 'selected' : '' }}>To Do</option>
                                                <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="Doing" {{ $task->status === 'Doing' ? 'selected' : '' }}>Doing</option>
                                                <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </form>
                                    </div>
                                @else
                                    <x-status-badge :status="$task->status" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="noTasksRow">
                            <td colspan="8" class="p-0">
                                <x-empty-state title="No Tasks Found" icon="bi-check2-square" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tasks->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $tasks->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Add Task Centered Modal Overlay --}}
@if(auth()->user()->role === 'admin')
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="addTaskModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-check2-square fs-6"></i>
                    </div>
                    Add Task
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="addTaskForm" action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalTaskErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Task Title --}}
                        <div class="col-12">
                            <label for="title" class="form-label fw-bold text-dark small">Task Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Redesign Login Page, Finish Accounting Report" required>
                        </div>

                        {{-- Project Dropdown --}}
                        <div class="col-md-6">
                            <label for="project_id" class="form-label fw-bold text-dark small">Project <span class="text-danger">*</span></label>
                            <select name="project_id" id="project_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Project...</option>
                                @foreach($projects ?? [] as $proj)
                                    <option value="{{ $proj->id }}">{{ $proj->project_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Employee Dropdown --}}
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-bold text-dark small">Assigned Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select rounded-3 border-light-subtle">
                                <option value="">Leave Unassigned</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Priority --}}
                        <div class="col-md-4">
                            <label for="priority" class="form-label fw-bold text-dark small">Priority <span class="text-danger">*</span></label>
                            <select name="priority" id="priority" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="To Do" selected>To Do</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        {{-- Due Date --}}
                        <div class="col-md-4">
                            <label for="due_date" class="form-label fw-bold text-dark small">Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" id="due_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-bold text-dark small">Description / Criteria</label>
                            <textarea name="description" id="description" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="Define task details, requirements, or success criteria..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveTaskBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Task
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addTaskForm = document.getElementById('addTaskForm');
    const saveTaskBtn = document.getElementById('saveTaskBtn');
    const modalTaskErrors = document.getElementById('modalTaskErrors');
    const tasksTableBody = document.querySelector('#tasksTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (addTaskForm) {
        addTaskForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveTaskBtn.disabled = true;
            saveTaskBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalTaskErrors.classList.add('d-none');
            modalTaskErrors.innerHTML = '';

            const formData = new FormData(addTaskForm);

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
                saveTaskBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Task';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('addTaskModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    addTaskForm.reset();
                    document.getElementById('due_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('priority').value = "Medium";
                    document.getElementById('status').value = "To Do";

                    // Remove empty state row if present
                    const noTasksRow = document.getElementById('noTasksRow');
                    if (noTasksRow) {
                        noTasksRow.remove();
                    }

                    // Prepend new row
                    const t = data.task;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let prioClass = 'bg-info-subtle text-info border border-info-subtle';
                    if (t.priority === 'High') prioClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                    else if (t.priority === 'Medium') prioClass = 'bg-warning-subtle text-warning border border-warning-subtle';

                    let statusClass = 'bg-secondary-subtle text-secondary border border-secondary-subtle';
                    if (t.status === 'Completed') statusClass = 'bg-success-subtle text-success border border-success-subtle';
                    else if (['In Progress', 'Doing'].includes(t.status)) statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                    else if (t.status === 'To Do') statusClass = 'bg-info-subtle text-info border border-info-subtle';

                    let empHtml = '<span class="badge bg-light text-secondary border border-light-subtle rounded-pill px-2.5 py-1">Unassigned</span>';
                    if (t.employee_name) {
                        empHtml = `
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${t.employee_name.charAt(0)}
                                </div>
                                <span class="small fw-semibold text-dark text-nowrap" style="white-space: nowrap;">${t.employee_name}</span>
                            </div>
                        `;
                    }

                    let dateHtml = t.due_date;
                    if (t.is_overdue) {
                        dateHtml = `<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;"><i class="bi bi-clock-history me-0.5"></i> ${t.due_date}</span>`;
                    }

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'taskRow_' + t.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-3 px-2 py-1 fw-bold" style="font-size: 0.725rem;">
                                #${t.id}
                            </span>
                        </td>
                        <td class="px-2 py-2.5 align-middle">
                            <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">${t.title}</span>
                        </td>
                        <td class="px-2 py-2.5 text-secondary small fw-medium align-middle">${t.project_name}</td>
                        <td class="px-2 py-2.5 align-middle">${empHtml}</td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge ${prioClass} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${t.priority}
                            </span>
                        </td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge ${statusClass} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${t.status}
                            </span>
                        </td>
                        <td class="px-2 py-2.5 text-secondary small align-middle text-nowrap" style="white-space: nowrap;">${dateHtml}</td>
                        <td class="pe-3 py-2.5 text-end align-middle">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                <a href="${t.show_url}" class="btn btn-action-view" title="View Task" aria-label="View Task">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="${t.edit_url}" class="btn btn-action-edit" title="Edit Task" aria-label="Edit Task">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${t.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Task" aria-label="Delete Task">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    tasksTableBody.prepend(newRow);

                    // Show success alert
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
                    modalTaskErrors.innerHTML = errHtml;
                    modalTaskErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveTaskBtn.disabled = false;
                saveTaskBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Task';
                modalTaskErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalTaskErrors.classList.remove('d-none');
            });
        });
    }
});
</script>
@endif

@endsection
