@extends('layouts.app')

@section('title', 'Company Assets')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Company Assets']
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
                <h3 class="fw-bold text-dark mb-1">Company Assets Inventory</h3>
                <p class="text-secondary small mb-0">Manage hardware, laptops, serial numbers, warranties, and physical asset details.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#addAssetDrawer" aria-controls="addAssetDrawer">
                <i class="bi bi-plus-lg me-1"></i> Add New Asset
            </button>
        </div>
    </div>

    {{-- Company Assets Table Card (SaaS Layout, Auto Column Widths, No Text Overlap) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-x-auto">
            <table class="table align-middle mb-0 w-100" id="assetsTable" style="font-size: 0.825rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="px-4 py-3 text-nowrap">Asset Name</th>
                        <th class="px-4 py-3 text-nowrap">Category</th>
                        <th class="px-4 py-3 text-nowrap">Serial Number</th>
                        <th class="px-4 py-3 text-nowrap">Purchase Date</th>
                        <th class="px-4 py-3 text-nowrap">Warranty Expiry</th>
                        <th class="px-4 py-3 text-nowrap">Cost</th>
                        <th class="px-4 py-3 text-nowrap">Status</th>
                        <th class="px-4 py-3 text-center text-nowrap" width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                        <tr class="hover-row" id="assetRow_{{ $asset->id }}">
                            {{-- Asset Name --}}
                            <td class="px-4 py-3 align-middle">
                                <span class="fw-bold text-dark d-block text-nowrap">{{ $asset->name }}</span>
                            </td>

                            {{-- Category --}}
                            <td class="px-4 py-3 text-secondary fw-semibold align-middle text-nowrap">
                                {{ $asset->category->name ?? 'N/A' }}
                            </td>

                            {{-- Serial Number --}}
                            <td class="px-4 py-3 align-middle text-nowrap">
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-3 px-2.5 py-1.5 font-monospace fs-7">
                                    {{ $asset->serial_number }}
                                </span>
                            </td>

                            {{-- Purchase Date --}}
                            <td class="px-4 py-3 text-secondary small fw-medium align-middle text-nowrap">
                                {{ date('M d, Y', strtotime($asset->purchase_date)) }}
                            </td>

                            {{-- Warranty Expiry Color Coded --}}
                            <td class="px-4 py-3 align-middle text-nowrap">
                                @if($asset->warranty_expiry)
                                    @if(strtotime($asset->warranty_expiry) < time())
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold fs-7">
                                            Expired ({{ date('M d, Y', strtotime($asset->warranty_expiry)) }})
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold fs-7">
                                            Active ({{ date('M d, Y', strtotime($asset->warranty_expiry)) }})
                                        </span>
                                    @endif
                                @else
                                    <span class="text-secondary opacity-50 small">N/A</span>
                                @endif
                            </td>

                            {{-- Cost --}}
                            <td class="px-4 py-3 align-middle text-nowrap">
                                <span class="fw-bold text-dark fs-7">PKR {{ number_format($asset->cost, 2) }}</span>
                            </td>

                            {{-- Status Badges --}}
                            <td class="px-4 py-3 align-middle text-nowrap">
                                @php
                                    $st = $asset->status;
                                    $statusClass = 'bg-success-subtle text-success border border-success-subtle';
                                    if ($st === 'Assigned') {
                                        $statusClass = 'bg-primary-subtle text-primary border border-primary-subtle';
                                    } elseif ($st === 'Maintenance') {
                                        $statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                                    } elseif ($st === 'Lost') {
                                        $statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-3 py-1.5 fw-bold fs-7">
                                    {{ $asset->status }}
                                </span>
                            </td>

                            {{-- Actions Column (px-4, gap-3, Centered Icons) --}}
                            <td class="px-4 py-3 text-center align-middle text-nowrap" width="160">
                                <div class="d-flex align-items-center justify-content-center gap-3" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-action-view view-asset-btn" 
                                            title="View Asset Details" 
                                            aria-label="View Asset Details"
                                            data-id="{{ $asset->id }}"
                                            data-name="{{ $asset->name }}"
                                            data-category="{{ $asset->category->name ?? 'N/A' }}"
                                            data-serial="{{ $asset->serial_number }}"
                                            data-cost="PKR {{ number_format($asset->cost, 2) }}"
                                            data-purchase="{{ date('M d, Y', strtotime($asset->purchase_date)) }}"
                                            data-warranty="{{ $asset->warranty_expiry ? date('M d, Y', strtotime($asset->warranty_expiry)) : 'N/A' }}"
                                            data-warranty-expired="{{ $asset->warranty_expiry ? (strtotime($asset->warranty_expiry) < time() ? '1' : '0') : 'none' }}"
                                            data-status="{{ $asset->status }}"
                                            data-show-url="{{ route('assets.show', $asset->id) }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-action-edit" title="Edit Asset" aria-label="Edit Asset">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this asset?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Asset" aria-label="Delete Asset">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noAssetsRow">
                            <td colspan="8" class="p-0">
                                <x-empty-state title="No Assets Registered" icon="bi-laptop" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Add New Asset Professional Slide-Over Drawer with STICKY FOOTER --}}
<div class="offcanvas offcanvas-end border-0 shadow-2xl d-flex flex-column h-100" tabindex="-1" id="addAssetDrawer" aria-labelledby="addAssetDrawerLabel" style="width: 480px; max-width: 90vw; backdrop-filter: blur(4px);">
    {{-- Drawer Header --}}
    <div class="offcanvas-header border-bottom border-light-subtle px-4 py-3 bg-white flex-shrink-0">
        <h5 class="offcanvas-title fw-bold text-dark d-flex align-items-center gap-2" id="addAssetDrawerLabel">
            <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-laptop-fill fs-6"></i>
            </div>
            Add New Asset
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    {{-- Drawer Form with Flex Column layout to make Footer Sticky --}}
    <form id="addAssetForm" action="{{ route('assets.store') }}" method="POST" class="d-flex flex-column flex-grow-1 overflow-hidden">
        @csrf
        
        {{-- Scrollable Body --}}
        <div class="offcanvas-body p-4 bg-white flex-grow-1" style="overflow-y: auto;">
            <div id="drawerAssetErrors" class="alert alert-danger d-none rounded-3 mb-3"></div>

            {{-- Section 1: Identity --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary border-bottom border-primary-subtle pb-2 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-tag-fill me-1"></i> Section 1: Asset Identity
                </h6>
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold text-dark small">Asset Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control rounded-3 border-light-subtle" placeholder="e.g. MacBook Pro M3, Dell XPS 15" required>
                </div>

                <div class="mb-3">
                    <label for="asset_category_id" class="form-label fw-bold text-dark small">Asset Category <span class="text-danger">*</span></label>
                    <select name="asset_category_id" id="asset_category_id" class="form-select rounded-3 border-light-subtle" required>
                        <option value="">Select Category...</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="serial_number" class="form-label fw-bold text-dark small">Serial / Asset ID <span class="text-danger">*</span></label>
                    <input type="text" name="serial_number" id="serial_number" class="form-control rounded-3 border-light-subtle" placeholder="e.g. SN-987654321" required>
                </div>
            </div>

            {{-- Section 2: Purchase & Warranty --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary border-bottom border-primary-subtle pb-2 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-cash-stack me-1"></i> Section 2: Financial & Warranty
                </h6>
                <div class="mb-3">
                    <label for="cost" class="form-label fw-bold text-dark small">Purchase Cost (PKR) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="cost" id="cost" class="form-control rounded-3 border-light-subtle" value="0" min="0" placeholder="e.g. 350000" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label for="purchase_date" class="form-label fw-bold text-dark small">Purchase Date <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" id="purchase_date" class="form-control rounded-3 border-light-subtle" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-6">
                        <label for="warranty_expiry" class="form-label fw-bold text-dark small">Warranty Expiry</label>
                        <input type="date" name="warranty_expiry" id="warranty_expiry" class="form-control rounded-3 border-light-subtle">
                    </div>
                </div>
            </div>

            {{-- Section 3: Status --}}
            <div class="mb-3">
                <h6 class="fw-bold text-primary border-bottom border-primary-subtle pb-2 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <i class="bi bi-flag-fill me-1"></i> Section 3: Asset Status
                </h6>
                <label for="status" class="form-label fw-bold text-dark small">Initial Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select rounded-3 border-light-subtle" required>
                    <option value="Available" selected>Available</option>
                    <option value="Assigned">Assigned</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Lost">Lost</option>
                </select>
            </div>
        </div>

        {{-- ALWAYS VISIBLE STICKY FOOTER (White Background, Top Border, Padding p-4) --}}
        <div class="offcanvas-footer border-top border-light-subtle p-4 bg-white d-flex justify-content-end gap-2 flex-shrink-0 shadow-lg" style="position: sticky; bottom: 0; z-index: 1055;">
            <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="offcanvas">Cancel</button>
            <button type="submit" id="saveAssetBtn" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                <i class="bi bi-check-circle-fill me-1"></i> Save Asset
            </button>
        </div>
    </form>
</div>

{{-- Centered Asset Details Modal Popup --}}
<div class="modal fade" id="viewAssetModal" tabindex="-1" aria-labelledby="viewAssetModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            
            {{-- Modal Header --}}
            <div class="modal-header border-bottom border-light-subtle px-4 py-3 bg-white">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="viewAssetModalLabel">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-info-circle-fill fs-6"></i>
                    </div>
                    Asset Specification & Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-4 bg-white">
                <div class="row g-4">
                    {{-- Left Card: Primary Overview --}}
                    <div class="col-md-7">
                        <div class="card border border-light-subtle rounded-4 p-4 h-100 bg-light-subtle">
                            <h4 class="fw-bold text-dark mb-1" id="detailAssetName">Asset Name</h4>
                            <p class="text-secondary small mb-3" id="detailAssetCategory">Category</p>
                            
                            <hr class="my-3 text-secondary opacity-25">

                            <div class="row g-3">
                                <div class="col-6">
                                    <span class="text-secondary small d-block mb-1">Serial / Asset ID</span>
                                    <span class="badge bg-white text-dark border border-secondary-subtle font-monospace px-2.5 py-1.5 fw-bold fs-6" id="detailSerial">SN-0000</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-secondary small d-block mb-1">Purchase Cost</span>
                                    <span class="fw-bold text-success fs-6" id="detailCost">PKR 0.00</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-secondary small d-block mb-1">Purchase Date</span>
                                    <span class="fw-semibold text-dark" id="detailPurchase">Jan 01, 2026</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-secondary small d-block mb-1">Warranty Expiry</span>
                                    <span id="detailWarrantyBadge">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Card: Status & Quick Actions --}}
                    <div class="col-md-5">
                        <div class="card border border-light-subtle rounded-4 p-4 h-100 bg-white">
                            <h6 class="fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Current Status</h6>
                            
                            <div class="mb-4">
                                <span class="badge fs-6 px-3 py-2 rounded-pill fw-bold" id="detailStatusBadge">Available</span>
                            </div>

                            <h6 class="fw-bold text-secondary text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">Full Management Page</h6>
                            <p class="text-secondary small mb-3">View full maintenance history logs and QR code scanner.</p>

                            <a href="#" id="detailShowFullPageBtn" class="btn btn-outline-primary w-100 rounded-3 fw-bold py-2 shadow-2xs d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-box-arrow-up-right"></i> Open Full Details Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fixed Modal Footer --}}
            <div class="modal-footer border-top border-light-subtle px-4 py-3 bg-light d-flex justify-content-end">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addAssetForm = document.getElementById('addAssetForm');
    const saveAssetBtn = document.getElementById('saveAssetBtn');
    const drawerAssetErrors = document.getElementById('drawerAssetErrors');
    const assetsTableBody = document.querySelector('#assetsTable tbody');
    const alertContainer = document.getElementById('alertContainer');

    // Handle View Details Modal Populate
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.view-asset-btn');
        if (btn) {
            const name = btn.getAttribute('data-name');
            const category = btn.getAttribute('data-category');
            const serial = btn.getAttribute('data-serial');
            const cost = btn.getAttribute('data-cost');
            const purchase = btn.getAttribute('data-purchase');
            const warranty = btn.getAttribute('data-warranty');
            const warrantyExpired = btn.getAttribute('data-warranty-expired');
            const status = btn.getAttribute('data-status');
            const showUrl = btn.getAttribute('data-show-url');

            document.getElementById('detailAssetName').textContent = name;
            document.getElementById('detailAssetCategory').textContent = category;
            document.getElementById('detailSerial').textContent = serial;
            document.getElementById('detailCost').textContent = cost;
            document.getElementById('detailPurchase').textContent = purchase;
            document.getElementById('detailShowFullPageBtn').setAttribute('href', showUrl);

            // Warranty Badge
            const wBadge = document.getElementById('detailWarrantyBadge');
            if (warrantyExpired === '1') {
                wBadge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold';
                wBadge.textContent = 'Expired (' + warranty + ')';
            } else if (warrantyExpired === '0') {
                wBadge.className = 'badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold';
                wBadge.textContent = 'Active (' + warranty + ')';
            } else {
                wBadge.className = 'text-secondary opacity-50 small';
                wBadge.textContent = 'N/A';
            }

            // Status Badge
            const sBadge = document.getElementById('detailStatusBadge');
            let statusClass = 'bg-success-subtle text-success border border-success-subtle';
            if (status === 'Assigned') statusClass = 'bg-primary-subtle text-primary border border-primary-subtle';
            else if (status === 'Maintenance') statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
            else if (status === 'Lost') statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';
            
            sBadge.className = 'badge ' + statusClass + ' fs-6 px-3 py-2 rounded-pill fw-bold';
            sBadge.textContent = status;

            // Show Modal
            const viewModalEl = document.getElementById('viewAssetModal');
            const viewModalInstance = new bootstrap.Modal(viewModalEl);
            viewModalInstance.show();
        }
    });

    function removeBackdrops() {
        document.querySelectorAll('.offcanvas-backdrop, .modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        document.body.removeAttribute('data-bs-overflow');
        document.body.removeAttribute('data-bs-padding-right');
    }

    const addAssetDrawerEl = document.getElementById('addAssetDrawer');
    if (addAssetDrawerEl) {
        addAssetDrawerEl.addEventListener('hidden.bs.offcanvas', function () {
            removeBackdrops();
        });
    }

    const viewAssetModalEl = document.getElementById('viewAssetModal');
    if (viewAssetModalEl) {
        viewAssetModalEl.addEventListener('hidden.bs.modal', function () {
            removeBackdrops();
        });
    }

    if (addAssetForm) {
        addAssetForm.addEventListener('submit', function (e) {
            e.preventDefault();

            saveAssetBtn.disabled = true;
            saveAssetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            drawerAssetErrors.classList.add('d-none');
            drawerAssetErrors.innerHTML = '';

            const formData = new FormData(addAssetForm);

            fetch("{{ route('assets.store') }}", {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                saveAssetBtn.disabled = false;
                saveAssetBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Asset';

                if (response.ok && data.success) {
                    // Close Offcanvas Drawer using getOrCreateInstance & trigger backdrop cleanup
                    const drawerEl = document.getElementById('addAssetDrawer');
                    const drawerInstance = bootstrap.Offcanvas.getOrCreateInstance(drawerEl);
                    if (drawerInstance) {
                        drawerInstance.hide();
                    }
                    setTimeout(removeBackdrops, 200);

                    // Reset Form
                    addAssetForm.reset();
                    document.getElementById('cost').value = "0";
                    document.getElementById('purchase_date').value = "{{ date('Y-m-d') }}";
                    document.getElementById('status').value = "Available";

                    // Remove empty state row if present
                    const noAssetsRow = document.getElementById('noAssetsRow');
                    if (noAssetsRow) {
                        noAssetsRow.remove();
                    }

                    // Prepend new row
                    const a = data.asset;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let statusClass = 'bg-success-subtle text-success border border-success-subtle';
                    if (a.status === 'Assigned') statusClass = 'bg-primary-subtle text-primary border border-primary-subtle';
                    else if (a.status === 'Maintenance') statusClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                    else if (a.status === 'Lost') statusClass = 'bg-danger-subtle text-danger border border-danger-subtle';

                    let warrantyHtml = '<span class="text-secondary opacity-50 small">N/A</span>';
                    if (a.warranty_expiry) {
                        if (a.is_expired) {
                            warrantyHtml = `<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold fs-7">Expired (${a.warranty_expiry})</span>`;
                        } else {
                            warrantyHtml = `<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold fs-7">Active (${a.warranty_expiry})</span>`;
                        }
                    }

                    const newRow = document.createElement('tr');
                    newRow.className = 'hover-row';
                    newRow.id = 'assetRow_' + a.id;
                    newRow.innerHTML = `
                        <td class="px-4 py-3 align-middle">
                            <span class="fw-bold text-dark d-block text-nowrap">${a.name}</span>
                        </td>
                        <td class="px-4 py-3 text-secondary fw-semibold align-middle text-nowrap">${a.category_name}</td>
                        <td class="px-4 py-3 align-middle text-nowrap">
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-3 px-2.5 py-1.5 font-monospace fs-7">
                                ${a.serial_number}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-secondary small fw-medium align-middle text-nowrap">${a.purchase_date}</td>
                        <td class="px-4 py-3 align-middle text-nowrap">${warrantyHtml}</td>
                        <td class="px-4 py-3 align-middle text-nowrap">
                            <span class="fw-bold text-dark fs-7">PKR ${a.cost}</span>
                        </td>
                        <td class="px-4 py-3 align-middle text-nowrap">
                            <span class="badge ${statusClass} rounded-pill px-3 py-1.5 fw-bold fs-7">
                                ${a.status}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center align-middle text-nowrap" width="160">
                            <div class="d-flex align-items-center justify-content-center gap-3" style="white-space: nowrap;">
                                <button type="button" class="btn btn-action-view view-asset-btn" 
                                        title="View Asset Details" 
                                        aria-label="View Asset Details"
                                        data-id="${a.id}"
                                        data-name="${a.name}"
                                        data-category="${a.category_name}"
                                        data-serial="${a.serial_number}"
                                        data-cost="PKR ${a.cost}"
                                        data-purchase="${a.purchase_date}"
                                        data-warranty="${a.warranty_expiry ? a.warranty_expiry : 'N/A'}"
                                        data-warranty-expired="${a.warranty_expiry ? (a.is_expired ? '1' : '0') : 'none'}"
                                        data-status="${a.status}"
                                        data-show-url="${a.show_url}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <a href="${a.edit_url}" class="btn btn-action-edit" title="Edit Asset" aria-label="Edit Asset">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="${a.destroy_url}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this asset?')">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-action-delete" title="Delete Asset" aria-label="Delete Asset">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    assetsTableBody.prepend(newRow);

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
                    } else {
                        errHtml += '<li>An error occurred while saving the asset. Please verify inputs and try again.</li>';
                    }
                    errHtml += '</ul>';
                    drawerAssetErrors.innerHTML = errHtml;
                    drawerAssetErrors.classList.remove('d-none');

                    // Scroll offcanvas drawer body to top so error message is immediately visible
                    const drawerBody = document.querySelector('#addAssetDrawer .offcanvas-body');
                    if (drawerBody) {
                        drawerBody.scrollTop = 0;
                    }
                }
            })
            .catch(error => {
                saveAssetBtn.disabled = false;
                saveAssetBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Asset';
                drawerAssetErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                drawerAssetErrors.classList.remove('d-none');

                const drawerBody = document.querySelector('#addAssetDrawer .offcanvas-body');
                if (drawerBody) {
                    drawerBody.scrollTop = 0;
                }
            });
        });
    }
});
</script>

@endsection
