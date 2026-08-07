@extends('layouts.app')

@section('title', 'Asset Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Asset Details</h3>
            <p class="text-muted mb-0">Review hardware details, check-out history, and offline QR Code.</p>
        </div>
        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Back to Assets</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- Left: Details & QR Code --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-body p-4 text-center">
                    <div class="mb-3 d-inline-block p-3 border rounded bg-light">
                        {!! $qrCodeSvg !!}
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $asset->name }}</h5>
                    <p class="text-muted small">Asset Tag / Serial: <code>{{ $asset->serial_number }}</code></p>
                    
                    <span class="badge {{ $asset->status === 'Available' ? 'bg-success' : ($asset->status === 'Assigned' ? 'bg-primary' : ($asset->status === 'Maintenance' ? 'bg-warning text-dark' : 'bg-danger')) }} py-2 px-3 fs-6">
                        {{ $asset->status }}
                    </span>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Specifications</h6>
                    <table class="table table-sm table-borderless text-dark mb-0">
                        <tr>
                            <td class="fw-semibold text-muted" width="150">Category:</td>
                            <td>{{ $asset->category->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Purchase Cost:</td>
                            <td>PKR {{ number_format($asset->cost, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Purchase Date:</td>
                            <td>{{ date('M d, Y', strtotime($asset->purchase_date)) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Warranty Expiry:</td>
                            <td>
                                @if($asset->warranty_expiry)
                                    {{ date('M d, Y', strtotime($asset->warranty_expiry)) }}
                                    @if(strtotime($asset->warranty_expiry) < time())
                                        <span class="text-danger small d-block">(Expired)</span>
                                    @else
                                        <span class="text-success small d-block">(Active)</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: Assignments & Maintenance logs --}}
        <div class="col-lg-7">
            {{-- Check-out history --}}
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Assignment History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Employee</th>
                                    <th>Assign Date</th>
                                    <th>Return Date</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asset->assignments as $assign)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-semibold">{{ $assign->employee->first_name }} {{ $assign->employee->last_name }}</span>
                                            <small class="text-muted d-block">Condition: {{ $assign->condition_upon_assign }}</small>
                                        </td>
                                        <td>{{ date('M d, Y', strtotime($assign->assign_date)) }}</td>
                                        <td>
                                            @if($assign->return_date)
                                                {{ date('M d, Y', strtotime($assign->return_date)) }}
                                                <small class="text-muted d-block">Return Condition: {{ $assign->condition_upon_return }}</small>
                                            @else
                                                <span class="text-primary fw-semibold small">In Possession</span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            <span class="badge bg-light text-dark border">
                                                {{ $assign->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No assignment history logs.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Maintenance Logs & Forms --}}
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Maintenance & Repairs Logs</h5>
                </div>
                <div class="card-body">
                    @forelse($asset->maintenanceLogs as $mLog)
                        <div class="p-3 border rounded mb-2 bg-light">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark">{{ date('M d, Y', strtotime($mLog->repair_date)) }}</span>
                                <span class="text-danger fw-semibold">PKR {{ number_format($mLog->cost, 2) }}</span>
                            </div>
                            <small class="text-muted d-block">Vendor: {{ $mLog->vendor ?: 'N/A' }}</small>
                            <p class="mb-0 text-dark mt-1 small">{{ $mLog->notes ?: 'No description' }}</p>
                        </div>
                    @empty
                        <p class="text-muted small">No maintenance logs registered.</p>
                    @endforelse

                    <hr class="my-4 text-muted">
                    <h6 class="fw-bold text-dark mb-3">Add New Maintenance Entry</h6>

                    <form action="{{ route('assets.maintenance', $asset->id) }}" method="POST">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="repair_date" class="form-label fw-semibold">Repair Date</label>
                                <input type="date" name="repair_date" id="repair_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cost" class="form-label fw-semibold">Cost (PKR)</label>
                                <input type="number" name="cost" id="cost" min="0" class="form-control" value="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="vendor" class="form-label fw-semibold">Vendor / Shop Name</label>
                            <input type="text" name="vendor" id="vendor" class="form-control" placeholder="Vendor details">
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label fw-semibold">Description / Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="What was repaired?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm text-dark fw-bold">Submit Maintenance Log</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
