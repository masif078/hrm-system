@extends('layouts.app')

@section('title', 'Recruitment Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Recruitment Reports</h3>
            <p class="text-muted mb-0">Recruitment pipeline analytics, applicant channels, and offer acceptance logs.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Reports Dashboard</a>
    </div>

    {{-- Stats Cards Row --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4">
                    <span class="text-muted d-block small mb-1">Total Offers Generated</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalOffers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4">
                    <span class="text-muted d-block small mb-1">Accepted Offers</span>
                    <h3 class="fw-bold mb-0 text-dark text-success">{{ $acceptedOffers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-4">
                    <span class="text-muted d-block small mb-1">Offer Acceptance Rate</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $acceptanceRate }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Pipeline stages --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Hiring Stages Conversion</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Hiring Stage</th>
                                    <th class="pe-4 text-end">Active Applicants</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Applied', 'Shortlisted', 'HR Interview', 'Technical Interview', 'Final Interview', 'Offer Sent', 'Accepted', 'Rejected', 'Hired'] as $stage)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-dark">{{ $stage }}</td>
                                        <td class="pe-4 text-end">
                                            <strong>{{ $stageStats->where('status', $stage)->first()->count ?? 0 }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Applicant Channels (Sources) --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-white h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Candidate Acquisition Channels</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Channel / Source</th>
                                    <th class="pe-4 text-end">Candidates Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sourceStats as $stat)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-dark">{{ $stat->source ?: 'Direct / Unknown' }}</td>
                                        <td class="pe-4 text-end"><strong>{{ $stat->count }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">No channels recorded.</td>
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
