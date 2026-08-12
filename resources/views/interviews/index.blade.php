@extends('layouts.app')

@section('title', 'Interviews Schedule')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Interviews Schedule']
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
                <h3 class="fw-bold text-dark mb-1">Interviews Schedule</h3>
                <p class="text-secondary small mb-0">List of scheduled applicant calls, meetings, and recruitment evaluations.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#scheduleInterviewModal">
                <i class="bi bi-calendar-plus-fill me-1"></i> + Schedule Interview
            </button>
        </div>
    </div>

    {{-- Interviews Schedule Table Card (No Horizontal Scrollbar, Compact SaaS Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="interviewsTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">Candidate</th>
                        <th class="px-2 py-2.5">Job Post</th>
                        <th class="px-2 py-2.5">Interview Date</th>
                        <th class="px-2 py-2.5">Time</th>
                        <th class="px-2 py-2.5">Interviewer</th>
                        <th class="px-2 py-2.5">Meeting Link</th>
                        <th class="pe-3 text-end py-2.5" width="180">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interviews as $interview)
                        <tr class="hover-row" id="interviewRow_{{ $interview->id }}">
                            {{-- Candidate Column with Avatar + Spacing --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <div class="d-flex align-items-center gap-3">
                                    <x-avatar :name="$interview->application->candidate->full_name ?? 'C'" size="sm" class="flex-shrink-0" />
                                    <div>
                                        <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">{{ $interview->application->candidate->full_name ?? 'N/A' }}</span>
                                        <small class="text-secondary opacity-75 d-block text-nowrap" style="font-size: 0.725rem; white-space: nowrap;">{{ $interview->application->candidate->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Job Post --}}
                            <td class="px-2 py-2.5 text-dark fw-bold align-middle">
                                {{ $interview->application->jobOpening->title ?? 'N/A' }}
                            </td>

                            {{-- Interview Date --}}
                            <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                {{ date('M d, Y', strtotime($interview->date)) }}
                            </td>

                            {{-- Time --}}
                            <td class="px-2 py-2.5 text-secondary fw-semibold align-middle text-nowrap" style="white-space: nowrap;">
                                <i class="bi bi-clock-fill text-primary me-1 opacity-75"></i> {{ date('h:i A', strtotime($interview->time)) }}
                            </td>

                            {{-- Interviewer --}}
                            <td class="px-2 py-2.5 text-dark fw-semibold align-middle text-nowrap" style="white-space: nowrap;">
                                {{ $interview->interviewer->first_name ?? '' }} {{ $interview->interviewer->last_name ?? 'N/A' }}
                            </td>

                            {{-- Meeting Link Video Button --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @if($interview->meeting_link)
                                    <a href="{{ $interview->meeting_link }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-3 px-2.5 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-2xs" title="Join Meeting" aria-label="Join Meeting">
                                        <i class="bi bi-camera-video-fill fs-6"></i> Join Meeting
                                    </a>
                                @else
                                    <span class="text-secondary opacity-50 small">N/A</span>
                                @endif
                            </td>

                            {{-- Action Process Pipeline Button --}}
                            <td class="pe-3 py-2.5 text-end align-middle">
                                <a href="{{ route('applications.show', $interview->application_id) }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-2xs" title="Process Application" aria-label="Process Application">
                                    <i class="bi bi-file-earmark-person-fill fs-6"></i> Process Application
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="noInterviewsRow">
                            <td colspan="7" class="p-0">
                                <x-empty-state title="No Scheduled Interviews Recorded" icon="bi-calendar-event" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Schedule Interview Centered Modal Overlay --}}
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1" aria-labelledby="scheduleInterviewModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="scheduleInterviewModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-calendar-event-fill fs-6"></i>
                    </div>
                    Schedule Interview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="scheduleInterviewForm" action="{{ route('interviews.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalInterviewErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Candidate Dropdown --}}
                        <div class="col-md-6">
                            <label for="application_id" class="form-label fw-bold text-dark small">Candidate <span class="text-danger">*</span></label>
                            <select name="application_id" id="application_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Candidate...</option>
                                @foreach($applications ?? [] as $app)
                                    <option value="{{ $app->id }}" data-job-title="{{ $app->jobOpening->title ?? 'N/A' }}">
                                        {{ $app->candidate->full_name ?? 'N/A' }} ({{ $app->jobOpening->title ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Job Post Display / Auto-filled --}}
                        <div class="col-md-6">
                            <label for="job_post_display" class="form-label fw-bold text-dark small">Job Post</label>
                            <input type="text" id="job_post_display" class="form-control rounded-3 border-light-subtle bg-light" placeholder="Auto-filled from Candidate Application" readonly>
                        </div>

                        {{-- Interview Date --}}
                        <div class="col-md-6">
                            <label for="date" class="form-label fw-bold text-dark small">Interview Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Time Picker --}}
                        <div class="col-md-6">
                            <label for="time" class="form-label fw-bold text-dark small">Time <span class="text-danger">*</span></label>
                            <input type="time" name="time" id="time" class="form-control rounded-3 border-light-subtle" value="10:00" required>
                        </div>

                        {{-- Interviewer Dropdown --}}
                        <div class="col-md-6">
                            <label for="interviewer_id" class="form-label fw-bold text-dark small">Interviewer <span class="text-danger">*</span></label>
                            <select name="interviewer_id" id="interviewer_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Interviewer...</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}" {{ auth()->user()->employee_id == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-'.$emp->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Meeting Link URL --}}
                        <div class="col-md-6">
                            <label for="meeting_link" class="form-label fw-bold text-dark small">Meeting Link (Google Meet / Zoom URL)</label>
                            <input type="url" name="meeting_link" id="meeting_link" class="form-control rounded-3 border-light-subtle" placeholder="https://meet.google.com/abc-defg-hij">
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="scheduleBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Schedule
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const scheduleInterviewForm = document.getElementById('scheduleInterviewForm');
    const scheduleBtn = document.getElementById('scheduleBtn');
    const modalInterviewErrors = document.getElementById('modalInterviewErrors');
    const interviewsTableBody = document.querySelector('#interviewsTable tbody');
    const alertContainer = document.getElementById('alertContainer');
    const appSelect = document.getElementById('application_id');
    const jobPostDisplay = document.getElementById('job_post_display');

    // Auto-fill Job Post display when candidate application is selected
    if (appSelect && jobPostDisplay) {
        appSelect.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                const jobTitle = selectedOpt.getAttribute('data-job-title') || "N/A";
                jobPostDisplay.value = jobTitle;
            } else {
                jobPostDisplay.value = "";
            }
        });
    }

    if (scheduleInterviewForm) {
        scheduleInterviewForm.addEventListener('submit', function (e) {
            e.preventDefault();

            scheduleBtn.disabled = true;
            scheduleBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Scheduling...';
            modalInterviewErrors.classList.add('d-none');
            modalInterviewErrors.innerHTML = '';

            const formData = new FormData(scheduleInterviewForm);

            fetch("{{ route('interviews.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                scheduleBtn.disabled = false;
                scheduleBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Schedule';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('scheduleInterviewModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    scheduleInterviewForm.reset();
                    document.getElementById('date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('time').value = "10:00";

                    // Remove empty state row if present
                    const noInterviewsRow = document.getElementById('noInterviewsRow');
                    if (noInterviewsRow) {
                        noInterviewsRow.remove();
                    }

                    // Prepend new row
                    const i = data.interview;

                    let meetingLinkHtml = '<span class="text-secondary opacity-50 small">N/A</span>';
                    if (i.meeting_link) {
                        meetingLinkHtml = `
                            <a href="${i.meeting_link}" target="_blank" class="btn btn-outline-primary btn-sm rounded-3 px-2.5 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-2xs" title="Join Meeting" aria-label="Join Meeting">
                                <i class="bi bi-camera-video-fill fs-6"></i> Join Meeting
                            </a>
                        `;
                    }

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'interviewRow_' + i.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${i.candidate_name.charAt(0)}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">${i.candidate_name}</span>
                                    <small class="text-secondary opacity-75 d-block text-nowrap" style="font-size: 0.725rem; white-space: nowrap;">${i.candidate_email}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-2.5 text-dark fw-bold align-middle">${i.job_title}</td>
                        <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">${i.interview_date}</td>
                        <td class="px-2 py-2.5 text-secondary fw-semibold align-middle text-nowrap" style="white-space: nowrap;">
                            <i class="bi bi-clock-fill text-primary me-1 opacity-75"></i> ${i.interview_time}
                        </td>
                        <td class="px-2 py-2.5 text-dark fw-semibold align-middle text-nowrap" style="white-space: nowrap;">${i.interviewer_name}</td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">${meetingLinkHtml}</td>
                        <td class="pe-3 py-2.5 text-end align-middle">
                            <a href="${i.process_url}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-2xs" title="Process Application" aria-label="Process Application">
                                <i class="bi bi-file-earmark-person-fill fs-6"></i> Process Application
                            </a>
                        </td>
                    `;
                    interviewsTableBody.prepend(newRow);

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
                    modalInterviewErrors.innerHTML = errHtml;
                    modalInterviewErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                scheduleBtn.disabled = false;
                scheduleBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Schedule';
                modalInterviewErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalInterviewErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
