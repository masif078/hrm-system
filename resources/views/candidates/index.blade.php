@extends('layouts.app')

@section('title', 'Candidates')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Candidates']
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
                <h3 class="fw-bold text-dark mb-1">Candidates Pipeline</h3>
                <p class="text-secondary small mb-0">Track applicants, qualifications, and recruitment pipelines across active job postings.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addCandidateModal">
                <i class="bi bi-person-plus-fill me-1"></i> Add Candidate
            </button>
        </div>
    </div>

    {{-- Candidates Table Card (No Horizontal Scrollbar, Compact SaaS Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="candidatesTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">Full Name</th>
                        <th class="px-2 py-2.5">Email</th>
                        <th class="px-2 py-2.5">Phone</th>
                        <th class="px-2 py-2.5">Experience</th>
                        <th class="px-2 py-2.5">Qualification</th>
                        <th class="px-2 py-2.5">Source</th>
                        <th class="px-2 py-2.5">Status</th>
                        <th class="pe-3 text-end py-2.5" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidates as $candidate)
                        <tr class="hover-row" id="candidateRow_{{ $candidate->id }}">
                            {{-- Full Name --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="$candidate->full_name" size="sm" class="flex-shrink-0" />
                                    <div>
                                        <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">{{ $candidate->full_name }}</span>
                                        @if($candidate->resume)
                                            <small><a href="{{ asset('storage/' . $candidate->resume) }}" target="_blank" class="text-primary text-decoration-none small"><i class="bi bi-file-earmark-text me-0.5"></i> Resume</a></small>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Email --}}
                            <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                {{ $candidate->email }}
                            </td>

                            {{-- Phone --}}
                            <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                {{ $candidate->phone ?: 'N/A' }}
                            </td>

                            {{-- Experience --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                    {{ $candidate->experience }} years
                                </span>
                            </td>

                            {{-- Qualification --}}
                            <td class="px-2 py-2.5 text-dark fw-semibold align-middle">
                                {{ $candidate->qualification ?: 'N/A' }}
                            </td>

                            {{-- Source --}}
                            <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                <span class="badge bg-light text-dark border border-secondary-subtle rounded-pill px-2.5 py-1">
                                    {{ $candidate->source ?: 'N/A' }}
                                </span>
                            </td>

                            {{-- Soft Rounded Pill Status Badges --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @php
                                    $st = $candidate->status;
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
                                    {{ $candidate->status }}
                                </span>
                            </td>

                            {{-- Action Icons with 12px gap --}}
                            <td class="pe-3 py-2.5 text-end align-middle">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                    <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-action-edit" title="Edit Candidate" aria-label="Edit Candidate">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this candidate record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Candidate" aria-label="Delete Candidate">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noCandidatesRow">
                            <td colspan="8" class="p-0">
                                <x-empty-state title="No Candidates Found" icon="bi-people" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Add Candidate Centered Modal Overlay --}}
<div class="modal fade" id="addCandidateModal" tabindex="-1" aria-labelledby="addCandidateModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="addCandidateModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-person-plus-fill fs-6"></i>
                    </div>
                    Add Candidate
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="addCandidateForm" action="{{ route('candidates.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalCandidateErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Full Name --}}
                        <div class="col-md-6">
                            <label for="full_name" class="form-label fw-bold text-dark small">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Ali Ahmed, Sara Khan" required>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold text-dark small">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control rounded-3 border-light-subtle" placeholder="e.g. candidate@example.com" required>
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-bold text-dark small">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control rounded-3 border-light-subtle" placeholder="e.g. +92 300 1234567">
                        </div>

                        {{-- Experience --}}
                        <div class="col-md-6">
                            <label for="experience" class="form-label fw-bold text-dark small">Experience (Years)</label>
                            <input type="number" step="0.5" name="experience" id="experience" class="form-control rounded-3 border-light-subtle" value="0" min="0" placeholder="e.g. 3">
                        </div>

                        {{-- Qualification --}}
                        <div class="col-md-6">
                            <label for="qualification" class="form-label fw-bold text-dark small">Qualification</label>
                            <input type="text" name="qualification" id="qualification" class="form-control rounded-3 border-light-subtle" placeholder="e.g. BS Computer Science, MBA">
                        </div>

                        {{-- Source --}}
                        <div class="col-md-6">
                            <label for="source" class="form-label fw-bold text-dark small">Source</label>
                            <select name="source" id="source" class="form-select rounded-3 border-light-subtle">
                                <option value="LinkedIn" selected>LinkedIn</option>
                                <option value="Company Website">Company Website</option>
                                <option value="Referral">Referral</option>
                                <option value="Job Board">Job Board</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="col-12">
                            <label for="status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
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
                    <button type="submit" id="addCandidateBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Add Candidate
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addCandidateForm = document.getElementById('addCandidateForm');
    const addCandidateBtn = document.getElementById('addCandidateBtn');
    const modalCandidateErrors = document.getElementById('modalCandidateErrors');
    const candidatesTableBody = document.querySelector('#candidatesTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (addCandidateForm) {
        addCandidateForm.addEventListener('submit', function (e) {
            e.preventDefault();

            addCandidateBtn.disabled = true;
            addCandidateBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Adding...';
            modalCandidateErrors.classList.add('d-none');
            modalCandidateErrors.innerHTML = '';

            const formData = new FormData(addCandidateForm);

            fetch("{{ route('candidates.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                addCandidateBtn.disabled = false;
                addCandidateBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Add Candidate';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('addCandidateModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    addCandidateForm.reset();
                    document.getElementById('experience').value = "0";
                    document.getElementById('status').value = "Applied";

                    // Remove empty state row if present
                    const noCandidatesRow = document.getElementById('noCandidatesRow');
                    if (noCandidatesRow) {
                        noCandidatesRow.remove();
                    }

                    // Prepend new row
                    const c = data.candidate;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusClass = 'bg-info-subtle text-info border border-info-subtle';
                    if (['Hired', 'Accepted', 'Offer Sent'].includes(c.status)) {
                        statusClass = 'bg-success-subtle text-success border border-success-subtle';
                    } else if (c.status === 'Rejected') {
                        statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                    } else if (['Shortlisted', 'HR Interview', 'Technical Interview', 'Final Interview'].includes(c.status)) {
                        statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                    }

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'candidateRow_' + c.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${c.full_name.charAt(0)}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">${c.full_name}</span>
                                    ${c.resume_url ? `<small><a href="${c.resume_url}" target="_blank" class="text-primary text-decoration-none small"><i class="bi bi-file-earmark-text me-0.5"></i> Resume</a></small>` : ''}
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">${c.email}</td>
                        <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">${c.phone}</td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${c.experience}
                            </span>
                        </td>
                        <td class="px-2 py-2.5 text-dark fw-semibold align-middle">${c.qualification}</td>
                        <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge bg-light text-dark border border-secondary-subtle rounded-pill px-2.5 py-1">
                                ${c.source}
                            </span>
                        </td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge ${statusClass} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${c.status}
                            </span>
                        </td>
                        <td class="pe-3 py-2.5 text-end align-middle">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                <a href="${c.edit_url}" class="btn btn-action-edit" title="Edit Candidate" aria-label="Edit Candidate">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${c.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this candidate record?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Candidate" aria-label="Delete Candidate">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    candidatesTableBody.prepend(newRow);

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
                    modalCandidateErrors.innerHTML = errHtml;
                    modalCandidateErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                addCandidateBtn.disabled = false;
                addCandidateBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Add Candidate';
                modalCandidateErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalCandidateErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
