@extends('layouts.app')

@section('title', 'Leaves Management')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Leaves Management']
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
                <h3 class="fw-bold text-dark mb-1">Leave Management</h3>
                <p class="text-secondary small mb-0">Track leave requests, employee balances, and leave approvals.</p>
            </div>

            @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addLeaveModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Leave
                </button>
            @else
                <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addLeaveModal">
                    <i class="bi bi-plus-lg me-1"></i> Apply Leave
                </button>
            @endif
        </div>
    </div>

    {{-- Stat Cards Row --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Total Leaves" 
                :value="$totalLeaves" 
                color="blue" 
                icon="bi-calendar-event-fill" 
                trend="All Requests" 
                trendType="neutral" 
                id="statTotalLeaves"
            />
        </div>

        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Pending Requests" 
                :value="$pendingLeaves" 
                color="amber" 
                icon="bi-clock-history" 
                trend="Needs Action" 
                trendType="down" 
                id="statPendingLeaves"
            />
        </div>

        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Approved Leaves" 
                :value="$approvedLeaves" 
                color="green" 
                icon="bi-check-circle-fill" 
                trend="Approved" 
                trendType="up" 
                id="statApprovedLeaves"
            />
        </div>

        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Rejected Leaves" 
                :value="$rejectedLeaves" 
                color="purple" 
                icon="bi-x-circle-fill" 
                trend="Rejected" 
                trendType="down" 
                id="statRejectedLeaves"
            />
        </div>
    </div>

    {{-- Search and Filters Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-secondary mb-1">Search Employee</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control rounded-3 border-light-subtle shadow-2xs"
                    placeholder="Search by employee name...">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Status</label>
                <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs">
                    <option value="">All Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Leave Type</label>
                <select name="leave_type" class="form-select rounded-3 border-light-subtle shadow-2xs">
                    <option value="">All Leave Types</option>
                    <option value="Annual Leave" {{ request('leave_type') == 'Annual Leave' ? 'selected' : '' }}>Annual Leave</option>
                    <option value="Sick Leave" {{ request('leave_type') == 'Sick Leave' ? 'selected' : '' }}>Sick Leave</option>
                    <option value="Casual Leave" {{ request('leave_type') == 'Casual Leave' ? 'selected' : '' }}>Casual Leave</option>
                    <option value="Emergency Leave" {{ request('leave_type') == 'Emergency Leave' ? 'selected' : '' }}>Emergency Leave</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold py-2 text-white shadow-sm">Filter</button>
                <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold py-2">Reset</a>
            </div>
        </form>
    </div>

    {{-- Leave Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="leavesTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Employee</th>
                        <th class="py-3">Leave Type</th>
                        <th class="py-3">Start Date</th>
                        <th class="py-3">End Date</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 text-end py-3" width="220">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr class="hover-row" id="leaveRow_{{ $leave->id }}">
                            <td class="ps-4 fw-bold text-secondary">#{{ $leave->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="($leave->employee->first_name ?? '') . ' ' . ($leave->employee->last_name ?? '')" size="sm" />
                                    <span class="fw-bold text-dark">{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}</span>
                                </div>
                            </td>
                            <td class="fw-semibold text-secondary">{{ $leave->leave_type }}</td>
                            <td class="text-secondary small">{{ $leave->start_date }}</td>
                            <td class="text-secondary small">{{ $leave->end_date }}</td>
                            <td>
                                <x-status-badge :status="$leave->status" />
                            </td>
                            <td class="pe-4 text-end">
                                @if(auth()->user()->role === 'admin')
                                    <div class="d-inline-flex align-items-center gap-1.5">
                                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-action-view" title="View Leave" aria-label="View Leave">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-action-edit" title="Edit Leave" aria-label="Edit Leave">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete" title="Delete Leave" aria-label="Delete Leave" onclick="return confirm('Are you sure you want to delete this leave?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>

                                        @if($leave->status == 'Pending')
                                            <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm rounded-3 fw-bold px-2 py-1" title="Approve Leave">
                                                    ✓
                                                </button>
                                            </form>
                                            <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-3 fw-bold px-2 py-1" title="Reject Leave">
                                                    ✕
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <x-status-badge :status="$leave->status" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="noLeavesRow">
                            <td colspan="7" class="p-0">
                                <x-empty-state title="No Leave Records Found" icon="bi-calendar-x" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaves->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $leaves->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Add Leave Modal Overlay --}}
<div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="addLeaveModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-calendar-plus-fill fs-6"></i>
                    </div>
                    {{ auth()->user()->role === 'admin' ? 'Add Leave Request' : 'Apply for Leave' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="addLeaveForm" action="{{ auth()->user()->role === 'admin' ? route('leaves.store') : route('employee.leaves.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalLeaveErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Employee Selection --}}
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-bold text-dark small">Employee <span class="text-danger">*</span></label>
                            @if(auth()->user()->role === 'admin')
                                <select name="employee_id" id="employee_id" class="form-select rounded-3 border-light-subtle" required>
                                    <option value="">Select Employee...</option>
                                    @foreach($employees ?? [] as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-' . $emp->id }})</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id ?? '' }}">
                                <input type="text" class="form-control rounded-3 border-light-subtle bg-light text-dark fw-bold" value="{{ auth()->user()->name }}" readonly>
                            @endif
                        </div>

                        {{-- Leave Type --}}
                        <div class="col-md-6">
                            <label for="leave_type" class="form-label fw-bold text-dark small">Leave Type <span class="text-danger">*</span></label>
                            <select name="leave_type" id="leave_type" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Annual Leave" selected>Annual Leave</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Casual Leave">Casual Leave</option>
                                <option value="Emergency Leave">Emergency Leave</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                                <option value="Paternity Leave">Paternity Leave</option>
                            </select>
                        </div>

                        {{-- Start Date --}}
                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-bold text-dark small">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- End Date --}}
                        <div class="col-md-6">
                            <label for="end_date" class="form-label fw-bold text-dark small">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        @if(auth()->user()->role === 'admin')
                            {{-- Status (Admin Only) --}}
                            <div class="col-12">
                                <label for="modal_status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
                                <select name="status" id="modal_status" class="form-select rounded-3 border-light-subtle" required>
                                    <option value="Pending" selected>Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="status" value="Pending">
                        @endif

                        {{-- Reason --}}
                        <div class="col-12">
                            <label for="reason" class="form-label fw-bold text-dark small">Reason for Leave</label>
                            <textarea name="reason" id="reason" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="Provide a brief explanation for taking leave..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="applyLeaveBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Apply Leave
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addLeaveForm = document.getElementById('addLeaveForm');
    const applyLeaveBtn = document.getElementById('applyLeaveBtn');
    const modalLeaveErrors = document.getElementById('modalLeaveErrors');
    const leavesTableBody = document.querySelector('#leavesTable tbody');
    const alertContainer = document.getElementById('alertContainer');
    const isAdmin = {{ auth()->user()->role === 'admin' ? 'true' : 'false' }};

    if (addLeaveForm) {
        addLeaveForm.addEventListener('submit', function (e) {
            e.preventDefault();

            applyLeaveBtn.disabled = true;
            applyLeaveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';
            modalLeaveErrors.classList.add('d-none');
            modalLeaveErrors.innerHTML = '';

            const formData = new FormData(addLeaveForm);
            const submitUrl = addLeaveForm.getAttribute('action');

            fetch(submitUrl, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                applyLeaveBtn.disabled = false;
                applyLeaveBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Apply Leave';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('addLeaveModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    addLeaveForm.reset();
                    document.getElementById('start_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('end_date').value = "{{ date('Y-m-d') }}";

                    // Remove empty state row if present
                    const noLeavesRow = document.getElementById('noLeavesRow');
                    if (noLeavesRow) {
                        noLeavesRow.remove();
                    }

                    // Prepend new row
                    const l = data.leave;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusBadgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                    if (l.status === 'Approved') statusBadgeClass = 'bg-success-subtle text-success border-success-subtle';
                    else if (l.status === 'Rejected') statusBadgeClass = 'bg-danger-subtle text-danger border-danger-subtle';

                    let actionsHtml = '';
                    if (isAdmin) {
                        actionsHtml = `
                            <div class="d-inline-flex align-items-center gap-1.5">
                                <a href="${l.show_url}" class="btn btn-action-view" title="View Leave" aria-label="View Leave">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="${l.edit_url}" class="btn btn-action-edit" title="Edit Leave" aria-label="Edit Leave">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${l.destroy_url}" method="POST" class="d-inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Leave" aria-label="Delete Leave" onclick="return confirm('Are you sure you want to delete this leave?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                                ${l.status === 'Pending' ? `
                                    <form action="${l.approve_url}" method="POST" class="d-inline">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="PATCH">
                                        <button type="submit" class="btn btn-success btn-sm rounded-3 fw-bold px-2 py-1" title="Approve Leave">✓</button>
                                    </form>
                                    <form action="${l.reject_url}" method="POST" class="d-inline">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="PATCH">
                                        <button type="submit" class="btn btn-danger btn-sm rounded-3 fw-bold px-2 py-1" title="Reject Leave">✕</button>
                                    </form>
                                ` : ''}
                            </div>
                        `;
                    } else {
                        actionsHtml = `
                            <span class="badge border ${statusBadgeClass} rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                ${l.status}
                            </span>
                        `;
                    }

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'leaveRow_' + l.id;
                    newRow.innerHTML = `
                        <td class="ps-4 fw-bold text-secondary">#${l.id}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${l.employee_name.charAt(0)}
                                </div>
                                <span class="fw-bold text-dark">${l.employee_name}</span>
                            </div>
                        </td>
                        <td class="fw-semibold text-secondary">${l.leave_type}</td>
                        <td class="text-secondary small">${l.start_date}</td>
                        <td class="text-secondary small">${l.end_date}</td>
                        <td>
                            <span class="badge border ${statusBadgeClass} rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                ${l.status}
                            </span>
                        </td>
                        <td class="pe-4 text-end">${actionsHtml}</td>
                    `;
                    leavesTableBody.prepend(newRow);

                    // Update Stat Card Counts
                    const totalElem = document.querySelector('#statTotalLeaves .stat-card-value');
                    if (totalElem) {
                        let count = parseInt(totalElem.textContent.replace(/,/g, '')) || 0;
                        totalElem.textContent = (count + 1).toLocaleString();
                    }

                    if (l.status === 'Pending') {
                        const pendingElem = document.querySelector('#statPendingLeaves .stat-card-value');
                        if (pendingElem) {
                            let count = parseInt(pendingElem.textContent.replace(/,/g, '')) || 0;
                            pendingElem.textContent = (count + 1).toLocaleString();
                        }
                    } else if (l.status === 'Approved') {
                        const approvedElem = document.querySelector('#statApprovedLeaves .stat-card-value');
                        if (approvedElem) {
                            let count = parseInt(approvedElem.textContent.replace(/,/g, '')) || 0;
                            approvedElem.textContent = (count + 1).toLocaleString();
                        }
                    } else if (l.status === 'Rejected') {
                        const rejectedElem = document.querySelector('#statRejectedLeaves .stat-card-value');
                        if (rejectedElem) {
                            let count = parseInt(rejectedElem.textContent.replace(/,/g, '')) || 0;
                            rejectedElem.textContent = (count + 1).toLocaleString();
                        }
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
                    modalLeaveErrors.innerHTML = errHtml;
                    modalLeaveErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                applyLeaveBtn.disabled = false;
                applyLeaveBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Apply Leave';
                modalLeaveErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalLeaveErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection