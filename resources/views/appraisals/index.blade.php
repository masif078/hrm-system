@extends('layouts.app')

@section('title', 'Appraisals & Increments')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Appraisals & Salary Increments']
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
                <h3 class="fw-bold text-dark mb-1">Appraisals & Salary Increments</h3>
                <p class="text-secondary small mb-0">Record, approve, and track employee designation promotions and salary increments.</p>
            </div>
            @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-success fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createAppraisalModal">
                    <i class="bi bi-plus-lg me-1"></i> Create Salary/Promotion Appraisal
                </button>
            @endif
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
                        <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft / Pending</option>
                        <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold text-white shadow-sm py-2">Filter Appraisals</button>
                    @if(request('employee_id') || request('status'))
                        <a href="{{ route('appraisals.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold py-2">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    @endif

    {{-- Appraisals Table Card (No Horizontal Scrollbar, Compact Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="appraisalsTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">Employee</th>
                        <th class="px-2 py-2.5">Type</th>
                        <th class="px-2 py-2.5">Current Salary</th>
                        <th class="px-2 py-2.5">New Salary</th>
                        <th class="px-2 py-2.5">Designation Promotion</th>
                        <th class="px-2 py-2.5">Effective Date</th>
                        <th class="px-2 py-2.5">Status</th>
                        <th class="pe-3 text-end py-2.5" width="130">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appraisals as $appraisal)
                        <tr class="hover-row" id="appraisalRow_{{ $appraisal->id }}">
                            {{-- Employee Column --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <div class="d-flex align-items-center gap-3">
                                    <x-avatar :name="($appraisal->employee->first_name ?? '') . ' ' . ($appraisal->employee->last_name ?? '')" size="sm" class="flex-shrink-0" />
                                    <div>
                                        <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">{{ $appraisal->employee->first_name ?? '' }} {{ $appraisal->employee->last_name ?? '' }}</span>
                                        <small class="text-secondary opacity-75 d-block" style="font-size: 0.725rem;">ID: #{{ $appraisal->employee->employee_id ?? $appraisal->employee_id }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Action Type --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.725rem;">
                                    {{ $appraisal->action_type }}
                                </span>
                            </td>

                            {{-- Current Salary --}}
                            <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                PKR {{ number_format($appraisal->previous_salary, 2) }}
                            </td>

                            {{-- New Salary --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @if($appraisal->new_salary > $appraisal->previous_salary)
                                    <span class="text-success fw-bold">PKR {{ number_format($appraisal->new_salary, 2) }}</span>
                                @else
                                    <span class="fw-bold text-dark">PKR {{ number_format($appraisal->new_salary, 2) }}</span>
                                @endif
                            </td>

                            {{-- Compact Vertical Designation Promotion Display --}}
                            <td class="px-2 py-2.5 align-middle">
                                @if($appraisal->newDesignation)
                                    <div class="d-flex flex-column align-items-start" style="gap: 2px;">
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-0.5 fw-normal text-truncate" style="font-size: 0.7rem; max-width: 160px;" title="{{ $appraisal->previousDesignation?->title ?? $appraisal->previousDesignation?->name ?? 'Current' }}">
                                            {{ $appraisal->previousDesignation?->title ?? $appraisal->previousDesignation?->name ?? 'Current' }}
                                        </span>
                                        <div class="ps-3 my-0" style="line-height: 1;">
                                            <i class="bi bi-arrow-down text-primary opacity-75" style="font-size: 0.7rem;"></i>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 fw-bold text-truncate" style="font-size: 0.7rem; max-width: 160px;" title="{{ $appraisal->newDesignation->title ?? $appraisal->newDesignation->name }}">
                                            {{ $appraisal->newDesignation->title ?? $appraisal->newDesignation->name }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-secondary opacity-50 small">None</span>
                                @endif
                            </td>

                            {{-- Effective Date --}}
                            <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($appraisal->effective_date)->format('M d, Y') }}
                            </td>

                            {{-- Status Badges --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @if($appraisal->status === 'Approved')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">Approved</span>
                                @elseif($appraisal->status === 'Rejected')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">Rejected</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">Pending</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="pe-3 py-2.5 text-end align-middle">
                                <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('appraisals.show', $appraisal->id) }}" class="btn btn-action-view" title="View Appraisal" aria-label="View Appraisal">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('appraisals.edit', $appraisal->id) }}" class="btn btn-action-edit" title="Edit Appraisal" aria-label="Edit Appraisal">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('appraisals.destroy', $appraisal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this appraisal?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete" title="Delete Appraisal" aria-label="Delete Appraisal">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noAppraisalsRow">
                            <td colspan="8" class="p-0">
                                <x-empty-state title="No Appraisals Recorded" icon="bi-graph-up-arrow" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appraisals->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $appraisals->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Create Appraisal Centered Modal Overlay --}}
<div class="modal fade" id="createAppraisalModal" tabindex="-1" aria-labelledby="createAppraisalModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="createAppraisalModalLabel">
                    <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-graph-up-arrow fs-6"></i>
                    </div>
                    Create Salary / Promotion Appraisal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="createAppraisalForm" action="{{ route('appraisals.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalAppraisalErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Employee Selection --}}
                        <div class="col-md-6">
                            <label for="modal_employee_id" class="form-label fw-bold text-dark small">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="modal_employee_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Employee...</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}" 
                                            data-salary="{{ $emp->salary ?? 0 }}" 
                                            data-designation-id="{{ $emp->designation_id ?? '' }}"
                                            data-designation-name="{{ $emp->designation->title ?? $emp->designation->name ?? 'None' }}">
                                        {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-'.$emp->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Appraisal Type --}}
                        <div class="col-md-6">
                            <label for="action_type" class="form-label fw-bold text-dark small">Appraisal Type <span class="text-danger">*</span></label>
                            <select name="action_type" id="action_type" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Increment" selected>Salary Increment</option>
                                <option value="Promotion">Promotion</option>
                                <option value="Both">Both (Increment & Promotion)</option>
                                <option value="None">None</option>
                            </select>
                        </div>

                        {{-- Current Salary (Read-only / Auto-fill) --}}
                        <div class="col-md-6">
                            <label for="previous_salary" class="form-label fw-bold text-dark small">Current Salary (PKR)</label>
                            <input type="number" step="0.01" name="previous_salary" id="previous_salary" class="form-control rounded-3 border-light-subtle bg-light" placeholder="0.00" readonly required>
                        </div>

                        {{-- New Salary --}}
                        <div class="col-md-6">
                            <label for="new_salary" class="form-label fw-bold text-dark small">New Salary (PKR) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="new_salary" id="new_salary" class="form-control rounded-3 border-light-subtle" placeholder="e.g. 95000" required>
                        </div>

                        {{-- Current Designation (Read-only) --}}
                        <div class="col-md-6">
                            <label for="current_designation_name" class="form-label fw-bold text-dark small">Current Designation</label>
                            <input type="text" id="current_designation_name" class="form-control rounded-3 border-light-subtle bg-light" placeholder="Auto-filled from Employee" readonly>
                            <input type="hidden" name="previous_designation_id" id="previous_designation_id">
                        </div>

                        {{-- New Designation (Dropdown) --}}
                        <div class="col-md-6">
                            <label for="new_designation_id" class="form-label fw-bold text-dark small">New Designation</label>
                            <select name="new_designation_id" id="new_designation_id" class="form-select rounded-3 border-light-subtle">
                                <option value="">Select New Designation (If Promoted)...</option>
                                @foreach($designations ?? [] as $desig)
                                    <option value="{{ $desig->id }}">{{ $desig->title ?? $desig->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Effective Date --}}
                        <div class="col-md-6">
                            <label for="effective_date" class="form-label fw-bold text-dark small">Effective Date <span class="text-danger">*</span></label>
                            <input type="date" name="effective_date" id="effective_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Approved" selected>Approved</option>
                                <option value="Draft">Draft (Pending)</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitAppraisalBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Submit Appraisal
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const createAppraisalForm = document.getElementById('createAppraisalForm');
    const submitAppraisalBtn = document.getElementById('submitAppraisalBtn');
    const modalAppraisalErrors = document.getElementById('modalAppraisalErrors');
    const appraisalsTableBody = document.querySelector('#appraisalsTable tbody');
    const alertContainer = document.getElementById('alertContainer');
    const empSelect = document.getElementById('modal_employee_id');
    const prevSalaryInput = document.getElementById('previous_salary');
    const prevDesigNameInput = document.getElementById('current_designation_name');
    const prevDesigIdInput = document.getElementById('previous_designation_id');
    const isAdmin = {{ auth()->user()->role === 'admin' ? 'true' : 'false' }};

    // Auto-fill Current Salary & Designation on Employee selection
    if (empSelect) {
        empSelect.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                const salary = selectedOpt.getAttribute('data-salary') || "0.00";
                const desigId = selectedOpt.getAttribute('data-designation-id') || "";
                const desigName = selectedOpt.getAttribute('data-designation-name') || "None";

                prevSalaryInput.value = parseFloat(salary).toFixed(2);
                prevDesigNameInput.value = desigName;
                prevDesigIdInput.value = desigId;
            } else {
                prevSalaryInput.value = "";
                prevDesigNameInput.value = "";
                prevDesigIdInput.value = "";
            }
        });
    }

    if (createAppraisalForm) {
        createAppraisalForm.addEventListener('submit', function (e) {
            e.preventDefault();

            submitAppraisalBtn.disabled = true;
            submitAppraisalBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';
            modalAppraisalErrors.classList.add('d-none');
            modalAppraisalErrors.innerHTML = '';

            const formData = new FormData(createAppraisalForm);

            fetch("{{ route('appraisals.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitAppraisalBtn.disabled = false;
                submitAppraisalBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Submit Appraisal';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('createAppraisalModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    createAppraisalForm.reset();
                    document.getElementById('effective_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('status').value = "Approved";

                    // Remove empty state row if present
                    const noAppraisalsRow = document.getElementById('noAppraisalsRow');
                    if (noAppraisalsRow) {
                        noAppraisalsRow.remove();
                    }

                    // Prepend new row
                    const a = data.appraisal;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusBadgeClass = 'bg-info-subtle text-info border border-info-subtle';
                    if (a.status === 'Approved') statusBadgeClass = 'bg-success-subtle text-success border border-success-subtle';
                    else if (a.status === 'Rejected') statusBadgeClass = 'bg-danger-subtle text-danger border border-danger-subtle';

                    let desigPromoHtml = '<span class="text-secondary opacity-50 small">None</span>';
                    if (a.new_designation && a.new_designation !== 'None') {
                        desigPromoHtml = `
                            <div class="d-flex flex-column align-items-start" style="gap: 2px;">
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-0.5 fw-normal text-truncate" style="font-size: 0.7rem; max-width: 160px;" title="${a.previous_designation}">
                                    ${a.previous_designation}
                                </span>
                                <div class="ps-3 my-0" style="line-height: 1;">
                                    <i class="bi bi-arrow-down text-primary opacity-75" style="font-size: 0.7rem;"></i>
                                </div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 fw-bold text-truncate" style="font-size: 0.7rem; max-width: 160px;" title="${a.new_designation}">
                                    ${a.new_designation}
                                </span>
                            </div>
                        `;
                    }

                    let actionsHtml = `
                        <div class="d-inline-flex align-items-center justify-content-end gap-2">
                            <a href="${a.edit_url}" class="btn btn-action-view" title="View Appraisal" aria-label="View Appraisal">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            ${isAdmin ? `
                                <a href="${a.edit_url}" class="btn btn-action-edit" title="Edit Appraisal" aria-label="Edit Appraisal">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${a.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this appraisal?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Appraisal" aria-label="Delete Appraisal">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            ` : ''}
                        </div>
                    `;

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'appraisalRow_' + a.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${a.employee_name.charAt(0)}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">${a.employee_name}</span>
                                    <small class="text-secondary opacity-75 d-block" style="font-size: 0.725rem;">ID: #${a.employee_code}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.725rem;">
                                ${a.action_type}
                            </span>
                        </td>
                        <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">PKR ${a.previous_salary}</td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;"><span class="text-success fw-bold">PKR ${a.new_salary}</span></td>
                        <td class="px-2 py-2.5 align-middle">${desigPromoHtml}</td>
                        <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">${a.effective_date}</td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge ${statusBadgeClass} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${a.status}
                            </span>
                        </td>
                        <td class="pe-3 py-2.5 text-end align-middle">${actionsHtml}</td>
                    `;
                    appraisalsTableBody.prepend(newRow);

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
                    modalAppraisalErrors.innerHTML = errHtml;
                    modalAppraisalErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                submitAppraisalBtn.disabled = false;
                submitAppraisalBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Submit Appraisal';
                modalAppraisalErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalAppraisalErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
