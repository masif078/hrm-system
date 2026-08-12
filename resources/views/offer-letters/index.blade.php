@extends('layouts.app')

@section('title', 'Offer Letters')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Offer Letters']
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
                <h3 class="fw-bold text-dark mb-1">Offer Letters</h3>
                <p class="text-secondary small mb-0">Generate, send, and manage formal candidate employment offer letters.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#createOfferModal">
                <i class="bi bi-file-earmark-plus-fill me-1"></i> Generate Offer Letter
            </button>
        </div>
    </div>

    {{-- Offer Letters Table Card (No Horizontal Scrollbar, Compact SaaS Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="offerLettersTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5">Candidate</th>
                        <th class="px-2 py-2.5">Job Title & Department</th>
                        <th class="px-2 py-2.5">Offered Salary</th>
                        <th class="px-2 py-2.5">Joining Date</th>
                        <th class="px-2 py-2.5">Sent Date</th>
                        <th class="px-2 py-2.5">Status</th>
                        <th class="pe-3 text-end py-2.5" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offerLetters as $offer)
                        <tr class="hover-row" id="offerRow_{{ $offer->id }}">
                            {{-- Candidate Column --}}
                            <td class="ps-3 py-2.5 align-middle">
                                <div class="d-flex align-items-center gap-3">
                                    <x-avatar :name="$offer->application->candidate->full_name ?? 'C'" size="sm" class="flex-shrink-0" />
                                    <div>
                                        <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">{{ $offer->application->candidate->full_name ?? 'N/A' }}</span>
                                        <small class="text-secondary opacity-75 d-block text-nowrap" style="font-size: 0.725rem; white-space: nowrap;">{{ $offer->application->candidate->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Job Title & Department --}}
                            <td class="px-2 py-2.5 align-middle">
                                <span class="fw-bold text-dark d-block">{{ $offer->application->jobOpening->title ?? 'N/A' }}</span>
                                <small class="text-secondary opacity-75 d-block" style="font-size: 0.725rem;">{{ $offer->application->jobOpening->department->name ?? 'N/A' }}</small>
                            </td>

                            {{-- Offered Salary --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                <span class="text-success fw-bold">PKR {{ number_format($offer->salary_offered, 2) }}</span>
                            </td>

                            {{-- Joining Date --}}
                            <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                {{ date('M d, Y', strtotime($offer->joining_date)) }}
                            </td>

                            {{-- Sent Date --}}
                            <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">
                                {{ $offer->sent_date ? date('M d, Y', strtotime($offer->sent_date)) : 'N/A' }}
                            </td>

                            {{-- Status Badges --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                                @if($offer->status === 'Accepted')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">Accepted</span>
                                @elseif($offer->status === 'Rejected')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">Rejected</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">Pending</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="pe-3 py-2.5 text-end align-middle">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                    <a href="{{ route('offer-letters.show', $offer->id) }}" class="btn btn-action-view" title="View Offer Letter" aria-label="View Offer Letter">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('offer-letters.print', $offer->id) }}" target="_blank" class="btn btn-action-edit" title="Print Offer Letter" aria-label="Print Offer Letter">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noOffersRow">
                            <td colspan="7" class="p-0">
                                <x-empty-state title="No Offer Letters Issued" icon="bi-file-earmark-check" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Generate Offer Letter Centered Modal Overlay --}}
<div class="modal fade" id="createOfferModal" tabindex="-1" aria-labelledby="createOfferModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="createOfferModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-file-earmark-plus-fill fs-6"></i>
                    </div>
                    Generate Offer Letter
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form --}}
            <form id="createOfferForm" action="{{ route('offer-letters.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div id="modalOfferErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

                    <div class="row g-3">
                        {{-- Candidate Application Selection --}}
                        <div class="col-12">
                            <label for="application_id" class="form-label fw-bold text-dark small">Candidate Application <span class="text-danger">*</span></label>
                            <select name="application_id" id="application_id" class="form-select rounded-3 border-light-subtle" required>
                                <option value="">Select Candidate Application...</option>
                                @foreach($applications ?? [] as $app)
                                    <option value="{{ $app->id }}">
                                        {{ $app->candidate->full_name ?? 'N/A' }} — {{ $app->jobOpening->title ?? 'N/A' }} ({{ $app->status }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Salary Offered --}}
                        <div class="col-md-6">
                            <label for="salary_offered" class="form-label fw-bold text-dark small">Offered Salary (PKR) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="salary_offered" id="salary_offered" class="form-control rounded-3 border-light-subtle" placeholder="e.g. 150000" min="0" required>
                        </div>

                        {{-- Joining Date --}}
                        <div class="col-md-6">
                            <label for="joining_date" class="form-label fw-bold text-dark small">Joining Date <span class="text-danger">*</span></label>
                            <input type="date" name="joining_date" id="joining_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                        </div>
                    </div>
                </div>

                {{-- Fixed Modal Footer --}}
                <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitOfferBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Generate Offer
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const createOfferForm = document.getElementById('createOfferForm');
    const submitOfferBtn = document.getElementById('submitOfferBtn');
    const modalOfferErrors = document.getElementById('modalOfferErrors');
    const offerLettersTableBody = document.querySelector('#offerLettersTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    if (createOfferForm) {
        createOfferForm.addEventListener('submit', function (e) {
            e.preventDefault();

            submitOfferBtn.disabled = true;
            submitOfferBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';
            modalOfferErrors.classList.add('d-none');
            modalOfferErrors.innerHTML = '';

            const formData = new FormData(createOfferForm);

            fetch("{{ route('offer-letters.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitOfferBtn.disabled = false;
                submitOfferBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Generate Offer';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('createOfferModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    createOfferForm.reset();

                    // Remove empty state row if present
                    const noOffersRow = document.getElementById('noOffersRow');
                    if (noOffersRow) {
                        noOffersRow.remove();
                    }

                    // Prepend new row
                    const o = data.offerLetter;

                    let statusClass = 'bg-info-subtle text-info border border-info-subtle';
                    if (o.status === 'Accepted') statusClass = 'bg-success-subtle text-success border border-success-subtle';
                    else if (o.status === 'Rejected') statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'offerRow_' + o.id;
                    newRow.innerHTML = `
                        <td class="ps-3 py-2.5 align-middle">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center border border-primary-subtle flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${o.candidate_name.charAt(0)}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block text-nowrap" style="white-space: nowrap;">${o.candidate_name}</span>
                                    <small class="text-secondary opacity-75 d-block text-nowrap" style="font-size: 0.725rem; white-space: nowrap;">${o.candidate_email}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-2.5 align-middle">
                            <span class="fw-bold text-dark d-block">${o.job_title}</span>
                            <small class="text-secondary opacity-75 d-block" style="font-size: 0.725rem;">${o.department_name}</small>
                        </td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="text-success fw-bold">PKR ${o.salary_offered}</span>
                        </td>
                        <td class="px-2 py-2.5 text-secondary fw-medium align-middle text-nowrap" style="white-space: nowrap;">${o.joining_date}</td>
                        <td class="px-2 py-2.5 text-secondary small fw-medium align-middle text-nowrap" style="white-space: nowrap;">${o.sent_date}</td>
                        <td class="px-2 py-2.5 align-middle text-nowrap" style="white-space: nowrap;">
                            <span class="badge ${statusClass} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                ${o.status}
                            </span>
                        </td>
                        <td class="pe-3 py-2.5 text-end align-middle">
                            <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                <a href="${o.show_url}" class="btn btn-action-view" title="View Offer Letter" aria-label="View Offer Letter">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="${o.print_url}" target="_blank" class="btn btn-action-edit" title="Print Offer Letter" aria-label="Print Offer Letter">
                                    <i class="bi bi-printer-fill"></i>
                                </a>
                            </div>
                        </td>
                    `;
                    offerLettersTableBody.prepend(newRow);

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
                    modalOfferErrors.innerHTML = errHtml;
                    modalOfferErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                submitOfferBtn.disabled = false;
                submitOfferBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Generate Offer';
                modalOfferErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalOfferErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
