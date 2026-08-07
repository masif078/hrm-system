@extends('layouts.app')

@section('title', 'Payroll Dashboard')

@section('content')
<div class="container">
    <!-- Header Card -->
    <div class="card shadow-sm mb-4 border-0 bg-dark text-white">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 text-info">Payroll Dashboard</h2>
                    <p class="mb-0 text-light opacity-75">Overview of salary disbursements, pending payments, and recent payroll activities.</p>
                </div>
                <div>
                    <a href="{{ route('payrolls.create') }}" class="btn btn-success me-2">+ Run Payroll</a>
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Payroll List</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Total Payroll -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-uppercase text-secondary fw-semibold">Total Runs</small>
                            <h3 class="mt-2 mb-0 fw-bold text-dark">{{ $totalPayroll }}</h3>
                        </div>
                        <div class="rounded bg-info bg-opacity-10 p-3 text-info">
                            <i class="fs-4">📊</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Salary Paid -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-uppercase text-secondary fw-semibold">Total Paid</small>
                            <h3 class="mt-2 mb-0 fw-bold text-success">PKR {{ number_format($totalPaid, 2) }}</h3>
                        </div>
                        <div class="rounded bg-success bg-opacity-10 p-3 text-success">
                            <i class="fs-4">💵</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Pending Payroll -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-uppercase text-secondary fw-semibold">Pending Payments</small>
                            <h3 class="mt-2 mb-0 fw-bold text-danger">PKR {{ number_format($pendingPayroll, 2) }}</h3>
                        </div>
                        <div class="rounded bg-danger bg-opacity-10 p-3 text-danger">
                            <i class="fs-4">⏳</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Employees Paid -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-uppercase text-secondary fw-semibold">Employees Paid</small>
                            <h3 class="mt-2 mb-0 fw-bold text-primary">{{ $employeesPaidCount }}</h3>
                        </div>
                        <div class="rounded bg-primary bg-opacity-10 p-3 text-primary">
                            <i class="fs-4">👤</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Section -->
    <div class="row g-4">
        <!-- Recent Payroll Runs -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-0 bg-white">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold text-dark">Recent Payroll Runs</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Period</th>
                                    <th>Net Salary</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayrolls as $p)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $p->employee->first_name }} {{ $p->employee->last_name }}</span>
                                            <div class="small text-muted">{{ $p->employee->department?->name }}</div>
                                        </td>
                                        <td>{{ date('F Y', mktime(0,0,0, $p->month, 10, $p->year)) }}</td>
                                        <td>PKR {{ number_format($p->net_salary, 2) }}</td>
                                        <td>
                                            @if($p->payment_status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No recent payrolls found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-0 bg-white">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold text-dark">Pending Disbursements</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Period</th>
                                    <th>Net Salary</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingPayments as $p)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $p->employee->first_name }} {{ $p->employee->last_name }}</span>
                                            <div class="small text-muted">{{ $p->employee->department?->name }}</div>
                                        </td>
                                        <td>{{ date('F Y', mktime(0,0,0, $p->month, 10, $p->year)) }}</td>
                                        <td>PKR {{ number_format($p->net_salary, 2) }}</td>
                                        <td>
                                            <a href="{{ route('payrolls.edit', $p->id) }}" class="btn btn-warning btn-sm">Pay</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No pending payments. Good job!</td>
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
