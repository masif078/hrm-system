@extends('layouts.app')

@section('title', 'Asset Status Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Asset Reports</h3>
            <p class="text-muted mb-0">Track hardware categorizations, checkout ratios, and lifetime maintenance expenditures.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Reports Dashboard</a>
    </div>

    {{-- Stats Cards Row --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4">
                    <span class="text-muted d-block small mb-1">Total Assets Registered</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $assets->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4">
                    <span class="text-muted d-block small mb-1">Total Maintenance Expenditures</span>
                    <h3 class="fw-bold mb-0 text-danger">PKR {{ number_format($totalMaintenanceCost, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Assets by Status --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Assets by Status</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Status</th>
                                    <th class="pe-4 text-end">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Available', 'Assigned', 'Maintenance', 'Lost'] as $st)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-dark">{{ $st }}</td>
                                        <td class="pe-4 text-end">
                                            <strong>{{ $statusStats->where('status', $st)->first()->count ?? 0 }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Assets by Category --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Assets by Category</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Category</th>
                                    <th class="pe-4 text-end">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categoryStats as $cStat)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-dark">{{ $cStat->name }}</td>
                                        <td class="pe-4 text-end"><strong>{{ $cStat->assets_count }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">No categories recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
