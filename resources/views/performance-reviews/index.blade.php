@extends('layouts.app')

@section('title', 'Performance Reviews')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Performance Reviews']
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
                <h3 class="fw-bold text-dark mb-1">Performance Reviews</h3>
                <p class="text-secondary small mb-0">Record, evaluate, and analyze employee performance monthly, quarterly, or annually.</p>
            </div>
            @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-success fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createEvaluationModal">
                    <i class="bi bi-plus-lg me-1"></i> Create Review Evaluation
                </button>
            @endif
        </div>
    </div>

    {{-- Filter Card --}}
    @if(auth()->user()->role === 'admin')
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-secondary mb-1">Filter Employee</label>
                    <select name="employee_id" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Employees</option>
                        @foreach($employees ?? [] as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-'.$emp->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">Review Type</label>
                    <select name="review_type" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Review Types</option>
                        <option value="Monthly" {{ request('review_type') === 'Monthly' ? 'selected' : '' }}>Monthly Review</option>
                        <option value="Quarterly" {{ request('review_type') === 'Quarterly' ? 'selected' : '' }}>Quarterly Review</option>
                        <option value="Annual" {{ request('review_type') === 'Annual' ? 'selected' : '' }}>Annual Review</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold text-white shadow-sm py-2">Filter Reviews</button>
                    @if(request('employee_id') || request('review_type'))
                        <a href="{{ route('performance-reviews.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold py-2">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    @endif

    {{-- Performance Reviews Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="reviewsTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Employee</th>
                        <th class="py-3">Reviewer</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Period</th>
                        <th class="py-3">Rating</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 text-end py-3" width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr class="hover-row" id="reviewRow_{{ $review->id }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="($review->employee->first_name ?? '') . ' ' . ($review->employee->last_name ?? '')" size="sm" />
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $review->employee->first_name ?? '' }} {{ $review->employee->last_name ?? '' }}</span>
                                        <small class="text-secondary opacity-75 d-block">ID: #{{ $review->employee->employee_id ?? $review->employee_id }}</small>
                                    </div>
                                </div>
                            </td>

                            <td class="fw-semibold text-secondary">
                                @if($review->reviewer)
                                    {{ $review->reviewer->first_name }} {{ $review->reviewer->last_name }}
                                @else
                                    <span class="text-secondary opacity-50">N/A</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">
                                    {{ $review->review_type }}
                                </span>
                            </td>

                            <td class="fw-semibold text-dark small">{{ $review->period }}</td>

                            {{-- Professional Rating Component --}}
                            <td>
                                <div class="d-inline-flex align-items-center gap-1.5 bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.8rem;">
                                    <i class="bi bi-star-fill text-warning me-0.5"></i> {{ number_format($review->rating, 2) }} / 5.00
                                </div>
                            </td>

                            <td class="text-secondary small fw-medium">
                                {{ \Carbon\Carbon::parse($review->review_date)->format('M d, Y') }}
                            </td>

                            <td>
                                @if($review->status === 'Completed')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Completed</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Pending</span>
                                @endif
                            </td>

                            <td class="pe-4 text-end">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                                    <a href="{{ route('performance-reviews.show', $review->id) }}" class="btn btn-action-view" title="View Review" aria-label="View Review">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('performance-reviews.edit', $review->id) }}" class="btn btn-action-edit" title="Edit Review" aria-label="Edit Review">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('performance-reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this evaluation?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete" title="Delete Review" aria-label="Delete Review">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noReviewsRow">
                            <td colspan="8" class="p-0">
                                <x-empty-state title="No Performance Reviews Recorded" icon="bi-award" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $reviews->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Create Review Evaluation Centered Modal Overlay --}}
<div class="modal fade" id="createEvaluationModal" tabindex="-1" aria-labelledby="createEvaluationModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="createEvaluationModalLabel">
                    <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-award-fill fs-6"></i>
                    </div>
                    Create Review Evaluation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="createEvaluationForm" action="{{ route('performance-reviews.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalEvaluationErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Employee Dropdown --}}
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-bold text-dark small">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Employee...</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-'.$emp->id }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Reviewer Dropdown --}}
                        <div class="col-md-6">
                            <label for="reviewer_id" class="form-label fw-bold text-dark small">Reviewer <span class="text-danger">*</span></label>
                            <select name="reviewer_id" id="reviewer_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Reviewer...</option>
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}" {{ (auth()->user()->employee_id == $emp->id) ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-'.$emp->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Review Type --}}
                        <div class="col-md-6">
                            <label for="review_type" class="form-label fw-bold text-dark small">Review Type <span class="text-danger">*</span></label>
                            <select name="review_type" id="review_type" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly" selected>Quarterly</option>
                                <option value="Annual">Annual</option>
                            </select>
                        </div>

                        {{-- Review Period --}}
                        <div class="col-md-6">
                            <label for="period" class="form-label fw-bold text-dark small">Review Period <span class="text-danger">*</span></label>
                            <input type="text" name="period" id="period" class="form-control rounded-3 border-light-subtle" placeholder="e.g. Q1 2026, Jan 2026, 2026 Annual" value="Q1 {{ date('Y') }}" required>
                        </div>

                        {{-- Rating (Numeric / Star) --}}
                        <div class="col-md-4">
                            <label for="rating" class="form-label fw-bold text-dark small">Rating (1.0 - 5.0) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="rating" id="rating" class="form-control rounded-3 border-light-subtle" value="4.5" min="1" max="5" required>
                        </div>

                        {{-- Review Date --}}
                        <div class="col-md-4">
                            <label for="review_date" class="form-label fw-bold text-dark small">Review Date <span class="text-danger">*</span></label>
                            <input type="date" name="review_date" id="review_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-bold text-dark small">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                                <option value="Completed" selected>Completed</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>

                        {{-- Strengths & Feedback --}}
                        <div class="col-12">
                            <label for="strengths" class="form-label fw-bold text-dark small">Strengths & Feedback Summary</label>
                            <textarea name="strengths" id="strengths" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="Provide evaluation notes, strengths, and areas for improvement..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveEvaluationBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Evaluation
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const createEvaluationForm = document.getElementById('createEvaluationForm');
    const saveEvaluationBtn = document.getElementById('saveEvaluationBtn');
    const modalEvaluationErrors = document.getElementById('modalEvaluationErrors');
    const reviewsTableBody = document.querySelector('#reviewsTable tbody');
    const alertContainer = document.getElementById('alertContainer');
    const isAdmin = {{ auth()->user()->role === 'admin' ? 'true' : 'false' }};

    if (createEvaluationForm) {
        createEvaluationForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveEvaluationBtn.disabled = true;
            saveEvaluationBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            modalEvaluationErrors.classList.add('d-none');
            modalEvaluationErrors.innerHTML = '';

            const formData = new FormData(createEvaluationForm);

            fetch("{{ route('performance-reviews.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                saveEvaluationBtn.disabled = false;
                saveEvaluationBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Evaluation';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('createEvaluationModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    createEvaluationForm.reset();
                    document.getElementById('rating').value = "4.5";
                    document.getElementById('review_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('status').value = "Completed";

                    // Remove empty state row if present
                    const noReviewsRow = document.getElementById('noReviewsRow');
                    if (noReviewsRow) {
                        noReviewsRow.remove();
                    }

                    // Prepend new row
                    const r = data.review;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusBadgeClass = r.status === 'Completed' 
                        ? 'bg-success-subtle text-success border border-success-subtle' 
                        : 'bg-warning-subtle text-warning border border-warning-subtle';

                    let actionsHtml = `
                        <div class="d-inline-flex align-items-center justify-content-end" style="gap: 12px;">
                            <a href="${r.show_url}" class="btn btn-action-view" title="View Review" aria-label="View Review">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            ${isAdmin ? `
                                <a href="${r.edit_url}" class="btn btn-action-edit" title="Edit Review" aria-label="Edit Review">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${r.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this evaluation?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Review" aria-label="Delete Review">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            ` : ''}
                        </div>
                    `;

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'reviewRow_' + r.id;
                    newRow.innerHTML = `
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${r.employee_name.charAt(0)}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block">${r.employee_name}</span>
                                    <small class="text-secondary opacity-75 d-block">ID: #${r.employee_code}</small>
                                </div>
                            </div>
                        </td>
                        <td class="fw-semibold text-secondary">${r.reviewer_name}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">
                                ${r.review_type}
                            </span>
                        </td>
                        <td class="fw-semibold text-dark small">${r.period}</td>
                        <td>
                            <div class="d-inline-flex align-items-center gap-1.5 bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.8rem;">
                                <i class="bi bi-star-fill text-warning me-0.5"></i> ${r.rating} / 5.00
                            </div>
                        </td>
                        <td class="text-secondary small fw-medium">${r.review_date}</td>
                        <td>
                            <span class="badge ${statusBadgeClass} rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                ${r.status}
                            </span>
                        </td>
                        <td class="pe-4 text-end">${actionsHtml}</td>
                    `;
                    reviewsTableBody.prepend(newRow);

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
                    modalEvaluationErrors.innerHTML = errHtml;
                    modalEvaluationErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                saveEvaluationBtn.disabled = false;
                saveEvaluationBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Evaluation';
                modalEvaluationErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalEvaluationErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
