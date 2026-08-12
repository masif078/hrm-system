@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Projects']
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
                <h3 class="fw-bold text-dark mb-1">Projects Management</h3>
                <p class="text-secondary small mb-0">Manage client projects, project managers, budgets, and execution status.</p>
            </div>
            @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                    <i class="bi bi-plus-lg me-1"></i> Add Project
                </button>
            @endif
        </div>
    </div>

    {{-- Search Bar Card (Compact & SaaS Styled) --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-3">
        <form method="GET" action="{{ route('projects.index') }}">
            <div class="row g-2 align-items-center">
                <div class="col-md-9 col-lg-10">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-light-subtle text-muted rounded-start-3">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control rounded-end-3 border-light-subtle shadow-2xs" value="{{ request('search') }}" placeholder="Search by project name or project code...">
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold py-2 text-white shadow-sm d-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-funnel-fill"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Projects Table Card (No Horizontal Scrollbar, Compact 100% Width Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="projectsTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">Code</th>
                        <th class="px-2 py-2.5">Project</th>
                        <th class="px-2 py-2.5">Client</th>
                        <th class="px-2 py-2.5">Manager</th>
                        <th class="px-2 py-2.5">Budget (PKR)</th>
                        <th class="px-2 py-2.5">Status</th>
                        <th class="pe-3 text-end py-2.5" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr class="hover-row" id="projectRow_{{ $project->id }}">
                            {{-- Code --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-3 px-2.5 py-1 fw-bold" style="font-size: 0.75rem;">
                                    {{ $project->project_code }}
                                </span>
                            </td>

                            {{-- Project Name --}}
                            <td class="px-2 py-2.5 align-middle">
                                <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">{{ $project->project_name }}</span>
                            </td>

                            {{-- Client --}}
                            <td class="px-2 py-2.5 text-secondary small fw-medium align-middle">
                                {{ $project->client?->company_name ?? 'N/A' }}
                            </td>

                            {{-- Manager --}}
                            <td class="px-2 py-2.5 text-dark fw-semibold align-middle text-nowrap" style="white-space: nowrap;">
                                {{ $project->manager?->first_name }} {{ $project->manager?->last_name }}
                            </td>

                            {{-- Budget --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                <span class="fw-bold text-success">PKR {{ number_format($project->budget, 2) }}</span>
                            </td>

                            {{-- Status Badges (Rounded Pill) --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @php
                                    $st = $project->status;
                                    $statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                                    if ($st === 'Completed') {
                                        $statusClass = 'bg-success-subtle text-success border border-success-subtle';
                                    } elseif (in_array($st, ['On Hold', 'Cancelled'])) {
                                        $statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                    } elseif ($st === 'Pending') {
                                        $statusClass = 'bg-info-subtle text-info border border-info-subtle';
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                    {{ $project->status }}
                                </span>
                            </td>

                            {{-- Action Icons (10px Gap, Centered) --}}
                            <td class="pe-3 py-2.5 text-end align-middle">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-action-view" title="View Project" aria-label="View Project">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-action-edit" title="Edit Project" aria-label="Edit Project">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete" title="Delete Project" aria-label="Delete Project">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noProjectsRow">
                            <td colspan="7" class="p-0">
                                <x-empty-state title="No Projects Found" icon="bi-kanban" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $projects->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Add Project Centered Modal Overlay --}}
@if(auth()->user()->role === 'admin')
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="addProjectModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-kanban-fill fs-6"></i>
                    </div>
                    Add Project
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="addProjectForm" action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalProjectErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Project Code --}}
                        <div class="col-md-6">
                            <label for="project_code" class="form-label fw-bold text-dark small">Project Code <span class="text-danger">*</span></label>
                            <input type="text" name="project_code" id="project_code" class="form-control rounded-3 border-light-subtle" placeholder="e.g. PRJ-101" required>
                        </div>

                        {{-- Project Name --}}
                        <div class="col-md-6">
                            <label for="project_name" class="form-label fw-bold text-dark small">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="project_name" id="project_name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Mobile App Redesign" required>
                        </div>

                        {{-- Client --}}
                        <div class="col-md-6">
                            <label for="client_id" class="form-label fw-bold text-dark small">Client <span class="text-danger">*</span></label>
                            <select name="client_id" id="client_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Client...</option>
                                @foreach($clients ?? [] as $client)
                                    <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Project Manager --}}
                        <div class="col-md-6">
                            <label for="project_manager_id" class="form-label fw-bold text-dark small">Project Manager <span class="text-danger">*</span></label>
                            <select name="project_manager_id" id="project_manager_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Manager...</option>
                                @foreach($employees ?? [] as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Start Date --}}
                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-bold text-dark small">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- End Date --}}
                        <div class="col-md-6">
                            <label for="end_date" class="form-label fw-bold text-dark small">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control rounded-3 border-light-subtle">
                        </div>

                        {{-- Budget (PKR) --}}
                        <div class="col-md-6">
                            <label for="budget" class="form-label fw-bold text-dark small">Budget (PKR) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="budget" id="budget" class="form-control rounded-3 border-light-subtle" placeholder="e.g. 500000" min="0" required>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="In Progress" selected>In Progress</option>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                                <option value="On Hold">On Hold</option>
                            </select>
                        </div>

                        {{-- Project Description --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-bold text-dark small">Project Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="Provide project scope, goals, and key deliverables..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveProjectBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Project
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addProjectForm = document.getElementById('addProjectForm');
    const saveProjectBtn = document.getElementById('saveProjectBtn');
    const modalProjectErrors = document.getElementById('modalProjectErrors');
    const projectsTableBody = document.querySelector('#projectsTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (addProjectForm) {
        addProjectForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveProjectBtn.disabled = true;
            saveProjectBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalProjectErrors.classList.add('d-none');
            modalProjectErrors.innerHTML = '';

            const formData = new FormData(addProjectForm);

            fetch("{{ route('projects.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveProjectBtn.disabled = false;
                saveProjectBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Project';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('addProjectModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    addProjectForm.reset();
                    document.getElementById('start_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('status').value = "In Progress";

                    // Remove empty state row if present
                    const noProjectsRow = document.getElementById('noProjectsRow');
                    if (noProjectsRow) {
                        noProjectsRow.remove();
                    }

                    // Prepend new row
                    const p = data.project;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                    if (p.status === 'Completed') statusClass = 'bg-success-subtle text-success border border-success-subtle';
                    else if (['On Hold', 'Cancelled'].includes(p.status)) statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                    else if (p.status === 'Pending') statusClass = 'bg-info-subtle text-info border border-info-subtle';

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'projectRow_' + p.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-3 px-2.5 py-1 fw-bold" style="font-size: 0.75rem;">
                                ${p.project_code}
                            </span>
                        </td>
                        <td class="px-2 py-2.5 align-middle">
                            <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">${p.project_name}</span>
                        </td>
                        <td class="px-2 py-2.5 text-secondary small fw-medium align-middle">${p.client_name}</td>
                        <td class="px-2 py-2.5 text-dark fw-semibold align-middle text-nowrap" style="white-space: nowrap;">${p.manager_name}</td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="fw-bold text-success">PKR ${p.budget}</span>
                        </td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge ${statusClass} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${p.status}
                            </span>
                        </td>
                        <td class="pe-3 py-2.5 text-end align-middle">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                <a href="${p.show_url}" class="btn btn-action-view" title="View Project" aria-label="View Project">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="${p.edit_url}" class="btn btn-action-edit" title="Edit Project" aria-label="Edit Project">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${p.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Project" aria-label="Delete Project">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    projectsTableBody.prepend(newRow);

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
                    modalProjectErrors.innerHTML = errHtml;
                    modalProjectErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveProjectBtn.disabled = false;
                saveProjectBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Project';
                modalProjectErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalProjectErrors.classList.remove('d-none');
            });
        });
    }
});
</script>
@endif

@endsection