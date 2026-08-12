@extends('layouts.app')

@section('title', 'Shift Management')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Shift Management']
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
                <h3 class="fw-bold text-dark mb-1">Shift Management</h3>
                <p class="text-secondary small mb-0">Define and manage working hours, shift schedules, and grace thresholds for employees.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addShiftModal">
                <i class="bi bi-plus-lg me-1"></i> Add New Shift
            </button>
        </div>
    </div>

    {{-- Shift Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="shiftsTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Shift Name</th>
                        <th class="py-3">Start Time</th>
                        <th class="py-3">End Time</th>
                        <th class="py-3">Late Threshold</th>
                        <th class="py-3">Early Check-out Threshold</th>
                        <th class="pe-4 text-end py-3" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                        <tr class="hover-row" id="shiftRow_{{ $shift->id }}">
                            <td class="ps-4 fw-bold text-dark">{{ $shift->name }}</td>
                            <td class="text-secondary small fw-medium">{{ date('h:i A', strtotime($shift->start_time)) }}</td>
                            <td class="text-secondary small fw-medium">{{ date('h:i A', strtotime($shift->end_time)) }}</td>
                            <td class="text-secondary small fw-medium">{{ date('h:i A', strtotime($shift->late_mark_after)) }}</td>
                            <td class="text-secondary small fw-medium">{{ date('h:i A', strtotime($shift->early_checkout_before)) }}</td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                    <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-action-edit" title="Edit Shift" aria-label="Edit Shift">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this shift?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Shift" aria-label="Delete Shift">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noShiftsRow">
                            <td colspan="6" class="p-0">
                                <x-empty-state title="No Shifts Defined Yet" icon="bi-clock-history" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shifts->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $shifts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Add New Shift Modal Overlay --}}
<div class="modal fade" id="addShiftModal" tabindex="-1" aria-labelledby="addShiftModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="addShiftModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-clock-fill fs-6"></i>
                    </div>
                    Add New Shift
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="addShiftForm" action="{{ route('shifts.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalShiftErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Shift Name --}}
                        <div class="col-12">
                            <label for="shift_name" class="form-label fw-bold text-dark small">Shift Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="shift_name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Morning Shift" required>
                        </div>

                        {{-- Start Time --}}
                        <div class="col-md-6">
                            <label for="start_time" class="form-label fw-bold text-dark small">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" id="start_time" class="form-control rounded-3 border-light-subtle" value="09:00" required>
                        </div>

                        {{-- End Time --}}
                        <div class="col-md-6">
                            <label for="end_time" class="form-label fw-bold text-dark small">End Time <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" id="end_time" class="form-control rounded-3 border-light-subtle" value="17:00" required>
                        </div>

                        {{-- Late Threshold --}}
                        <div class="col-md-6">
                            <label for="late_mark_after" class="form-label fw-bold text-dark small">Late Threshold (Mark Late After) <span class="text-danger">*</span></label>
                            <input type="time" name="late_mark_after" id="late_mark_after" class="form-control rounded-3 border-light-subtle" value="09:15" required>
                        </div>

                        {{-- Early Check-out Threshold --}}
                        <div class="col-md-6">
                            <label for="early_checkout_before" class="form-label fw-bold text-dark small">Early Check-out Threshold (Mark Early Before) <span class="text-danger">*</span></label>
                            <input type="time" name="early_checkout_before" id="early_checkout_before" class="form-control rounded-3 border-light-subtle" value="16:45" required>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveShiftBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Shift
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addShiftForm = document.getElementById('addShiftForm');
    const saveShiftBtn = document.getElementById('saveShiftBtn');
    const modalShiftErrors = document.getElementById('modalShiftErrors');
    const shiftsTableBody = document.querySelector('#shiftsTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (addShiftForm) {
        addShiftForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveShiftBtn.disabled = true;
            saveShiftBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalShiftErrors.classList.add('d-none');
            modalShiftErrors.innerHTML = '';

            const formData = new FormData(addShiftForm);

            fetch("{{ route('shifts.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveShiftBtn.disabled = false;
                saveShiftBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Shift';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('addShiftModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    addShiftForm.reset();
                    document.getElementById('start_time').value = "09:00";
                    document.getElementById('end_time').value = "17:00";
                    document.getElementById('late_mark_after').value = "09:15";
                    document.getElementById('early_checkout_before').value = "16:45";

                    // Remove empty state row if present
                    const noShiftsRow = document.getElementById('noShiftsRow');
                    if (noShiftsRow) {
                        noShiftsRow.remove();
                    }

                    // Prepend new row
                    const s = data.shift;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'shiftRow_' + s.id;
                    newRow.innerHTML = `
                        <td class="ps-4 fw-bold text-dark">${s.name}</td>
                        <td class="text-secondary small fw-medium">${s.formatted_start_time}</td>
                        <td class="text-secondary small fw-medium">${s.formatted_end_time}</td>
                        <td class="text-secondary small fw-medium">${s.formatted_late_mark_after}</td>
                        <td class="text-secondary small fw-medium">${s.formatted_early_checkout_before}</td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                <a href="${s.edit_url}" class="btn btn-action-edit" title="Edit Shift" aria-label="Edit Shift">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${s.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this shift?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Shift" aria-label="Delete Shift">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    shiftsTableBody.prepend(newRow);

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
                    modalShiftErrors.innerHTML = errHtml;
                    modalShiftErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveShiftBtn.disabled = false;
                saveShiftBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Shift';
                modalShiftErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalShiftErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
