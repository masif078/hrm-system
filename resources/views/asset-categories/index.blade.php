@extends('layouts.app')

@section('title', 'Asset Categories')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Asset Categories']
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
                <h3 class="fw-bold text-dark mb-1">Asset Categories</h3>
                <p class="text-secondary small mb-0">Classify physical hardware, laptops, servers, and software licenses.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bi bi-plus-lg me-1"></i> Create Category
            </button>
        </div>
    </div>

    {{-- Asset Categories Table Card (No Horizontal Scrollbar, Compact SaaS Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="categoriesTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">Category Name</th>
                        <th class="px-2 py-2.5">Description</th>
                        <th class="px-2 py-2.5">Created Date</th>
                        <th class="pe-3 text-end py-2.5" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr class="hover-row" id="categoryRow_{{ $cat->id }}">
                            {{-- Category Name --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">{{ $cat->name }}</span>
                            </td>

                            {{-- Description --}}
                            <td class="px-2 py-2.5 text-secondary small fw-medium align-middle">
                                {{ $cat->description ?: 'No description' }}
                            </td>

                            {{-- Created Date --}}
                            <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                {{ $cat->created_at ? $cat->created_at->format('M d, Y') : 'N/A' }}
                            </td>

                            {{-- Minimalist Action Icons with 12px gap --}}
                            <td class="pe-3 py-2.5 text-end align-middle">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                    <a href="{{ route('asset-categories.edit', $cat->id) }}" class="btn btn-action-edit" title="Edit Category" aria-label="Edit Category">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('asset-categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Category" aria-label="Delete Category">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noCategoriesRow">
                            <td colspan="4" class="p-0">
                                <x-empty-state title="No Asset Categories Recorded" icon="bi-folder2-open" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Create Category Centered Modal Overlay --}}
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="createCategoryModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-folder-plus-fill fs-6"></i>
                    </div>
                    Create Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="createCategoryForm" action="{{ route('asset-categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalCategoryErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Category Name --}}
                        <div class="col-12">
                            <label for="name" class="form-label fw-bold text-dark small">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Laptops & Workstations, Mobile Devices, Hardware Credentials" required>
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label for="description" class="form-label fw-bold text-dark small">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="Provide a detailed description of assets classified under this category..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveCategoryBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Category
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const createCategoryForm = document.getElementById('createCategoryForm');
    const saveCategoryBtn = document.getElementById('saveCategoryBtn');
    const modalCategoryErrors = document.getElementById('modalCategoryErrors');
    const categoriesTableBody = document.querySelector('#categoriesTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (createCategoryForm) {
        createCategoryForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveCategoryBtn.disabled = true;
            saveCategoryBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalCategoryErrors.classList.add('d-none');
            modalCategoryErrors.innerHTML = '';

            const formData = new FormData(createCategoryForm);

            fetch("{{ route('asset-categories.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveCategoryBtn.disabled = false;
                saveCategoryBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Category';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('createCategoryModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    createCategoryForm.reset();

                    // Remove empty state row if present
                    const noCategoriesRow = document.getElementById('noCategoriesRow');
                    if (noCategoriesRow) {
                        noCategoriesRow.remove();
                    }

                    // Prepend new row
                    const c = data.category;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'categoryRow_' + c.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">${c.name}</span>
                        </td>
                        <td class="px-2 py-2.5 text-secondary small fw-medium align-middle">${c.description}</td>
                        <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">${c.created_at}</td>
                        <td class="pe-3 py-2.5 text-end align-middle">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                <a href="${c.edit_url}" class="btn btn-action-edit" title="Edit Category" aria-label="Edit Category">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${c.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Category" aria-label="Delete Category">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    categoriesTableBody.prepend(newRow);

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
                    modalCategoryErrors.innerHTML = errHtml;
                    modalCategoryErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveCategoryBtn.disabled = false;
                saveCategoryBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Category';
                modalCategoryErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalCategoryErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
