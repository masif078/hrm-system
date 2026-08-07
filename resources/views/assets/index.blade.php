@extends('layouts.app')

@section('title', 'Company Assets')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Company Assets</h3>
            <p class="text-muted mb-0">Manage hardware, laptops, phones, keyfobs, and active warranties.</p>
        </div>
        <a href="{{ route('assets.create') }}" class="btn btn-primary">Add New Asset</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Asset Name</th>
                            <th>Category</th>
                            <th>Serial Number</th>
                            <th>Purchase Date</th>
                            <th>Warranty Expiry</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th class="pe-4" width="220">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $asset->name }}</span>
                                </td>
                                <td>{{ $asset->category->name ?? 'N/A' }}</td>
                                <td><code>{{ $asset->serial_number }}</code></td>
                                <td>{{ date('M d, Y', strtotime($asset->purchase_date)) }}</td>
                                <td>
                                    @if($asset->warranty_expiry)
                                        @if(strtotime($asset->warranty_expiry) < time())
                                            <span class="text-danger small fw-semibold">Expired ({{ date('M d, Y', strtotime($asset->warranty_expiry)) }})</span>
                                        @else
                                            <span class="text-success small fw-semibold">Active ({{ date('M d, Y', strtotime($asset->warranty_expiry)) }})</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td>PKR {{ number_format($asset->cost, 2) }}</td>
                                <td>
                                    <span class="badge {{ $asset->status === 'Available' ? 'bg-success' : ($asset->status === 'Assigned' ? 'bg-primary' : ($asset->status === 'Maintenance' ? 'bg-warning text-dark' : 'bg-danger')) }}">
                                        {{ $asset->status }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-outline-primary btn-sm">Details & QR</a>
                                        <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this asset?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No assets registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
