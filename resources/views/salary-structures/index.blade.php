@extends('layouts.app')

@section('title', 'Salary Structures')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Salary Structures']
    ]" />

    <div id="alertContainer">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Salary Structures</h3>
                <p class="text-secondary small mb-0">Define and manage base salaries, allowances, and deductions for active employees.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#addSalaryStructureDrawer">
                <i class="bi bi-plus-lg me-1"></i> Define Salary Structure
            </button>
        </div>
    </div>

    {{-- Search Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" class="row g-3">
            <div class="col-md-9">
                <label class="form-label small fw-semibold text-secondary mb-1">Search Employee</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="Search by Employee Name...">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button class="btn btn-primary w-100 rounded-3 fw-bold text-white shadow-sm py-2">Search</button>
                @if(request('search'))
                    <a href="{{ route('salary-structures.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold py-2">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Salary Structures Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="salaryStructuresTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Employee</th>
                        <th class="py-3">Department / Designation</th>
                        <th class="py-3">Basic Salary</th>
                        <th class="py-3">Net Salary</th>
                        <th class="py-3">Effective From</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 text-end py-3" width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaryStructures as $structure)
                        <tr class="hover-row" id="structureRow_{{ $structure->id }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="($structure->employee->first_name ?? '') . ' ' . ($structure->employee->last_name ?? '')" size="sm" />
                                    <span class="fw-bold text-dark">{{ $structure->employee->first_name ?? '' }} {{ $structure->employee->last_name ?? '' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-semibold me-1">
                                    {{ $structure->employee->department?->name ?? 'N/A' }}
                                </span>
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fw-semibold">
                                    {{ $structure->employee->designation?->title ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="fw-bold text-dark">PKR {{ number_format($structure->basic_salary, 2) }}</td>
                            <td class="fw-bold text-primary">PKR {{ number_format($structure->net_salary, 2) }}</td>
                            <td class="text-secondary small fw-medium">{{ $structure->effective_from }}</td>
                            <td>
                                @if($structure->status === 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Inactive</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                    <a href="{{ route('salary-structures.show', $structure->id) }}" class="btn btn-action-view" title="View Structure" aria-label="View Structure">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('salary-structures.edit', $structure->id) }}" class="btn btn-action-edit" title="Edit Structure" aria-label="Edit Structure">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('salary-structures.destroy', $structure->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this structure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Structure" aria-label="Delete Structure">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noStructuresRow">
                            <td colspan="7" class="p-0">
                                <x-empty-state title="No Salary Structures Defined" icon="bi-cash-stack" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($salaryStructures->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $salaryStructures->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Define Salary Structure Slide-over Offcanvas Drawer --}}
<div class="offcanvas offcanvas-end border-0 shadow-2xl" tabindex="-1" id="addSalaryStructureDrawer" aria-labelledby="addSalaryStructureDrawerLabel" style="width: 45%; min-width: 420px; max-width: 600px;">
    
    {{-- Drawer Header --}}
    <div class="offcanvas-header border-bottom border-light-subtle px-4 py-3 bg-white">
        <h5 class="offcanvas-title fw-bold text-dark d-flex align-items-center gap-2" id="addSalaryStructureDrawerLabel">
            <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-cash-coin fs-6"></i>
            </div>
            Define Salary Structure
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    {{-- Form Container --}}
    <form id="addSalaryStructureForm" action="{{ route('salary-structures.store') }}" method="POST" class="d-flex flex-column" style="height: calc(100vh - 80px);">
        @csrf
        
        {{-- Scrollable Form Body --}}
        <div class="offcanvas-body flex-grow-1 overflow-y-auto p-4 bg-white">
            <div id="drawerSalaryErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

            {{-- SECTION 1: Basic Information --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary border-bottom border-primary-subtle pb-2 mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-person-bounding-box"></i> 1. Basic Information & Employee
                </h6>
                <div class="row g-3">
                    {{-- Employee Selection --}}
                    <div class="col-12">
                        <label for="employee_id" class="form-label fw-bold text-dark small">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-select rounded-3 border-light-subtle" required>
                            <option value="">Select Employee...</option>
                            @foreach($employees ?? [] as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-' . $emp->id }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Basic Salary --}}
                    <div class="col-md-6">
                        <label for="basic_salary" class="form-label fw-bold text-dark small">Basic Salary (PKR) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="basic_salary" id="basic_salary" class="form-control rounded-3 border-light-subtle" placeholder="e.g. 80000" required>
                    </div>

                    {{-- Effective From --}}
                    <div class="col-md-6">
                        <label for="effective_from" class="form-label fw-bold text-dark small">Effective From <span class="text-danger">*</span></label>
                        <input type="date" name="effective_from" id="effective_from" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                    </div>

                    {{-- Status --}}
                    <div class="col-12">
                        <label for="status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Allowances --}}
            <div class="mb-4">
                <h6 class="fw-bold text-success border-bottom border-success-subtle pb-2 mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-wallet2"></i> 2. Allowances (Additions)
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="house_allowance" class="form-label fw-bold text-dark small">House Rent Allowance</label>
                        <input type="number" step="0.01" name="house_allowance" id="house_allowance" class="form-control rounded-3 border-light-subtle" placeholder="0.00" value="0">
                    </div>
                    <div class="col-md-6">
                        <label for="medical_allowance" class="form-label fw-bold text-dark small">Medical Allowance</label>
                        <input type="number" step="0.01" name="medical_allowance" id="medical_allowance" class="form-control rounded-3 border-light-subtle" placeholder="0.00" value="0">
                    </div>
                    <div class="col-md-6">
                        <label for="transport_allowance" class="form-label fw-bold text-dark small">Transport Allowance</label>
                        <input type="number" step="0.01" name="transport_allowance" id="transport_allowance" class="form-control rounded-3 border-light-subtle" placeholder="0.00" value="0">
                    </div>
                    <div class="col-md-6">
                        <label for="other_allowance" class="form-label fw-bold text-dark small">Other Allowance</label>
                        <input type="number" step="0.01" name="other_allowance" id="other_allowance" class="form-control rounded-3 border-light-subtle" placeholder="0.00" value="0">
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Deductions --}}
            <div class="mb-4">
                <h6 class="fw-bold text-danger border-bottom border-danger-subtle pb-2 mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-dash-circle"></i> 3. Deductions & Taxes
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="tax" class="form-label fw-bold text-dark small">Income Tax</label>
                        <input type="number" step="0.01" name="tax" id="tax" class="form-control rounded-3 border-light-subtle" placeholder="0.00" value="0">
                    </div>
                    <div class="col-md-6">
                        <label for="provident_fund" class="form-label fw-bold text-dark small">Provident Fund</label>
                        <input type="number" step="0.01" name="provident_fund" id="provident_fund" class="form-control rounded-3 border-light-subtle" placeholder="0.00" value="0">
                    </div>
                    <div class="col-12">
                        <label for="other_deduction" class="form-label fw-bold text-dark small">Other Deductions</label>
                        <input type="number" step="0.01" name="other_deduction" id="other_deduction" class="form-control rounded-3 border-light-subtle" placeholder="0.00" value="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- Sticky Footer Bar --}}
        <div class="p-3 bg-white border-top border-light-subtle d-flex justify-content-end gap-2" style="position: sticky; bottom: 0; z-index: 10; flex-shrink: 0;">
            <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="offcanvas">Cancel</button>
            <button type="submit" id="saveStructureBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                <i class="bi bi-check-circle-fill me-1"></i> Save Structure
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addSalaryStructureForm = document.getElementById('addSalaryStructureForm');
    const saveStructureBtn = document.getElementById('saveStructureBtn');
    const drawerSalaryErrors = document.getElementById('drawerSalaryErrors');
    const salaryStructuresTableBody = document.querySelector('#salaryStructuresTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (addSalaryStructureForm) {
        addSalaryStructureForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveStructureBtn.disabled = true;
            saveStructureBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            drawerSalaryErrors.classList.add('d-none');
            drawerSalaryErrors.innerHTML = '';

            const formData = new FormData(addSalaryStructureForm);

            fetch("{{ route('salary-structures.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveStructureBtn.disabled = false;
                saveStructureBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Structure';

                if (data.success) {
                    // Close Offcanvas Drawer
                    const drawerEl = document.getElementById('addSalaryStructureDrawer');
                    const drawerInstance = bootstrap.Offcanvas.getInstance(drawerEl);
                    if (drawerInstance) {
                        drawerInstance.hide();
                    }

                    // Reset Form
                    addSalaryStructureForm.reset();
                    document.getElementById('effective_from').value = "{{ date('Y-m-d') }}";
                    document.getElementById('house_allowance').value = "0";
                    document.getElementById('medical_allowance').value = "0";
                    document.getElementById('transport_allowance').value = "0";
                    document.getElementById('other_allowance').value = "0";
                    document.getElementById('tax').value = "0";
                    document.getElementById('provident_fund').value = "0";
                    document.getElementById('other_deduction').value = "0";

                    // Remove empty state row if present
                    const noStructuresRow = document.getElementById('noStructuresRow');
                    if (noStructuresRow) {
                        noStructuresRow.remove();
                    }

                    // Prepend new row
                    const s = data.salary_structure;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusBadge = s.status === 'active' 
                        ? '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Active</span>' 
                        : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Inactive</span>';

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'structureRow_' + s.id;
                    newRow.innerHTML = `
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${s.employee_name.charAt(0)}
                                </div>
                                <span class="fw-bold text-dark">${s.employee_name}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-semibold me-1">${s.department_name}</span>
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fw-semibold">${s.designation_title}</span>
                        </td>
                        <td class="fw-bold text-dark">PKR ${s.basic_salary}</td>
                        <td class="fw-bold text-primary">PKR ${s.net_salary}</td>
                        <td class="text-secondary small fw-medium">${s.effective_from}</td>
                        <td>${statusBadge}</td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                <a href="${s.show_url}" class="btn btn-action-view" title="View Structure" aria-label="View Structure">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="${s.edit_url}" class="btn btn-action-edit" title="Edit Structure" aria-label="Edit Structure">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${s.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this structure?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Structure" aria-label="Delete Structure">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    salaryStructuresTableBody.prepend(newRow);

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
                    drawerSalaryErrors.innerHTML = errHtml;
                    drawerSalaryErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveStructureBtn.disabled = false;
                saveStructureBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Structure';
                drawerSalaryErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                drawerSalaryErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
