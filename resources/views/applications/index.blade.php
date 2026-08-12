@extends('layouts.app')

@section('title', 'Job Applications')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Job Applications']
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
                <h3 class="fw-bold text-dark mb-1">Job Applications</h3>
                <p class="text-secondary small mb-0">Track candidates applying against active vacancies and manage interview pipelines.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createApplicationModal">
                <i class="bi bi-plus-lg me-1"></i> New Job Application
            </button>
        </div>
    </div>

    {{-- Job Applications Table Card (No Horizontal Scrollbar, Compact SaaS Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="applicationsTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">Candidate</th>
                        <th class="px-2 py-2.5">Job Title</th>
                        <th class="px-2 py-2.5">Department</th>
                        <th class="px-2 py-2.5">Applied Date</th>
                        <th class="px-2 py-2.5">Current Stage</th>
                        <th class="pe-3 text-end py-2.5" width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                        <tr class="hover-row" id="applicationRow_{{ $app->id }}">
                            {{-- Candidate Column with Avatar + Spacing --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <div class="d-flex align-items-center gap-3">
                                    <x-avatar :name="$app->candidate->full_name ?? 'C'" size="sm" class="flex-shrink-0" />
                                    <div>
                                        <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">{{ $app->candidate->full_name ?? 'N/A' }}</span>
                                        <small class="text-secondary opacity-75 d-block text-nowrap" style="font-size: 0.725rem; white-space: nowrap;">{{ $app->candidate->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Job Title --}}
                            <td class="px-2 py-2.5 text-dark fw-bold align-middle">
                                {{ $app->jobOpening->title ?? 'N/A' }}
                            </td>

                            {{-- Department --}}
                            <td class="px-2 py-2.5 text-secondary fw-semibold align-middle text-nowrap" style="white-space: nowrap;">
                                {{ $app->jobOpening->department->name ?? 'N/A' }}
                            </td>

                            {{-- Applied Date --}}
                            <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                {{ $app->created_at ? $app->created_at->format('M d, Y') : 'N/A' }}
                            </td>

                            {{-- Soft Rounded Pill Status Badges for Stage --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @php
                                    $st = $app->status;
                                    $statusClass = 'bg-info-subtle text-info border border-info-subtle';
                                    if (in_array($st, ['Hired', 'Accepted', 'Offer Sent'])) {
                                        $statusClass = 'bg-success-subtle text-success border border-success-subtle';
                                    } elseif (in_array($st, ['Rejected'])) {
                                        $statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                    } elseif (in_array($st, ['Shortlisted', 'HR Interview', 'Technical Interview', 'Final Interview'])) {
                                        $statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                    {{ $app->status }}
                                </span>
                            </td>

                            {{-- Process Pipeline Action Button --}}
                            <td class="pe-3 py-2.5 text-end align-middle">
                                <a href="{{ route('applications.show', $app->id) }}" class="btn btn-outline-primary btn-sm rounded-3 px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-2xs" title="Process Pipeline" aria-label="Process Pipeline">
                                    <i class="bi bi-diagram-3-fill fs-6"></i> Process Pipeline
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="noApplicationsRow">
                            <td colspan="6" class="p-0">
                                <x-empty-state title="No Job Applications Recorded" icon="bi-file-earmark-person" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- New Job Application Centered Modal Overlay --}}
<div class="modal fade" id="createApplicationModal" tabindex="-1" aria-labelledby="createApplicationModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="createApplicationModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-file-earmark-person-fill fs-6"></i>
                    </div>
                    New Job Application
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="createApplicationForm" action="{{ route('applications.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalApplicationErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Candidate Dropdown --}}
                        <div class="col-md-6">
                            <label for="candidate_id" class="form-label fw-bold text-dark small">Candidate <span class="text-danger">*</span></label>
                            <select name="candidate_id" id="candidate_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Candidate...</option>
                                @foreach($candidates ?? [] as $cand)
                                    <option value="{{ $cand->id }}">{{ $cand->full_name }} ({{ $cand->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Job Title Dropdown --}}
                        <div class="col-md-6">
                            <label for="job_opening_id" class="form-label fw-bold text-dark small">Job Title <span class="text-danger">*</span></label>
                            <select name="job_opening_id" id="job_opening_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Job Opening...</option>
                                @foreach($jobs ?? [] as $job)
                                    <option value="{{ $job->id }}" data-department-id="{{ $job->department_id }}">
                                        {{ $job->title }} ({{ $job->department->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Department Dropdown --}}
                        <div class="col-md-6">
                            <label for="department_id" class="form-label fw-bold text-dark small">Department <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Department...</option>
                                @foreach($departments ?? [] as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Applied Date Picker --}}
                        <div class="col-md-6">
                            <label for="applied_date" class="form-label fw-bold text-dark small">Applied Date <span class="text-danger">*</span></label>
                            <input type="date" name="applied_date" id="applied_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Current Stage Dropdown --}}
                        <div class="col-12">
                            <label for="status" class="form-label fw-bold text-dark small">Current Stage <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Applied" selected>Applied</option>
                                <option value="Shortlisted">Shortlisted</option>
                                <option value="HR Interview">HR Interview</option>
                                <option value="Technical Interview">Technical Interview</option>
                                <option value="Final Interview">Final Interview</option>
                                <option value="Offer Sent">Offer Sent</option>
                                <option value="Accepted">Accepted</option>
                                <option value="Hired">Hired</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitApplicationBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Submit Application
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const createApplicationForm = document.getElementById('createApplicationForm');
    const submitApplicationBtn = document.getElementById('submitApplicationBtn');
    const modalApplicationErrors = document.getElementById('modalApplicationErrors');
    const applicationsTableBody = document.querySelector('#applicationsTable tbody');
    const alertContainer = document.getElementById('alertContainer');
    const jobSelect = document.getElementById('job_opening_id');
    const deptSelect = document.getElementById('department_id');

    // Auto-select department when job opening is chosen
    if (jobSelect && deptSelect) {
        jobSelect.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                const deptId = selectedOpt.getAttribute('data-department-id');
                if (deptId) {
                    deptSelect.value = deptId;
                }
            }
        });
    }

    if (createApplicationForm) {
        createApplicationForm.addEventListener('submit', function (e) {
            e.preventDefault();

            submitApplicationBtn.disabled = true;
            submitApplicationBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';
            modalApplicationErrors.classList.add('d-none');
            modalApplicationErrors.innerHTML = '';

            const formData = new FormData(createApplicationForm);

            fetch("{{ route('applications.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitApplicationBtn.disabled = false;
                submitApplicationBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Submit Application';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('createApplicationModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    createApplicationForm.reset();
                    document.getElementById('applied_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('status').value = "Applied";

                    // Remove empty state row if present
                    const noApplicationsRow = document.getElementById('noApplicationsRow');
                    if (noApplicationsRow) {
                        noApplicationsRow.remove();
                    }

                    // Prepend new row
                    const a = data.application;

                    let statusClass = 'bg-info-subtle text-info border border-info-subtle';
                    if (['Hired', 'Accepted', 'Offer Sent'].includes(a.status)) {
                        statusClass = 'bg-success-subtle text-success border border-success-subtle';
                    } else if (a.status === 'Rejected') {
                        statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                    } else if (['Shortlisted', 'HR Interview', 'Technical Interview', 'Final Interview'].includes(a.status)) {
                        statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                    }

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'applicationRow_' + a.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${a.candidate_name.charAt(0)}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">${a.candidate_name}</span>
                                    <small class="text-secondary opacity-75 d-block text-nowrap" style="font-size: 0.725rem; white-space: nowrap;">${a.candidate_email}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-2.5 text-dark fw-bold align-middle">${a.job_title}</td>
                        <td class="px-2 py-2.5 text-secondary fw-semibold align-middle text-nowrap" style="white-space: nowrap;">${a.department_name}</td>
                        <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">${a.applied_date}</td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge ${statusClass} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${a.status}
                            </span>
                        </td>
                        <td class="pe-3 py-2.5 text-end align-middle">
                            <a href="${a.show_url}" class="btn btn-outline-primary btn-sm rounded-3 px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-2xs" title="Process Pipeline" aria-label="Process Pipeline">
                                <i class="bi bi-diagram-3-fill fs-6"></i> Process Pipeline
                            </a>
                        </td>
                    `;
                    applicationsTableBody.prepend(newRow);

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
                    modalApplicationErrors.innerHTML = errHtml;
                    modalApplicationErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                submitApplicationBtn.disabled = false;
                submitApplicationBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Submit Application';
                modalApplicationErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalApplicationErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
