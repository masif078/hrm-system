@extends('layouts.app')

@section('title', 'Leave Balances Summary')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Leave Balances Summary']
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
                <h3 class="fw-bold text-dark mb-1">Leave Balances Summary</h3>
                <p class="text-secondary small mb-0">Track and allocate annual, sick, or casual leave balances for company employees.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#allocateLeaveModal">
                <i class="bi bi-plus-lg me-1"></i> Allocate Leaves
            </button>
        </div>
    </div>

    {{-- Leave Balances Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="leaveBalancesTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Employee</th>
                        <th class="py-3">Leave Type</th>
                        <th class="py-3">Allocated Leaves</th>
                        <th class="py-3">Used Leaves</th>
                        <th class="py-3">Remaining Leaves</th>
                        <th class="pe-4 text-end py-3" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $balance)
                        @php $remaining = $balance->allocated - $balance->used; @endphp
                        <tr class="hover-row" id="balanceRow_{{ $balance->id }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="($balance->employee->first_name ?? '') . ' ' . ($balance->employee->last_name ?? '')" size="sm" />
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $balance->employee->first_name ?? '' }} {{ $balance->employee->last_name ?? '' }}</span>
                                        <small class="text-secondary opacity-75 d-block">ID: #{{ $balance->employee->employee_id ?? $balance->employee_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold text-secondary">{{ $balance->leave_type }}</td>
                            <td class="fw-bold text-dark">{{ $balance->allocated }} days</td>
                            <td class="text-secondary fw-medium">{{ $balance->used }} days</td>
                            <td>
                                <span class="badge {{ $remaining > 2 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                    {{ $remaining }} days left
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                    <a href="{{ route('leave-balances.edit', $balance->id) }}" class="btn btn-action-edit" title="Edit Allocation" aria-label="Edit Allocation">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('leave-balances.destroy', $balance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this leave allocation?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Allocation" aria-label="Delete Allocation">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noBalancesRow">
                            <td colspan="6" class="p-0">
                                <x-empty-state title="No Leave Allocations Found" icon="bi-calendar-x" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($balances->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $balances->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Allocate Leaves Modal Overlay --}}
<div class="modal fade" id="allocateLeaveModal" tabindex="-1" aria-labelledby="allocateLeaveModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="allocateLeaveModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-calendar-plus-fill fs-6"></i>
                    </div>
                    Allocate Leave Balances
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="allocateLeaveForm" action="{{ route('leave-balances.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalBalanceErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Employee Selection --}}
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-bold text-dark small">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Employee...</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-' . $emp->id }})</option>
                                @endforeach
                            </select>
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

                        {{-- Number of Days --}}
                        <div class="col-md-6">
                            <label for="allocated" class="form-label fw-bold text-dark small">Number of Days <span class="text-danger">*</span></label>
                            <input type="number" name="allocated" id="allocated" class="form-control rounded-3 border-light-subtle" value="14" min="1" required>
                        </div>

                        {{-- Year --}}
                        <div class="col-md-6">
                            <label for="year" class="form-label fw-bold text-dark small">Year <span class="text-danger">*</span></label>
                            <select name="year" id="year" class="form-select rounded-3 border-light-subtle" required>
                                <option value="2026" selected>2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2027">2027</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveBalanceBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Allocate
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const allocateLeaveForm = document.getElementById('allocateLeaveForm');
    const saveBalanceBtn = document.getElementById('saveBalanceBtn');
    const modalBalanceErrors = document.getElementById('modalBalanceErrors');
    const leaveBalancesTableBody = document.querySelector('#leaveBalancesTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (allocateLeaveForm) {
        allocateLeaveForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveBalanceBtn.disabled = true;
            saveBalanceBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Allocating...';
            modalBalanceErrors.classList.add('d-none');
            modalBalanceErrors.innerHTML = '';

            const formData = new FormData(allocateLeaveForm);

            fetch("{{ route('leave-balances.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveBalanceBtn.disabled = false;
                saveBalanceBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Allocate';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('allocateLeaveModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    allocateLeaveForm.reset();
                    document.getElementById('allocated').value = "14";
                    document.getElementById('year').value = "2026";

                    // Remove empty state row if present
                    const noBalancesRow = document.getElementById('noBalancesRow');
                    if (noBalancesRow) {
                        noBalancesRow.remove();
                    }

                    // Prepend new row
                    const b = data.balance;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const badgeClass = b.remaining > 2 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle';

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'balanceRow_' + b.id;
                    newRow.innerHTML = `
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${b.employee_name.charAt(0)}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block">${b.employee_name}</span>
                                    <small class="text-secondary opacity-75 d-block">ID: #${b.employee_code}</small>
                                </div>
                            </div>
                        </td>
                        <td class="fw-semibold text-secondary">${b.leave_type}</td>
                        <td class="fw-bold text-dark">${b.allocated} days</td>
                        <td class="text-secondary fw-medium">${b.used} days</td>
                        <td>
                            <span class="badge ${badgeClass} rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                ${b.remaining} days left
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                <a href="${b.edit_url}" class="btn btn-action-edit" title="Edit Allocation" aria-label="Edit Allocation">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${b.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this leave allocation?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Allocation" aria-label="Delete Allocation">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    leaveBalancesTableBody.prepend(newRow);

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
                    modalBalanceErrors.innerHTML = errHtml;
                    modalBalanceErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveBalanceBtn.disabled = false;
                saveBalanceBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Allocate';
                modalBalanceErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalBalanceErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
