@extends('layouts.app')

@section('title', 'Attendance Management')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Attendance Management']
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
                <h3 class="fw-bold text-dark mb-1">Attendance Management</h3>
                <p class="text-secondary small mb-0">Manage employee attendance logs, check-in schedules, and daily tracking.</p>
            </div>

            @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#markAttendanceModal">
                    <i class="bi bi-plus-lg me-1"></i> Mark Attendance
                </button>
            @else
                <div class="d-flex gap-2">
                    @if(!$todayAttendance)
                        <form method="POST" action="{{ route('employee.attendances.checkin') }}">
                            @csrf
                            <button class="btn btn-success fw-bold px-4 py-2 rounded-3 text-white shadow-sm">Check In</button>
                        </form>
                    @elseif(!$todayAttendance->check_out)
                        <form method="POST" action="{{ route('employee.attendances.checkout') }}">
                            @csrf
                            <button class="btn btn-danger fw-bold px-4 py-2 rounded-3 text-white shadow-sm">Check Out</button>
                        </form>
                    @else
                        <span class="btn btn-secondary disabled fw-semibold px-3 rounded-3">Attendance Done for Today</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Stat Cards Row --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Total Records" 
                :value="$attendances->total()" 
                color="blue" 
                icon="bi-journal-text" 
                trend="Total Logs" 
                trendType="neutral" 
                id="statTotalRecords"
            />
        </div>

        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Present" 
                :value="$presentCount ?? 0" 
                color="green" 
                icon="bi-person-check-fill" 
                trend="On Duty" 
                trendType="up" 
                id="statPresentCount"
            />
        </div>

        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Absent" 
                :value="$absentCount ?? 0" 
                color="amber" 
                icon="bi-person-x-fill" 
                trend="Not Present" 
                trendType="down" 
                id="statAbsentCount"
            />
        </div>

        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Late Arrival" 
                :value="$lateCount ?? 0" 
                color="purple" 
                icon="bi-clock-fill" 
                trend="Late Marked" 
                trendType="down" 
                id="statLateCount"
            />
        </div>
    </div>

    {{-- Search & Filters --}}
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
                <label class="form-label small fw-semibold text-secondary mb-1">Date</label>
                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="form-control rounded-3 border-light-subtle shadow-2xs">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Status</label>
                <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs">
                    <option value="">All Status</option>
                    <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                    <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                    <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                    <option value="Leave" {{ request('status') == 'Leave' ? 'selected' : '' }}>Leave</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold text-white shadow-sm py-2">Filter</button>
                <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold py-2">Reset</a>
            </div>
        </form>
    </div>

    {{-- Attendance Data Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="attendancesTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Employee</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Check In</th>
                        <th class="py-3">Check Out</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 text-end py-3" width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr class="hover-row" id="attendanceRow_{{ $attendance->id }}">
                            <td class="ps-4 fw-bold text-secondary">#{{ $attendance->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="$attendance->employee->first_name . ' ' . $attendance->employee->last_name" size="sm" />
                                    <span class="fw-bold text-dark">{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</span>
                                </div>
                            </td>
                            <td class="text-secondary small">{{ $attendance->date }}</td>
                            <td class="text-secondary small fw-medium">{{ $attendance->formatted_check_in }}</td>
                            <td class="text-secondary small fw-medium">{{ $attendance->formatted_check_out }}</td>
                            <td>
                                <x-status-badge :status="$attendance->status" />
                            </td>
                            <td class="pe-4 text-end">
                                @if(auth()->user()->role === 'admin')
                                    <div class="d-inline-flex gap-1.5">
                                        <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-action-view" title="View Attendance" aria-label="View Attendance">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-action-edit" title="Edit Attendance" aria-label="Edit Attendance">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete" title="Delete Attendance" aria-label="Delete Attendance" onclick="return confirm('Delete Attendance Record?')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <x-status-badge :status="$attendance->status" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="noAttendancesRow">
                            <td colspan="7" class="p-0">
                                <x-empty-state title="No Attendance Records Found." icon="bi-clock-history" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $attendances->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

@if(auth()->user()->role === 'admin')
{{-- Mark Attendance Modal Overlay --}}
<div class="modal fade" id="markAttendanceModal" tabindex="-1" aria-labelledby="markAttendanceModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="markAttendanceModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-clock-history fs-6"></i>
                    </div>
                    Mark Attendance Record
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="markAttendanceForm" action="{{ route('attendances.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalAttendanceErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

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

                        {{-- Date --}}
                        <div class="col-md-6">
                            <label for="modal_date" class="form-label fw-bold text-dark small">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="modal_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Check In Time --}}
                        <div class="col-md-6">
                            <label for="check_in" class="form-label fw-bold text-dark small">Check In Time</label>
                            <input type="time" name="check_in" id="check_in" class="form-control rounded-3 border-light-subtle" value="09:00">
                        </div>

                        {{-- Check Out Time --}}
                        <div class="col-md-6">
                            <label for="check_out" class="form-label fw-bold text-dark small">Check Out Time</label>
                            <input type="time" name="check_out" id="check_out" class="form-control rounded-3 border-light-subtle" value="17:00">
                        </div>

                        {{-- Status --}}
                        <div class="col-12">
                            <label for="modal_status" class="form-label fw-bold text-dark small">Attendance Status <span class="text-danger">*</span></label>
                            <select name="status" id="modal_status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Present" selected>Present</option>
                                <option value="Absent">Absent</option>
                                <option value="Late">Late</option>
                                <option value="Leave">Leave</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveAttendanceBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Attendance
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const markAttendanceForm = document.getElementById('markAttendanceForm');
    const saveAttendanceBtn = document.getElementById('saveAttendanceBtn');
    const modalAttendanceErrors = document.getElementById('modalAttendanceErrors');
    const attendancesTableBody = document.querySelector('#attendancesTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (markAttendanceForm) {
        markAttendanceForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveAttendanceBtn.disabled = true;
            saveAttendanceBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalAttendanceErrors.classList.add('d-none');
            modalAttendanceErrors.innerHTML = '';

            const formData = new FormData(markAttendanceForm);

            fetch("{{ route('attendances.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveAttendanceBtn.disabled = false;
                saveAttendanceBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Attendance';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('markAttendanceModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    markAttendanceForm.reset();
                    document.getElementById('modal_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('check_in').value = "09:00";
                    document.getElementById('check_out').value = "17:00";

                    // Remove empty state row if present
                    const noAttendancesRow = document.getElementById('noAttendancesRow');
                    if (noAttendancesRow) {
                        noAttendancesRow.remove();
                    }

                    // Prepend new row
                    const att = data.attendance;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusBadgeClass = 'bg-success-subtle text-success border-success-subtle';
                    if (att.status === 'Absent') statusBadgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                    else if (att.status === 'Late') statusBadgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                    else if (att.status === 'Leave') statusBadgeClass = 'bg-info-subtle text-info border-info-subtle';

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'attendanceRow_' + att.id;
                    newRow.innerHTML = `
                        <td class="ps-4 fw-bold text-secondary">#${att.id}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${att.employee_name.charAt(0)}
                                </div>
                                <span class="fw-bold text-dark">${att.employee_name}</span>
                            </div>
                        </td>
                        <td class="text-secondary small">${att.date}</td>
                        <td class="text-secondary small fw-medium">${att.formatted_check_in}</td>
                        <td class="text-secondary small fw-medium">${att.formatted_check_out}</td>
                        <td>
                            <span class="badge border ${statusBadgeClass} rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                ${att.status}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex gap-1.5">
                                <a href="${att.show_url}" class="btn btn-action-view" title="View Attendance" aria-label="View Attendance">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="${att.edit_url}" class="btn btn-action-edit" title="Edit Attendance" aria-label="Edit Attendance">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${att.destroy_url}" method="POST" class="d-inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Attendance" aria-label="Delete Attendance" onclick="return confirm('Delete Attendance Record?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    attendancesTableBody.prepend(newRow);

                    // Update Stat Card Counts
                    const totalElem = document.querySelector('#statTotalRecords .stat-card-value');
                    if (totalElem) {
                        let count = parseInt(totalElem.textContent.replace(/,/g, '')) || 0;
                        totalElem.textContent = (count + 1).toLocaleString();
                    }

                    if (att.status === 'Present') {
                        const presentElem = document.querySelector('#statPresentCount .stat-card-value');
                        if (presentElem) {
                            let count = parseInt(presentElem.textContent.replace(/,/g, '')) || 0;
                            presentElem.textContent = (count + 1).toLocaleString();
                        }
                    } else if (att.status === 'Absent') {
                        const absentElem = document.querySelector('#statAbsentCount .stat-card-value');
                        if (absentElem) {
                            let count = parseInt(absentElem.textContent.replace(/,/g, '')) || 0;
                            absentElem.textContent = (count + 1).toLocaleString();
                        }
                    } else if (att.status === 'Late') {
                        const lateElem = document.querySelector('#statLateCount .stat-card-value');
                        if (lateElem) {
                            let count = parseInt(lateElem.textContent.replace(/,/g, '')) || 0;
                            lateElem.textContent = (count + 1).toLocaleString();
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
                    modalAttendanceErrors.innerHTML = errHtml;
                    modalAttendanceErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveAttendanceBtn.disabled = false;
                saveAttendanceBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Attendance';
                modalAttendanceErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalAttendanceErrors.classList.remove('d-none');
            });
        });
    }
});
</script>
@endif

@endsection
