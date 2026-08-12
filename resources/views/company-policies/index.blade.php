@extends('layouts.app')

@section('title', 'Company Policies')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Company Policies']
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
                <h3 class="fw-bold text-dark mb-1">Company Standard Policies</h3>
                <p class="text-secondary small mb-0">Manage office procedures, code of conduct, remote work, and compliance guidelines.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createPolicyModal">
                <i class="bi bi-plus-lg me-1"></i> Create Policy
            </button>
        </div>
    </div>

    {{-- Policies Grid Cards --}}
    <div class="row g-4" id="policiesGrid">
        @forelse($policies as $policy)
            <div class="col-md-6 col-xxl-4" id="policyCardCol_{{ $policy->id }}">
                <div class="card border border-light-subtle shadow-sm rounded-4 h-100 bg-white hover-elevate transition-all">
                    <div class="card-body p-4 d-flex flex-column">
                        {{-- Category Pill Tag --}}
                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold fs-7" id="policyCardType_{{ $policy->id }}">
                                <i class="bi bi-shield-check me-1"></i> {{ $policy->type }}
                            </span>
                        </div>

                        {{-- Policy Title --}}
                        <h5 class="fw-bold text-dark mb-2" id="policyCardTitle_{{ $policy->id }}">{{ $policy->title }}</h5>

                        {{-- Policy Content --}}
                        <p class="text-secondary small flex-grow-1 mb-4" id="policyCardContent_{{ $policy->id }}" style="line-height: 1.6; white-space: pre-line;">{{ Str::limit($policy->content, 200) }}</p>

                        {{-- Card Footer Action Buttons --}}
                        <div class="d-flex align-items-center justify-content-between gap-2 mt-auto pt-3 border-top border-light-subtle">
                            <button type="button" 
                                    class="btn btn-outline-primary btn-sm rounded-3 px-3 py-1.5 fw-bold edit-policy-btn d-inline-flex align-items-center"
                                    data-id="{{ $policy->id }}"
                                    data-title="{{ $policy->title }}"
                                    data-type="{{ $policy->type }}"
                                    data-content="{{ $policy->content }}"
                                    data-update-url="{{ route('company-policies.update', $policy->id) }}">
                                <i class="bi bi-pencil-fill me-1.5"></i> Edit Policy
                            </button>

                            <form action="{{ route('company-policies.destroy', $policy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this policy?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-3 py-1.5 fw-bold d-inline-flex align-items-center">
                                    <i class="bi bi-trash-fill me-1.5"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12" id="noPoliciesCol">
                <x-empty-state title="No Company Policies Recorded" icon="bi-journal-text" />
            </div>
        @endforelse
    </div>

</div>

{{-- Create Policy Centered Modal Overlay --}}
<div class="modal fade" id="createPolicyModal" tabindex="-1" aria-labelledby="createPolicyModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="createPolicyModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-file-earmark-text-fill fs-6"></i>
                    </div>
                    Create New Company Policy
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="createPolicyForm" action="{{ route('company-policies.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="createPolicyModalErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Policy Title --}}
                        <div class="col-md-7">
                            <label for="create_title" class="form-label fw-bold text-dark small">Policy Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="create_title" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Annual Leave & Time Off Policy" required>
                        </div>

                        {{-- Category / Type --}}
                        <div class="col-md-5">
                            <label for="create_type" class="form-label fw-bold text-dark small">Category <span class="text-danger">*</span></label>
                            <select name="type" id="create_type" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Category...</option>
                                <option value="Leave Policy">Leave Policy</option>
                                <option value="Attendance Policy">Attendance Policy</option>
                                <option value="Code of Conduct">Code of Conduct</option>
                                <option value="IT & Security">IT & Security</option>
                                <option value="Remote Work">Remote Work</option>
                                <option value="Benefits & Perks">Benefits & Perks</option>
                            </select>
                        </div>

                        {{-- Policy Content --}}
                        <div class="col-12">
                            <label for="create_content" class="form-label fw-bold text-dark small">Policy Content <span class="text-danger">*</span></label>
                            <textarea name="content" id="create_content" rows="6" class="form-control rounded-3 border-light-subtle" placeholder="Enter full details, rules, guidelines, and bullet points of the policy..." required></textarea>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="savePolicyBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Policy
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Edit Policy Centered Modal Overlay --}}
<div class="modal fade" id="editPolicyModal" tabindex="-1" aria-labelledby="editPolicyModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="editPolicyModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-pencil-square fs-6"></i>
                    </div>
                    Edit Company Policy
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="editPolicyForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-white">
                    <div id="editPolicyModalErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Policy Title --}}
                        <div class="col-md-7">
                            <label for="edit_title" class="form-label fw-bold text-dark small">Policy Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_title" class="form-control rounded-3 border-light-subtle" required>
                        </div>

                        {{-- Category / Type --}}
                        <div class="col-md-5">
                            <label for="edit_type" class="form-label fw-bold text-dark small">Category <span class="text-danger">*</span></label>
                            <select name="type" id="edit_type" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Leave Policy">Leave Policy</option>
                                <option value="Attendance Policy">Attendance Policy</option>
                                <option value="Code of Conduct">Code of Conduct</option>
                                <option value="IT & Security">IT & Security</option>
                                <option value="Remote Work">Remote Work</option>
                                <option value="Benefits & Perks">Benefits & Perks</option>
                            </select>
                        </div>

                        {{-- Policy Content --}}
                        <div class="col-12">
                            <label for="edit_content" class="form-label fw-bold text-dark small">Policy Content <span class="text-danger">*</span></label>
                            <textarea name="content" id="edit_content" rows="6" class="form-control rounded-3 border-light-subtle" required></textarea>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="updatePolicyBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const createPolicyForm = document.getElementById('createPolicyForm');
    const savePolicyBtn = document.getElementById('savePolicyBtn');
    const createPolicyModalErrors = document.getElementById('createPolicyModalErrors');

    const editPolicyForm = document.getElementById('editPolicyForm');
    const updatePolicyBtn = document.getElementById('updatePolicyBtn');
    const editPolicyModalErrors = document.getElementById('editPolicyModalErrors');

    const policiesGrid = document.getElementById('policiesGrid');
    const alertContainer = document.getElementById('alertContainer');

    // Populate Edit Policy Modal when clicking Edit on any card
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.edit-policy-btn');
        if (btn) {
            const updateUrl = btn.getAttribute('data-update-url');
            const title = btn.getAttribute('data-title');
            const type = btn.getAttribute('data-type');
            const content = btn.getAttribute('data-content');

            editPolicyForm.setAttribute('action', updateUrl);
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_content').value = content;

            const modalEl = document.getElementById('editPolicyModal');
            const modalInstance = new bootstrap.Modal(modalEl);
            modalInstance.show();
        }
    });

    // Handle Create Policy Form Submit
    if (createPolicyForm) {
        createPolicyForm.addEventListener('submit', function (e) {
            e.preventDefault();

            savePolicyBtn.disabled = true;
            savePolicyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            createPolicyModalErrors.classList.add('d-none');
            createPolicyModalErrors.innerHTML = '';

            const formData = new FormData(createPolicyForm);

            fetch("{{ route('company-policies.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                savePolicyBtn.disabled = false;
                savePolicyBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Policy';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('createPolicyModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    createPolicyForm.reset();

                    // Remove empty state if present
                    const noPoliciesCol = document.getElementById('noPoliciesCol');
                    if (noPoliciesCol) {
                        noPoliciesCol.remove();
                    }

                    // Prepend new card to Grid
                    const p = data.policy;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const newCol = document.createElement('div');
                    newCol.className = 'col-md-6 col-xxl-4';
                    newCol.id = 'policyCardCol_' + p.id;
                    newCol.innerHTML = `
                        <div class="card border border-light-subtle shadow-sm rounded-4 h-100 bg-white hover-elevate transition-all">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="mb-3">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold fs-7" id="policyCardType_${p.id}">
                                        <i class="bi bi-shield-check me-1"></i> ${p.type}
                                    </span>
                                </div>

                                <h5 class="fw-bold text-dark mb-2" id="policyCardTitle_${p.id}">${p.title}</h5>

                                <p class="text-secondary small flex-grow-1 mb-4" id="policyCardContent_${p.id}" style="line-height: 1.6; white-space: pre-line;">${p.excerpt}</p>

                                <div class="d-flex align-items-center justify-content-between gap-2 mt-auto pt-3 border-top border-light-subtle">
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-sm rounded-3 px-3 py-1.5 fw-bold edit-policy-btn d-inline-flex align-items-center"
                                            data-id="${p.id}"
                                            data-title="${p.title}"
                                            data-type="${p.type}"
                                            data-content="${p.content}"
                                            data-update-url="${p.update_url}">
                                        <i class="bi bi-pencil-fill me-1.5"></i> Edit Policy
                                    </button>

                                    <form action="${p.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this policy?')">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-3 py-1.5 fw-bold d-inline-flex align-items-center">
                                            <i class="bi bi-trash-fill me-1.5"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `;
                    policiesGrid.prepend(newCol);

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
                    createPolicyModalErrors.innerHTML = errHtml;
                    createPolicyModalErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                savePolicyBtn.disabled = false;
                savePolicyBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Policy';
                createPolicyModalErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                createPolicyModalErrors.classList.remove('d-none');
            });
        });
    }

    // Handle Edit Policy Form Submit
    if (editPolicyForm) {
        editPolicyForm.addEventListener('submit', function (e) {
            e.preventDefault();

            updatePolicyBtn.disabled = true;
            updatePolicyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            editPolicyModalErrors.classList.add('d-none');
            editPolicyModalErrors.innerHTML = '';

            const formData = new FormData(editPolicyForm);
            const actionUrl = editPolicyForm.getAttribute('action');

            fetch(actionUrl, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                updatePolicyBtn.disabled = false;
                updatePolicyBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Changes';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('editPolicyModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Update Card Content
                    const p = data.policy;
                    const cardTitle = document.getElementById('policyCardTitle_' + p.id);
                    const cardType = document.getElementById('policyCardType_' + p.id);
                    const cardContent = document.getElementById('policyCardContent_' + p.id);
                    const editBtn = document.querySelector(`#policyCardCol_${p.id} .edit-policy-btn`);

                    if (cardTitle) cardTitle.textContent = p.title;
                    if (cardType) cardType.innerHTML = `<i class="bi bi-shield-check me-1"></i> ${p.type}`;
                    if (cardContent) cardContent.textContent = p.excerpt;
                    if (editBtn) {
                        editBtn.setAttribute('data-title', p.title);
                        editBtn.setAttribute('data-type', p.type);
                        editBtn.setAttribute('data-content', p.content);
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
                    editPolicyModalErrors.innerHTML = errHtml;
                    editPolicyModalErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                updatePolicyBtn.disabled = false;
                updatePolicyBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Changes';
                editPolicyModalErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                editPolicyModalErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
