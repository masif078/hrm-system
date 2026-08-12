@extends('layouts.app')

@section('title', 'Job Openings')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Job Openings']
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
                <h3 class="fw-bold text-dark mb-1">Job Openings</h3>
                <p class="text-secondary small mb-0">Manage active and closed recruitment job postings for your organization.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createJobModal">
                <i class="bi bi-plus-lg me-1"></i> Create Job Posting
            </button>
        </div>
    </div>

    {{-- Job Openings Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="jobsTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Job Title</th>
                        <th class="py-3">Department</th>
                        <th class="py-3">Employment Type</th>
                        <th class="py-3">Location</th>
                        <th class="py-3">Vacancies</th>
                        <th class="py-3">Closing Date</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 text-end py-3" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr class="hover-row" id="jobRow_{{ $job->id }}">
                            <td class="ps-4">
                                <span class="fw-bold text-dark d-block">{{ $job->title }}</span>
                            </td>

                            <td class="fw-semibold text-secondary">
                                {{ $job->department->name ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                    {{ $job->employment_type }}
                                </span>
                            </td>

                            <td class="text-secondary small fw-medium">
                                <i class="bi bi-geo-alt-fill text-primary me-1 opacity-75"></i> {{ $job->location }}
                            </td>

                            <td class="fw-bold text-dark">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                    {{ $job->vacancies }}
                                </span>
                            </td>

                            <td class="text-secondary small fw-medium">
                                {{ $job->closing_date ? date('M d, Y', strtotime($job->closing_date)) : 'N/A' }}
                            </td>

                            {{-- Status Badges --}}
                            <td>
                                @if($job->status === 'Open')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Open</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Closed</span>
                                @endif
                            </td>

                            <td class="pe-4 text-end">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                    <a href="{{ route('job-openings.edit', $job->id) }}" class="btn btn-action-edit" title="Edit Job Posting" aria-label="Edit Job Posting">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('job-openings.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Job Posting" aria-label="Delete Job Posting">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noJobsRow">
                            <td colspan="8" class="p-0">
                                <x-empty-state title="No Job Openings Recorded" icon="bi-briefcase" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Create Job Posting Centered Modal Overlay --}}
<div class="modal fade" id="createJobModal" tabindex="-1" aria-labelledby="createJobModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="createJobModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-briefcase-fill fs-6"></i>
                    </div>
                    Create Job Posting
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="createJobForm" action="{{ route('job-openings.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalJobErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Job Title --}}
                        <div class="col-md-6">
                            <label for="title" class="form-label fw-bold text-dark small">Job Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Senior Laravel Developer, HR Specialist" required>
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

                        {{-- Employment Type --}}
                        <div class="col-md-6">
                            <label for="employment_type" class="form-label fw-bold text-dark small">Employment Type <span class="text-danger">*</span></label>
                            <select name="employment_type" id="employment_type" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Full Time" selected>Full Time</option>
                                <option value="Part Time">Part Time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>

                        {{-- Location --}}
                        <div class="col-md-6">
                            <label for="location" class="form-label fw-bold text-dark small">Location <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control rounded-3 border-light-subtle" placeholder="e.g., Islamabad Branch, Lahore HQ, Remote" required>
                        </div>

                        {{-- Vacancies --}}
                        <div class="col-md-4">
                            <label for="vacancies" class="form-label fw-bold text-dark small">Vacancies <span class="text-danger">*</span></label>
                            <input type="number" name="vacancies" id="vacancies" class="form-control rounded-3 border-light-subtle" value="1" min="1" required>
                        </div>

                        {{-- Closing Date --}}
                        <div class="col-md-4">
                            <label for="closing_date" class="form-label fw-bold text-dark small">Closing Date <span class="text-danger">*</span></label>
                            <input type="date" name="closing_date" id="closing_date" class="form-control rounded-3 border-light-subtle" required>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Open" selected>Open</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>

                        {{-- Job Description --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-bold text-dark small">Job Description & Requirements</label>
                            <textarea name="description" id="description" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="Provide key responsibilities, qualifications, and benefits..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="postJobBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Post Job
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const createJobForm = document.getElementById('createJobForm');
    const postJobBtn = document.getElementById('postJobBtn');
    const modalJobErrors = document.getElementById('modalJobErrors');
    const jobsTableBody = document.querySelector('#jobsTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (createJobForm) {
        createJobForm.addEventListener('submit', function (e) {
            e.preventDefault();

            postJobBtn.disabled = true;
            postJobBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Posting...';
            modalJobErrors.classList.add('d-none');
            modalJobErrors.innerHTML = '';

            const formData = new FormData(createJobForm);

            fetch("{{ route('job-openings.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                postJobBtn.disabled = false;
                postJobBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Post Job';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('createJobModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    createJobForm.reset();
                    document.getElementById('vacancies').value = "1";
                    document.getElementById('status').value = "Open";

                    // Remove empty state row if present
                    const noJobsRow = document.getElementById('noJobsRow');
                    if (noJobsRow) {
                        noJobsRow.remove();
                    }

                    // Prepend new row
                    const j = data.job;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusBadgeClass = j.status === 'Open' 
                        ? 'bg-success-subtle text-success border border-success-subtle' 
                        : 'bg-danger-subtle text-danger border border-danger-subtle';

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'jobRow_' + j.id;
                    newRow.innerHTML = `
                        <td class="ps-4">
                            <span class="fw-bold text-dark d-block">${j.title}</span>
                        </td>
                        <td class="fw-semibold text-secondary">${j.department_name}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                ${j.employment_type}
                            </span>
                        </td>
                        <td class="text-secondary small fw-medium">
                            <i class="bi bi-geo-alt-fill text-primary me-1 opacity-75"></i> ${j.location}
                        </td>
                        <td class="fw-bold text-dark">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                ${j.vacancies}
                            </span>
                        </td>
                        <td class="text-secondary small fw-medium">${j.closing_date}</td>
                        <td>
                            <span class="badge ${statusBadgeClass} rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                ${j.status}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                <a href="${j.edit_url}" class="btn btn-action-edit" title="Edit Job Posting" aria-label="Edit Job Posting">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${j.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job post?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Job Posting" aria-label="Delete Job Posting">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    jobsTableBody.prepend(newRow);

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
                    modalJobErrors.innerHTML = errHtml;
                    modalJobErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                postJobBtn.disabled = false;
                postJobBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Post Job';
                modalJobErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalJobErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
