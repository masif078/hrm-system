@extends('layouts.app')

@section('title', 'Goals Tracking')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Goals Tracking']
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
                <h3 class="fw-bold text-dark mb-1">Goals Tracking</h3>
                <p class="text-secondary small mb-0">Define, track, and monitor employee key performance indicators and target goals.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addGoalModal">
                <i class="bi bi-plus-lg me-1"></i> Add New Goal
            </button>
        </div>
    </div>

    {{-- Filter Card for Admin --}}
    @if(auth()->user()->role === 'admin')
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-secondary mb-1">Filter Employee</label>
                    <select name="employee_id" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Employees</option>
                        @foreach($employees ?? [] as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-'.$emp->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">Status</label>
                    <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold text-white shadow-sm py-2">Filter Goals</button>
                    @if(request('employee_id') || request('status'))
                        <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold py-2">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    @endif

    {{-- Goals Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="goalsTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        @if(auth()->user()->role === 'admin')
                            <th class="ps-4 py-3">Employee</th>
                        @else
                            <th class="ps-4 py-3">ID</th>
                        @endif
                        <th class="py-3">Goal Title & Criteria</th>
                        <th class="py-3">Target Date</th>
                        <th class="py-3" width="220">Progress</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 text-end py-3" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($goals as $goal)
                        @php
                            $prog = intval($goal->progress);
                            $barColor = $prog > 70 ? 'bg-success' : ($prog >= 30 ? 'bg-warning' : 'bg-primary');
                        @endphp
                        <tr class="hover-row" id="goalRow_{{ $goal->id }}">
                            @if(auth()->user()->role === 'admin')
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <x-avatar :name="($goal->employee->first_name ?? '') . ' ' . ($goal->employee->last_name ?? '')" size="sm" />
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $goal->employee->first_name ?? '' }} {{ $goal->employee->last_name ?? '' }}</span>
                                            <small class="text-secondary opacity-75 d-block">ID: #{{ $goal->employee->employee_id ?? $goal->employee_id }}</small>
                                        </div>
                                    </div>
                                </td>
                            @else
                                <td class="ps-4 fw-bold text-secondary">#{{ $goal->id }}</td>
                            @endif

                            <td>
                                <span class="fw-bold text-dark d-block mb-0.5">{{ $goal->title }}</span>
                                @if($goal->description)
                                    <small class="text-secondary opacity-75 d-block text-truncate" style="max-width: 320px;">{{ $goal->description }}</small>
                                @endif
                            </td>

                            <td class="text-secondary small fw-medium">
                                {{ \Carbon\Carbon::parse($goal->target_date)->format('M d, Y') }}
                            </td>

                            {{-- Modern Progress Bar Component --}}
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="progress flex-grow-1 rounded-pill bg-light-subtle border border-light-subtle" style="height: 10px;">
                                        <div class="progress-bar {{ $barColor }} rounded-pill" role="progressbar" style="width: {{ $prog }}%;" aria-valuenow="{{ $prog }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fw-bold small text-dark" style="min-width: 36px;">{{ $prog }}%</span>
                                </div>
                            </td>

                            {{-- Status Badge Component --}}
                            <td>
                                @if($goal->status === 'Completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Completed</span>
                                @elseif($goal->status === 'In Progress')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">In Progress</span>
                                @elseif($goal->status === 'Cancelled')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Pending</span>
                                @endif
                            </td>

                            <td class="pe-4 text-end">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                    <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-action-edit" title="Edit Goal" aria-label="Edit Goal">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this goal?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete" title="Delete Goal" aria-label="Delete Goal">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noGoalsRow">
                            <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="p-0">
                                <x-empty-state title="No Goals Tracked Yet" icon="bi-bullseye" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($goals->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $goals->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Add New Goal Centered Modal Overlay --}}
<div class="modal fade" id="addGoalModal" tabindex="-1" aria-labelledby="addGoalModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="addGoalModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-bullseye fs-6"></i>
                    </div>
                    Add New Goal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="addGoalForm" action="{{ route('goals.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalGoalErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Employee Dropdown --}}
                        <div class="col-12">
                            <label for="employee_id" class="form-label fw-bold text-dark small">Employee <span class="text-danger">*</span></label>
                            @if(auth()->user()->role === 'admin')
                                <select name="employee_id" id="employee_id" class="form-select rounded-3 border-light-subtle" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees ?? [] as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-'.$emp->id }})</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id ?? '' }}">
                                <input type="text" class="form-control rounded-3 border-light-subtle bg-light text-dark fw-bold" value="{{ auth()->user()->name }}" readonly>
                            @endif
                        </div>

                        {{-- Goal Title --}}
                        <div class="col-12">
                            <label for="goal_title" class="form-label fw-bold text-dark small">Goal Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="goal_title" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Redesign Login Page, Finish Accounting Report" required>
                        </div>

                        {{-- Description / Criteria --}}
                        <div class="col-12">
                            <label for="goal_description" class="form-label fw-bold text-dark small">Description / Criteria</label>
                            <textarea name="description" id="goal_description" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="Define success criteria for this goal"></textarea>
                        </div>

                        {{-- Target Completion Date --}}
                        <div class="col-md-6">
                            <label for="target_date" class="form-label fw-bold text-dark small">Target Completion Date <span class="text-danger">*</span></label>
                            <input type="date" name="target_date" id="target_date" class="form-control rounded-3 border-light-subtle" placeholder="mm/dd/yyyy" required>
                        </div>

                        {{-- Initial Progress (%) --}}
                        <div class="col-md-3">
                            <label for="progress" class="form-label fw-bold text-dark small">Initial Progress (%) <span class="text-danger">*</span></label>
                            <input type="number" name="progress" id="progress" class="form-control rounded-3 border-light-subtle" value="0" min="0" max="100" required>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-3">
                            <label for="status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Pending" selected>Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveGoalBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Goal
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addGoalForm = document.getElementById('addGoalForm');
    const saveGoalBtn = document.getElementById('saveGoalBtn');
    const modalGoalErrors = document.getElementById('modalGoalErrors');
    const goalsTableBody = document.querySelector('#goalsTable tbody');
    const alertContainer = document.getElementById('alertContainer');
    const isAdmin = {{ auth()->user()->role === 'admin' ? 'true' : 'false' }};

    if (addGoalForm) {
        addGoalForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveGoalBtn.disabled = true;
            saveGoalBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalGoalErrors.classList.add('d-none');
            modalGoalErrors.innerHTML = '';

            const formData = new FormData(addGoalForm);

            fetch("{{ route('goals.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveGoalBtn.disabled = false;
                saveGoalBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Goal';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('addGoalModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    addGoalForm.reset();
                    document.getElementById('progress').value = "0";
                    document.getElementById('status').value = "Pending";

                    // Remove empty state row if present
                    const noGoalsRow = document.getElementById('noGoalsRow');
                    if (noGoalsRow) {
                        noGoalsRow.remove();
                    }

                    // Prepend new row
                    const g = data.goal;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const prog = parseInt(g.progress) || 0;
                    let barColor = 'bg-primary';
                    if (prog > 70) barColor = 'bg-success';
                    else if (prog >= 30) barColor = 'bg-warning';

                    let statusBadgeClass = 'bg-secondary-subtle text-secondary border border-secondary-subtle';
                    if (g.status === 'Completed') statusBadgeClass = 'bg-success-subtle text-success border border-success-subtle';
                    else if (g.status === 'In Progress') statusBadgeClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                    else if (g.status === 'Cancelled') statusBadgeClass = 'bg-danger-subtle text-danger border border-danger-subtle';

                    let empTd = '';
                    if (isAdmin) {
                        empTd = `
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                        ${g.employee_name.charAt(0)}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">${g.employee_name}</span>
                                        <small class="text-secondary opacity-75 d-block">ID: #${g.employee_code}</small>
                                    </div>
                                </div>
                            </td>
                        `;
                    } else {
                        empTd = `<td class="ps-4 fw-bold text-secondary">#${g.id}</td>`;
                    }

                    let deleteBtnHtml = '';
                    if (isAdmin) {
                        deleteBtnHtml = `
                            <form action="${g.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this goal?')">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-action-delete" title="Delete Goal" aria-label="Delete Goal">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        `;
                    }

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'goalRow_' + g.id;
                    newRow.innerHTML = `
                        ${empTd}
                        <td>
                            <span class="fw-bold text-dark d-block mb-0.5">${g.title}</span>
                            ${g.description ? `<small class="text-secondary opacity-75 d-block text-truncate" style="max-width: 320px;">${g.description}</small>` : ''}
                        </td>
                        <td class="text-secondary small fw-medium">${g.target_date}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="progress flex-grow-1 rounded-pill bg-light-subtle border border-light-subtle" style="height: 10px;">
                                    <div class="progress-bar ${barColor} rounded-pill" role="progressbar" style="width: ${prog}%;" aria-valuenow="${prog}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="fw-bold small text-dark" style="min-width: 36px;">${prog}%</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge ${statusBadgeClass} rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                ${g.status}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                <a href="${g.edit_url}" class="btn btn-action-edit" title="Edit Goal" aria-label="Edit Goal">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                ${deleteBtnHtml}
                            </div>
                        </td>
                    `;
                    goalsTableBody.prepend(newRow);

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
                    modalGoalErrors.innerHTML = errHtml;
                    modalGoalErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveGoalBtn.disabled = false;
                saveGoalBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Goal';
                modalGoalErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalGoalErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
