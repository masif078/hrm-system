@extends('layouts.app')

@section('title', 'Branch List')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Branches']
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
                <h3 class="fw-bold text-dark mb-1">Company Branches</h3>
                <p class="text-secondary small mb-0">Manage multiple office locations, physical addresses, and assigned branch managers.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createBranchModal">
                <i class="bi bi-plus-lg me-1"></i> Add New Branch
            </button>
        </div>
    </div>

    {{-- Branches Table Card (No Horizontal Scrollbar, Compact SaaS Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="branchesTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">Branch Name</th>
                        <th class="px-2 py-2.5">Location</th>
                        <th class="px-2 py-2.5">Manager</th>
                        <th class="px-2 py-2.5">Status</th>
                        <th class="pe-3 text-end py-2.5" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr class="hover-row" id="branchRow_{{ $branch->id }}">
                            {{-- Branch Name --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">{{ $branch->name }}</span>
                            </td>

                            {{-- Location --}}
                            <td class="px-2 py-2.5 text-secondary small fw-medium align-middle">
                                <i class="bi bi-geo-alt-fill me-1 text-danger"></i> {{ $branch->location }}
                            </td>

                            {{-- Manager --}}
                            <td class="px-2 py-2.5 text-secondary fw-semibold align-middle text-nowrap" style="white-space: nowrap;">
                                @if($branch->manager)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                            {{ strtoupper(substr($branch->manager->first_name, 0, 1)) }}
                                        </div>
                                        <span>{{ $branch->manager->first_name }} {{ $branch->manager->last_name }}</span>
                                    </div>
                                @else
                                    <span class="text-secondary opacity-50 small">N/A</span>
                                @endif
                            </td>

                            {{-- Status Badges --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @php
                                    $st = $branch->status;
                                    $statusClass = ($st === 'Active') 
                                        ? 'bg-success-subtle text-success border border-success-subtle' 
                                        : 'bg-danger-subtle text-danger border border-danger-subtle';
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                    {{ $branch->status }}
                                </span>
                            </td>

                            {{-- Action Icons (12px gap, Yellow Edit, Red Delete) --}}
                            <td class="pe-3 py-2.5 text-end align-middle">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                    <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-action-edit" title="Edit Branch" aria-label="Edit Branch">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this office branch?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Branch" aria-label="Delete Branch">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noBranchesRow">
                            <td colspan="5" class="p-0">
                                <x-empty-state title="No Office Branches Logged" icon="bi-building" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Add New Branch Centered Modal Overlay --}}
<div class="modal fade" id="createBranchModal" tabindex="-1" aria-labelledby="createBranchModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="createBranchModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-building-add fs-6"></i>
                    </div>
                    Add New Branch Office
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="createBranchForm" action="{{ route('branches.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalBranchErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Branch Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold text-dark small">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Islamabad Office, Karachi Branch" required>
                        </div>

                        {{-- Physical Location --}}
                        <div class="col-md-6">
                            <label for="location" class="form-label fw-bold text-dark small">Physical Location / Address <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Sector F-6, Blue Area, Islamabad" required>
                        </div>

                        {{-- Manager Dropdown --}}
                        <div class="col-md-6">
                            <label for="manager_id" class="form-label fw-bold text-dark small">Assign Branch Manager</label>
                            <select name="manager_id" id="manager_id" class="form-select rounded-3 border-light-subtle">
                                <option value="">Select Manager...</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Branch Status --}}
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold text-dark small">Branch Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveBranchBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Branch
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const createBranchForm = document.getElementById('createBranchForm');
    const saveBranchBtn = document.getElementById('saveBranchBtn');
    const modalBranchErrors = document.getElementById('modalBranchErrors');
    const branchesTableBody = document.querySelector('#branchesTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (createBranchForm) {
        createBranchForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveBranchBtn.disabled = true;
            saveBranchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalBranchErrors.classList.add('d-none');
            modalBranchErrors.innerHTML = '';

            const formData = new FormData(createBranchForm);

            fetch("{{ route('branches.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveBranchBtn.disabled = false;
                saveBranchBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Branch';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('createBranchModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    createBranchForm.reset();
                    document.getElementById('status').value = "Active";

                    // Remove empty state row if present
                    const noBranchesRow = document.getElementById('noBranchesRow');
                    if (noBranchesRow) {
                        noBranchesRow.remove();
                    }

                    // Prepend new row
                    const b = data.branch;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusClass = (b.status === 'Active') 
                        ? 'bg-success-subtle text-success border border-success-subtle' 
                        : 'bg-danger-subtle text-danger border border-danger-subtle';

                    let managerHtml = '<span class="text-secondary opacity-50 small">N/A</span>';
                    if (b.manager_name && b.manager_name !== 'N/A') {
                        const initialChar = b.manager_name.charAt(0).toUpperCase();
                        managerHtml = `
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                    ${initialChar}
                                </div>
                                <span>${b.manager_name}</span>
                            </div>
                        `;
                    }

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'branchRow_' + b.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">${b.name}</span>
                        </td>
                        <td class="px-2 py-2.5 text-secondary small fw-medium align-middle">
                            <i class="bi bi-geo-alt-fill me-1 text-danger"></i> ${b.location}
                        </td>
                        <td class="px-2 py-2.5 text-secondary fw-semibold align-middle text-nowrap" style="white-space: nowrap;">${managerHtml}</td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge ${statusClass} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${b.status}
                            </span>
                        </td>
                        <td class="pe-3 py-2.5 text-end align-middle">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                <a href="${b.edit_url}" class="btn btn-action-edit" title="Edit Branch" aria-label="Edit Branch">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${b.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this office branch?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Branch" aria-label="Delete Branch">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    branchesTableBody.prepend(newRow);

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
                    modalBranchErrors.innerHTML = errHtml;
                    modalBranchErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveBranchBtn.disabled = false;
                saveBranchBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Branch';
                modalBranchErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalBranchErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
