@extends('layouts.app')

@section('title', 'Departments')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Departments']
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
                <h3 class="fw-bold text-dark mb-1">Departments</h3>
                <p class="text-secondary small mb-0">Manage all company departments, department codes, and staff allocations.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                + Add Department
            </button>
        </div>
    </div>

    {{-- Search Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" action="{{ route('departments.index') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="Search by name or code..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-50 rounded-3 fw-bold py-2 text-white shadow-sm">Search</button>
                <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary w-50 rounded-3 fw-semibold py-2">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="departmentsTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Name</th>
                        <th class="py-3">Code</th>
                        <th class="py-3">Employees</th>
                        <th class="py-3">Description</th>
                        <th class="pe-4 text-end py-3" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                        <tr class="hover-row">
                            <td class="ps-4 fw-bold text-secondary">#{{ $department->id }}</td>
                            <td class="fw-bold text-dark">{{ $department->name }}</td>
                            <td><span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold">{{ $department->code }}</span></td>
                            <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold">{{ $department->employees_count }}</span></td>
                            <td class="text-secondary small">{{ $department->description ?? 'No Description' }}</td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-1.5">
                                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-action-edit" title="Edit Department" aria-label="Edit Department">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Department" aria-label="Delete Department" onclick="return confirm('Delete this department?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-empty-state title="No Departments Available" icon="bi-building" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Add Department Modal (Popup Overlay with Backdrop Blur) --}}
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px);">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-white border-bottom p-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0" id="addDepartmentModalLabel">Add Department</h5>
                    <small class="text-secondary">Create a new organizational department record.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="addDepartmentForm" action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div id="addDepartmentAlert" class="alert alert-danger d-none rounded-3 py-2 px-3 small mb-3"></div>

                    {{-- Department Name --}}
                    <div class="mb-3.5">
                        <label class="form-label small fw-semibold text-secondary mb-1">Department Name <span class="text-danger">*</span></label>
                        <input type="text" id="modal_name" name="name" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="e.g. Human Resources" required>
                    </div>

                    {{-- Department Code --}}
                    <div class="mb-3.5">
                        <label class="form-label small fw-semibold text-secondary mb-1">Department Code <span class="text-danger">*</span></label>
                        <input type="text" id="modal_code" name="code" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="e.g. HR-001" required>
                    </div>

                    {{-- Description --}}
                    <div class="mb-0">
                        <label class="form-label small fw-semibold text-secondary mb-1">Description</label>
                        <textarea id="modal_description" name="description" class="form-control rounded-3 border-light-subtle shadow-2xs" rows="3" placeholder="Provide a short summary of responsibilities..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top p-3 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary fw-semibold px-4 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveDeptBtn" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm">Save Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addDepartmentForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveDeptBtn');
        const alertBox = document.getElementById('addDepartmentAlert');
        
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
            btn.innerHTML = 'Save Department';

            if (data.success) {
                // Close modal
                const modalEl = document.getElementById('addDepartmentModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modalInstance.hide();

                // Reset Form
                form.reset();

                // Dynamically append new department to table
                const tbody = document.querySelector('#departmentsTable tbody');
                const emptyCell = tbody.querySelector('td[colspan]');
                if (emptyCell) {
                    emptyCell.closest('tr').remove();
                }

                const dept = data.department;
                const newRow = document.createElement('tr');
                newRow.className = 'hover-row bg-success-subtle transition-all';
                newRow.innerHTML = `
                    <td class="ps-4 fw-bold text-secondary">#${dept.id}</td>
                    <td class="fw-bold text-dark">${dept.name}</td>
                    <td><span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold">${dept.code}</span></td>
                    <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold">0</span></td>
                    <td class="text-secondary small">${dept.description ? dept.description : 'No Description'}</td>
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex gap-1.5">
                            <a href="/departments/${dept.id}/edit" class="btn btn-action-edit" title="Edit Department" aria-label="Edit Department">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="/departments/${dept.id}" method="POST" class="d-inline">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-action-delete" title="Delete Department" aria-label="Delete Department" onclick="return confirm('Delete this department?')">
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
            btn.innerHTML = 'Save Department';
            alertBox.innerHTML = err.message || 'An error occurred while saving the department.';
            alertBox.classList.remove('d-none');
        });
    });
});
</script>

@endsection
