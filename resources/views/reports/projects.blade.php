@extends('layouts.app')

@section('title', 'Project Reports')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Reports', 'url' => route('reports.index')],
        ['label' => 'Project Reports']
    ]" />

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Project Reports</h3>
                <p class="text-secondary small mb-0">Analyze project completion milestones, corporate client allocations, and financial budgets.</p>
            </div>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-3">
                &larr; Back to Reports
            </a>
        </div>
    </div>

    {{-- Filter Section (Single Horizontal Row with Spacing & Consistent Heights) --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" action="{{ route('reports.projects') }}">
            <div class="row g-3 align-items-end">
                <div class="col-xl-3 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Project Name</label>
                    <input type="text"
                           name="search"
                           class="form-control rounded-3 border-light-subtle shadow-2xs"
                           placeholder="Search project name..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-xl-3 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">All Clients</label>
                    <select name="client" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                {{ $client->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Project Manager</label>
                    <select name="manager" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Managers</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ request('manager') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->first_name }} {{ $manager->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Status</label>
                    <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                    </select>
                </div>

                <div class="col-xl-2 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-50 rounded-3 fw-bold text-white shadow-sm py-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                    <a href="{{ route('reports.projects') }}" class="btn btn-outline-secondary w-50 rounded-3 fw-semibold py-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Projects Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Code</th>
                        <th class="py-3">Project Name</th>
                        <th class="py-3">Client</th>
                        <th class="py-3">Project Manager</th>
                        <th class="py-3">Budget (PKR)</th>
                        <th class="pe-4 text-end py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr class="hover-row">
                            <td class="ps-4 fw-bold text-secondary">#{{ $project->project_code }}</td>
                            <td class="fw-bold text-dark">{{ $project->project_name }}</td>
                            <td class="text-secondary small">{{ $project->client->company_name ?? 'N/A' }}</td>
                            <td class="text-dark small">{{ $project->manager->first_name ?? '' }} {{ $project->manager->last_name ?? '' }}</td>
                            <td class="fw-semibold text-dark">PKR {{ number_format($project->budget, 2) }}</td>
                            <td class="pe-4 text-end">
                                <x-status-badge :status="$project->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-empty-state title="No Projects Found" icon="bi-bar-chart" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $projects->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

@endsection
