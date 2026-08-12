@extends('layouts.app')

@section('title', 'Asset Assignments')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Asset Assignments']
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
                <h3 class="fw-bold text-dark mb-1">Asset Assignments</h3>
                <p class="text-secondary small mb-0">Allocate company hardware devices to employees and manage check-in returns.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                <i class="bi bi-plus-lg me-1"></i> Check-out Asset
            </button>
        </div>
    </div>

    {{-- Asset Assignments Table Card (100% Screen Fit, Zero Scroll, Compact SaaS Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="assignmentsTable" style="font-size: 0.8rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">Asset</th>
                        <th class="px-2 py-2.5">Assigned To</th>
                        <th class="px-2 py-2.5 text-nowrap">Assign Date & Condition</th>
                        <th class="px-2 py-2.5 text-nowrap">Return Date & Condition</th>
                        <th class="px-2 py-2.5 text-nowrap">Status</th>
                        <th class="pe-3 text-end py-2.5 text-nowrap" width="130">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assign)
                        <tr class="hover-row" id="assignmentRow_{{ $assign->id }}">
                            {{-- Asset Details (Name wraps if needed + Serial Badge) --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <span class="fw-bold text-dark d-block mb-0.5" style="white-space: normal; word-break: break-word; line-height: 1.3;">{{ $assign->asset->name ?? 'N/A' }}</span>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-3 px-1.5 py-0.5 font-monospace" style="font-size: 0.7rem;">
                                    SN: {{ $assign->asset->serial_number ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- Assigned To (Employee Name + Gap-2 Avatar) --}}
                            <td class="px-2 py-2.5 align-middle">
                                <div class="d-flex align-items-center gap-2" style="white-space: nowrap;">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                        {{ strtoupper(substr($assign->employee->first_name ?? 'E', 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold text-dark text-nowrap" style="font-size: 0.8rem;">
                                        {{ $assign->employee->first_name ?? '' }} {{ $assign->employee->last_name ?? '' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Assign Date & Condition (Stacked Vertically) --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap">
                                <span class="fw-bold text-dark d-block mb-0" style="font-size: 0.8rem;">{{ date('M d, Y', strtotime($assign->assign_date)) }}</span>
                                <span class="text-secondary d-block opacity-75" style="font-size: 0.725rem;">{{ $assign->condition_upon_assign }}</span>
                            </td>

                            {{-- Return Date & Condition (Stacked Vertically) --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" id="returnCol_{{ $assign->id }}">
                                @if($assign->return_date)
                                    <span class="fw-bold text-dark d-block mb-0" style="font-size: 0.8rem;">{{ date('M d, Y', strtotime($assign->return_date)) }}</span>
                                    <span class="text-secondary d-block opacity-75" style="font-size: 0.725rem;">{{ $assign->condition_upon_return }}</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-0.5 fw-bold" style="font-size: 0.7rem;">
                                        Possessed (In Use)
                                    </span>
                                @endif
                            </td>

                            {{-- Status Badges --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" id="statusCol_{{ $assign->id }}">
                                @php
                                    $st = $assign->status;
                                    $statusClass = 'bg-primary-subtle text-primary border border-primary-subtle';
                                    if ($st === 'Returned') {
                                        $statusClass = 'bg-success-subtle text-success border border-success-subtle';
                                    } elseif ($st === 'Lost') {
                                        $statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                    {{ $assign->status }}
                                </span>
                            </td>

                            {{-- Compact Action Button --}}
                            <td class="pe-3 py-2.5 text-end align-middle text-nowrap" id="actionCol_{{ $assign->id }}" width="130">
                                @if($assign->status === 'Assigned')
                                    <button type="button" 
                                            class="btn btn-outline-success btn-sm rounded-3 px-2.5 py-1 fw-bold d-inline-flex align-items-center gap-1 checkin-btn"
                                            style="font-size: 0.75rem;"
                                            data-id="{{ $assign->id }}"
                                            data-asset="{{ $assign->asset->name ?? 'N/A' }}"
                                            data-employee="{{ $assign->employee->first_name ?? '' }} {{ $assign->employee->last_name ?? '' }}"
                                            data-assign-date="{{ date('M d, Y', strtotime($assign->assign_date)) }}"
                                            data-update-url="{{ route('asset-assignments.update', $assign->id) }}">
                                        <i class="bi bi-box-arrow-in-left"></i> Check-in
                                    </button>
                                @else
                                    <span class="text-secondary opacity-50 small fw-semibold">
                                        <i class="bi bi-check2-all me-1 text-success"></i> Done
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="noAssignmentsRow">
                            <td colspan="6" class="p-0">
                                <x-empty-state title="No Asset Assignments Logged" icon="bi-arrow-left-right" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Check-out Asset Centered Modal Popup --}}
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="checkoutModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-box-arrow-up-right fs-6"></i>
                    </div>
                    Check-out Asset to Employee
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="checkoutForm" action="{{ route('asset-assignments.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="checkoutModalErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Asset Dropdown --}}
                        <div class="col-md-6">
                            <label for="asset_id" class="form-label fw-bold text-dark small">Available Asset <span class="text-danger">*</span></label>
                            <select name="asset_id" id="asset_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Asset...</option>
                                @foreach($assets ?? [] as $asset)
                                    <option value="{{ $asset->id }}">
                                        {{ $asset->name }} (SN: {{ $asset->serial_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Employee Dropdown --}}
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-bold text-dark small">Assign To Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Employee...</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->department->name ?? 'Staff' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Assignment Date --}}
                        <div class="col-md-6">
                            <label for="assign_date" class="form-label fw-bold text-dark small">Assign Date <span class="text-danger">*</span></label>
                            <input type="date" name="assign_date" id="assign_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Condition Upon Assignment --}}
                        <div class="col-md-6">
                            <label for="condition_upon_assign" class="form-label fw-bold text-dark small">Device Condition <span class="text-danger">*</span></label>
                            <input type="text" name="condition_upon_assign" id="condition_upon_assign" class="form-control rounded-3 border-light-subtle" value="Excellent / Brand New" placeholder="e.g. Excellent / Brand New" required>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="assignAssetBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Assign Asset
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Process Return (Check-in) Centered Modal Popup --}}
<div class="modal fade" id="checkinModal" tabindex="-1" aria-labelledby="checkinModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="checkinModalLabel">
                    <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-box-arrow-in-left fs-6"></i>
                    </div>
                    Process Return (Check-in)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="checkinForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-white">
                    <div id="checkinModalErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    {{-- Readonly Summary Banner --}}
                    <div class="card border border-light-subtle rounded-3 p-3 bg-light mb-3">
                        <div class="row g-2 text-dark small">
                            <div class="col-6">
                                <span class="text-secondary d-block">Asset Name:</span>
                                <strong id="checkinAssetName">-</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-secondary d-block">Assigned Employee:</span>
                                <strong id="checkinEmployeeName">-</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Return Date --}}
                        <div class="col-md-6">
                            <label for="return_date" class="form-label fw-bold text-dark small">Return Date <span class="text-danger">*</span></label>
                            <input type="date" name="return_date" id="return_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Return Status --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold text-dark small">Return Status <span class="text-danger">*</span></label>
                            <select name="status" id="checkin_status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Returned" selected>Returned</option>
                                <option value="Lost">Lost</option>
                            </select>
                        </div>

                        {{-- Return Condition --}}
                        <div class="col-12">
                            <label for="condition_upon_return" class="form-label fw-bold text-dark small">Return Condition / Notes <span class="text-danger">*</span></label>
                            <input type="text" name="condition_upon_return" id="condition_upon_return" class="form-control rounded-3 border-light-subtle" value="Good / No damage" placeholder="e.g. Good / No damage" required>
                            <small class="text-secondary d-block mt-1">If condition mentions "damaged", "broken", or "repair", asset will move to Maintenance.</small>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="confirmReturnBtn" class="btn btn-success rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Confirm Return
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkoutForm = document.getElementById('checkoutForm');
    const assignAssetBtn = document.getElementById('assignAssetBtn');
    const checkoutModalErrors = document.getElementById('checkoutModalErrors');

    const checkinForm = document.getElementById('checkinForm');
    const confirmReturnBtn = document.getElementById('confirmReturnBtn');
    const checkinModalErrors = document.getElementById('checkinModalErrors');
    const assignmentsTableBody = document.querySelector('#assignmentsTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    // Handle Check-in Button Click in Table
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.checkin-btn');
        if (btn) {
            const updateUrl = btn.getAttribute('data-update-url');
            const assetName = btn.getAttribute('data-asset');
            const empName = btn.getAttribute('data-employee');

            checkinForm.setAttribute('action', updateUrl);
            document.getElementById('checkinAssetName').textContent = assetName;
            document.getElementById('checkinEmployeeName').textContent = empName;

            const checkinModalEl = document.getElementById('checkinModal');
            const checkinModalInstance = new bootstrap.Modal(checkinModalEl);
            checkinModalInstance.show();
        }
    });

    // Submit Check-out Form
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            e.preventDefault();

            assignAssetBtn.disabled = true;
            assignAssetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Assigning...';
            checkoutModalErrors.classList.add('d-none');
            checkoutModalErrors.innerHTML = '';

            const formData = new FormData(checkoutForm);

            fetch("{{ route('asset-assignments.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                assignAssetBtn.disabled = false;
                assignAssetBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Assign Asset';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('checkoutModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    checkoutForm.reset();
                    document.getElementById('assign_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('condition_upon_assign').value = "Excellent / Brand New";

                    // Remove empty state row if present
                    const noAssignmentsRow = document.getElementById('noAssignmentsRow');
                    if (noAssignmentsRow) {
                        noAssignmentsRow.remove();
                    }

                    // Prepend new row
                    const a = data.assignment;
                    const initialChar = a.employee_name ? a.employee_name.charAt(0).toUpperCase() : 'E';

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'assignmentRow_' + a.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <span class="fw-bold text-dark d-block mb-0.5" style="white-space: normal; word-break: break-word; line-height: 1.3;">${a.asset_name}</span>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-3 px-1.5 py-0.5 font-monospace" style="font-size: 0.7rem;">
                                SN: ${a.serial_number}
                            </span>
                        </td>
                        <td class="px-2 py-2.5 align-middle">
                            <div class="d-flex align-items-center gap-2" style="white-space: nowrap;">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                    ${initialChar}
                                </div>
                                <span class="fw-semibold text-dark text-nowrap" style="font-size: 0.8rem;">
                                    ${a.employee_name}
                                </span>
                            </div>
                        </td>
                        <td class="px-2 py-2.5 align-middle text-nowrap">
                            <span class="fw-bold text-dark d-block mb-0" style="font-size: 0.8rem;">${a.assign_date}</span>
                            <span class="text-secondary d-block opacity-75" style="font-size: 0.725rem;">${a.condition_upon_assign}</span>
                        </td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" id="returnCol_${a.id}">
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-0.5 fw-bold" style="font-size: 0.7rem;">
                                Possessed (In Use)
                            </span>
                        </td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" id="statusCol_${a.id}">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${a.status}
                            </span>
                        </td>
                        <td class="pe-3 py-2.5 text-end align-middle text-nowrap" id="actionCol_${a.id}" width="130">
                            <button type="button" 
                                    class="btn btn-outline-success btn-sm rounded-3 px-2.5 py-1 fw-bold d-inline-flex align-items-center gap-1 checkin-btn"
                                    style="font-size: 0.75rem;"
                                    data-id="${a.id}"
                                    data-asset="${a.asset_name}"
                                    data-employee="${a.employee_name}"
                                    data-assign-date="${a.assign_date}"
                                    data-update-url="${a.update_url}">
                                <i class="bi bi-box-arrow-in-left"></i> Check-in
                            </button>
                        </td>
                    `;
                    assignmentsTableBody.prepend(newRow);

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
                    checkoutModalErrors.innerHTML = errHtml;
                    checkoutModalErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                assignAssetBtn.disabled = false;
                assignAssetBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Assign Asset';
                checkoutModalErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                checkoutModalErrors.classList.remove('d-none');
            });
        });
    }

    // Submit Check-in Form
    if (checkinForm) {
        checkinForm.addEventListener('submit', function (e) {
            e.preventDefault();

            confirmReturnBtn.disabled = true;
            confirmReturnBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
            checkinModalErrors.classList.add('d-none');
            checkinModalErrors.innerHTML = '';

            const formData = new FormData(checkinForm);
            const actionUrl = checkinForm.getAttribute('action');

            fetch(actionUrl, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                confirmReturnBtn.disabled = false;
                confirmReturnBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Confirm Return';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('checkinModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    checkinForm.reset();

                    // Update Row in Table
                    const a = data.assignment;
                    const returnCol = document.getElementById('returnCol_' + a.id);
                    const statusCol = document.getElementById('statusCol_' + a.id);
                    const actionCol = document.getElementById('actionCol_' + a.id);

                    if (returnCol) {
                        returnCol.innerHTML = `
                            <span class="fw-bold text-dark d-block mb-0" style="font-size: 0.8rem;">${a.return_date}</span>
                            <span class="text-secondary d-block opacity-75" style="font-size: 0.725rem;">${a.condition_upon_return}</span>
                        `;
                    }

                    if (statusCol) {
                        let statusClass = 'bg-success-subtle text-success border border-success-subtle';
                        if (a.status === 'Lost') statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                        statusCol.innerHTML = `
                            <span class="badge ${statusClass} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${a.status}
                            </span>
                        `;
                    }

                    if (actionCol) {
                        actionCol.innerHTML = `
                            <span class="text-secondary opacity-50 small fw-semibold">
                                <i class="bi bi-check2-all me-1 text-success"></i> Done
                            </span>
                        `;
                    }

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
                    checkinModalErrors.innerHTML = errHtml;
                    checkinModalErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                confirmReturnBtn.disabled = false;
                confirmReturnBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Confirm Return';
                checkinModalErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                checkinModalErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
