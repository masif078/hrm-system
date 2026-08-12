@extends('layouts.app')

@section('title', 'Designations')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Designations']
    ]" />

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 shadow-sm">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Designations</h3>
                <p class="text-secondary small mb-0">Manage all designation titles and job roles mapped across departments.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addDesignationModal">
                + Add Designation
            </button>
        </div>
    </div>

    {{-- Search Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" action="{{ route('designations.index') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="Search by designation title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-50 rounded-3 fw-bold py-2 text-white shadow-sm">Search</button>
                <a href="{{ route('designations.index') }}" class="btn btn-outline-secondary w-50 rounded-3 fw-semibold py-2">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="designationsTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Title</th>
                        <th class="py-3">Department</th>
                        <th class="py-3">Description</th>
                        <th class="pe-4 text-end py-3" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($designations as $designation)
                        <tr class="hover-row">
                            <td class="ps-4 fw-bold text-secondary">#{{ $designation->id }}</td>
                            <td class="fw-bold text-dark">{{ $designation->title }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold">
                                    {{ $designation->department->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-secondary small">{{ $designation->description ?? 'No Description' }}</td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-1.5">
                                    <a href="{{ route('designations.edit', $designation) }}" class="btn btn-action-edit" title="Edit Designation" aria-label="Edit Designation">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('designations.destroy', $designation) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Designation" aria-label="Delete Designation" onclick="return confirm('Delete this designation?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state title="No Designations Available" icon="bi-award" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Add Designation Modal (Popup Overlay with Backdrop Blur) --}}
<div class="modal fade" id="addDesignationModal" tabindex="-1" aria-labelledby="addDesignationModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px);">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-white border-bottom p-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0" id="addDesignationModalLabel">Add Designation</h5>
                    <small class="text-secondary">Create a new job title mapped to a department.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="addDesignationForm" action="{{ route('designations.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div id="addDesignationAlert" class="alert alert-danger d-none rounded-3 py-2 px-3 small mb-3"></div>

                    {{-- Designation Name / Title --}}
                    <div class="mb-3.5">
                        <label class="form-label small fw-semibold text-secondary mb-1">Designation Name <span class="text-danger">*</span></label>
                        <input type="text" id="modal_desig_title" name="title" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="e.g. Senior Software Engineer" required>
                    </div>

                    {{-- Department Selection --}}
                    <div class="mb-3.5">
                        <label class="form-label small fw-semibold text-secondary mb-1">Department <span class="text-danger">*</span></label>
                        <select id="modal_department_id" name="department_id" class="form-select rounded-3 border-light-subtle shadow-2xs" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-secondary mb-1">Description</label>
                        <textarea id="modal_desig_description" name="description" class="form-control rounded-3 border-light-subtle shadow-2xs" rows="3" placeholder="Provide a short summary of job role..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top p-3 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary fw-semibold px-4 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveDesigBtn" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm">Save Designation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addDesignationForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveDesigBtn');
        const alertBox = document.getElementById('addDesignationAlert');
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Saving...';
        alertBox.classList.add('d-none');

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                let msg = data.message || 'Validation error occurred.';
                if (data.errors) {
                    msg = Object.values(data.errors).flat().join('<br>');
                }
                throw new Error(msg);
            }
            return data;
        })
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = 'Save Designation';

            if (data.success) {
                // Close modal
                const modalEl = document.getElementById('addDesignationModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modalInstance.hide();

                // Reset Form
                form.reset();

                // Dynamically append new designation to table
                const tbody = document.querySelector('#designationsTable tbody');
                const emptyCell = tbody.querySelector('td[colspan]');
                if (emptyCell) {
                    emptyCell.closest('tr').remove();
                }

                const desig = data.designation;
                const deptName = desig.department ? desig.department.name : '-';

                const newRow = document.createElement('tr');
                newRow.className = 'hover-row bg-success-subtle transition-all';
                newRow.innerHTML = `
                    <td class="ps-4 fw-bold text-secondary">#${desig.id}</td>
                    <td class="fw-bold text-dark">${desig.title}</td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold">
                            ${deptName}
                        </span>
                    </td>
                    <td class="text-secondary small">${desig.description ? desig.description : 'No Description'}</td>
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex gap-1.5">
                            <a href="/designations/${desig.id}/edit" class="btn btn-action-edit" title="Edit Designation" aria-label="Edit Designation">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="/designations/${desig.id}" method="POST" class="d-inline">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-action-delete" title="Delete Designation" aria-label="Delete Designation" onclick="return confirm('Delete this designation?')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                `;
                tbody.prepend(newRow);

                // Smoothly remove flash effect after 2 seconds
                setTimeout(() => {
                    newRow.classList.remove('bg-success-subtle');
                }, 2000);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = 'Save Designation';
            alertBox.innerHTML = err.message || 'An error occurred while saving the designation.';
            alertBox.classList.remove('d-none');
        });
    });
});
</script>

@endsection
