@extends('layouts.app')

@section('title', 'Holiday Management')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Holiday Management']
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
                <h3 class="fw-bold text-dark mb-1">Holiday Management</h3>
                <p class="text-secondary small mb-0">Define and manage company holidays, public observances, and calendar events.</p>
            </div>
            <button type="button" class="btn btn-success fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                <i class="bi bi-plus-lg me-1"></i> Add New Holiday
            </button>
        </div>
    </div>

    {{-- Holiday Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="holidaysTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Holiday Name</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Type</th>
                        <th class="pe-4 text-end py-3" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $holiday)
                        <tr class="hover-row" id="holidayRow_{{ $holiday->id }}">
                            <td class="ps-4 fw-bold text-dark">{{ $holiday->name }}</td>
                            <td class="text-secondary small fw-medium">{{ \Carbon\Carbon::parse($holiday->date)->format('F d, Y') }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                    {{ $holiday->type }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                    <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-action-edit" title="Edit Holiday" aria-label="Edit Holiday">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this holiday?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Holiday" aria-label="Delete Holiday">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noHolidaysRow">
                            <td colspan="4" class="p-0">
                                <x-empty-state title="No Holidays Defined Yet" icon="bi-calendar-event" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($holidays->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $holidays->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Add New Holiday Modal Overlay --}}
<div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="addHolidayModalLabel">
                    <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-calendar-event-fill fs-6"></i>
                    </div>
                    Add New Holiday
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="addHolidayForm" action="{{ route('holidays.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalHolidayErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Holiday Name --}}
                        <div class="col-md-6">
                            <label for="holiday_name" class="form-label fw-bold text-dark small">Holiday Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="holiday_name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Independence Day" required>
                        </div>

                        {{-- Date --}}
                        <div class="col-md-6">
                            <label for="holiday_date" class="form-label fw-bold text-dark small">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="holiday_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Holiday Type --}}
                        <div class="col-12">
                            <label for="holiday_type" class="form-label fw-bold text-dark small">Holiday Type <span class="text-danger">*</span></label>
                            <select name="type" id="holiday_type" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Public Holiday" selected>Public Holiday</option>
                                <option value="National Holiday">National Holiday</option>
                                <option value="Company Holiday">Company Holiday</option>
                                <option value="Religious Holiday">Religious Holiday</option>
                                <option value="Optional Holiday">Optional Holiday</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveHolidayBtn" class="btn btn-success rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Holiday
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addHolidayForm = document.getElementById('addHolidayForm');
    const saveHolidayBtn = document.getElementById('saveHolidayBtn');
    const modalHolidayErrors = document.getElementById('modalHolidayErrors');
    const holidaysTableBody = document.querySelector('#holidaysTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (addHolidayForm) {
        addHolidayForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveHolidayBtn.disabled = true;
            saveHolidayBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalHolidayErrors.classList.add('d-none');
            modalHolidayErrors.innerHTML = '';

            const formData = new FormData(addHolidayForm);

            fetch("{{ route('holidays.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveHolidayBtn.disabled = false;
                saveHolidayBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Holiday';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('addHolidayModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    addHolidayForm.reset();
                    document.getElementById('holiday_date').value = "{{ date('Y-m-d') }}";

                    // Remove empty state row if present
                    const noHolidaysRow = document.getElementById('noHolidaysRow');
                    if (noHolidaysRow) {
                        noHolidaysRow.remove();
                    }

                    // Prepend new row
                    const h = data.holiday;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'holidayRow_' + h.id;
                    newRow.innerHTML = `
                        <td class="ps-4 fw-bold text-dark">${h.name}</td>
                        <td class="text-secondary small fw-medium">${h.formatted_date}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                ${h.type}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                <a href="${h.edit_url}" class="btn btn-action-edit" title="Edit Holiday" aria-label="Edit Holiday">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${h.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this holiday?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Holiday" aria-label="Delete Holiday">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    holidaysTableBody.prepend(newRow);

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
                    modalHolidayErrors.innerHTML = errHtml;
                    modalHolidayErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveHolidayBtn.disabled = false;
                saveHolidayBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Holiday';
                modalHolidayErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalHolidayErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
