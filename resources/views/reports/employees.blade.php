@extends('layouts.app')

@section('title', 'Employee Report')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Reports', 'url' => route('reports.index')],
        ['label' => 'Employee Report']
    ]" />

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Employee Report</h3>
                <p class="text-secondary small mb-0">Comprehensive staff directory report, department allocations, and exportable documentation.</p>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('reports.employees.pdf') }}" class="btn btn-outline-danger fw-semibold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1.5 shadow-2xs">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Download PDF
                </a>
                <a href="{{ route('reports.employees.excel') }}" class="btn btn-outline-success fw-semibold px-3 py-2 rounded-3 d-inline-flex align-items-center gap-1.5 shadow-2xs">
                    <i class="bi bi-file-earmark-excel-fill"></i> Download Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Professional Search Component Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" action="{{ route('reports.employees') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label class="form-label small fw-semibold text-secondary mb-1">Search Employee</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-light-subtle text-secondary ps-3">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               name="search"
                               class="form-control rounded-end-3 border-light-subtle shadow-2xs"
                               placeholder="Search employee by name or email address..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-50 rounded-3 fw-bold text-white shadow-sm py-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-search"></i> Search
                    </button>
                    <a href="{{ route('reports.employees') }}" class="btn btn-outline-secondary w-50 rounded-3 fw-semibold py-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Employee Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Employee Name</th>
                        <th class="py-3">Email Address</th>
                        <th class="py-3">Department</th>
                        <th class="py-3">Designation</th>
                        <th class="pe-4 text-end py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr class="hover-row">
                            <td class="ps-4 fw-bold text-secondary">#{{ $employee->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="$employee->first_name . ' ' . $employee->last_name" size="sm" />
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                                        @if($employee->employee_id)
                                            <small class="text-secondary opacity-75 d-block">ID: {{ $employee->employee_id }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-secondary small">{{ $employee->email }}</td>
                            <td>
                                <span class="badge bg-light text-dark border border-light-subtle rounded-pill px-3 py-1 fw-medium">
                                    {{ $employee->department?->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border border-light-subtle rounded-pill px-3 py-1 fw-medium">
                                    {{ $employee->designation?->title ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <x-status-badge :status="$employee->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-0">
                                <x-empty-state title="No Employees Found" icon="bi-person-x" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

@endsection