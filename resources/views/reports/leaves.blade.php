@extends('layouts.app')

@section('title', 'Leave Report')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Reports', 'url' => route('reports.index')],
        ['label' => 'Leave Report']
    ]" />

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Leave Report</h3>
                <p class="text-secondary small mb-0">Detailed breakdown of employee leave applications, leave types, and approval statuses.</p>
            </div>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-3">
                &larr; Back to Reports
            </a>
        </div>
    </div>

    {{-- Filter Section (Single Horizontal Row with Green Filter Button & Reset Fix) --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" action="{{ route('reports.leaves') }}">
            <div class="row g-3 align-items-end">
                <div class="col-xl-3 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">All Employees</label>
                    <select name="employee" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Employees</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Status</label>
                    <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-xl-2 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Date</label>
                    <input type="date"
                           name="date"
                           class="form-control rounded-3 border-light-subtle shadow-2xs"
                           value="{{ request('date') }}">
                </div>

                <div class="col-xl-3 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Leave Type</label>
                    <select name="leave_type" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Leave Types</option>
                        <option value="Annual Leave" {{ request('leave_type') == 'Annual Leave' ? 'selected' : '' }}>Annual Leave</option>
                        <option value="Sick Leave" {{ request('leave_type') == 'Sick Leave' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="Casual Leave" {{ request('leave_type') == 'Casual Leave' ? 'selected' : '' }}>Casual Leave</option>
                        <option value="Emergency Leave" {{ request('leave_type') == 'Emergency Leave' ? 'selected' : '' }}>Emergency Leave</option>
                    </select>
                </div>

                <div class="col-xl-2 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success w-50 rounded-3 fw-bold text-white shadow-sm py-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                    <a href="{{ route('reports.leaves') }}" class="btn btn-outline-secondary w-50 rounded-3 fw-semibold py-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Leave Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Employee Name</th>
                        <th class="py-3">Leave Type</th>
                        <th class="py-3">Start Date</th>
                        <th class="py-3">End Date</th>
                        <th class="pe-4 text-end py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr class="hover-row">
                            <td class="ps-4 fw-bold text-dark">
                                {{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}
                            </td>
                            <td class="fw-semibold text-secondary">{{ $leave->leave_type }}</td>
                            <td class="text-secondary small">{{ $leave->start_date }}</td>
                            <td class="text-secondary small">{{ $leave->end_date }}</td>
                            <td class="pe-4 text-end">
                                <x-status-badge :status="$leave->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state title="No Leave Records Found" icon="bi-calendar-x" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaves->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $leaves->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

@endsection
