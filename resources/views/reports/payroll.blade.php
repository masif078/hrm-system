@extends('layouts.app')

@section('title', 'Payroll Report')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-dark text-white py-3">
            <h4 class="mb-0 fw-bold">Payroll Report</h4>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Employee</label>
                    <select name="employee" class="form-select">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Department</label>
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-secondary">Month</label>
                    <select name="month" class="form-select">
                        <option value="">All Months</option>
                        @for ($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-secondary">Year</label>
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @for ($y=date('Y')-2; $y<=date('Y')+1; $y++)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 d-grid gap-2 d-md-flex align-items-end">
                    <button class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('reports.payroll') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>

            <!-- Aggregated Totals Grid -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="border rounded p-3 text-center bg-light">
                        <small class="text-uppercase text-secondary fw-semibold">Disbursed (Paid) Salary</small>
                        <h4 class="fw-bold text-success mb-0 mt-1">PKR {{ number_format($totalPaid, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 text-center bg-light">
                        <small class="text-uppercase text-secondary fw-semibold">Total Allowances</small>
                        <h4 class="fw-bold text-primary mb-0 mt-1">PKR {{ number_format($totalAllowances, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 text-center bg-light">
                        <small class="text-uppercase text-secondary fw-semibold">Total Deductions</small>
                        <h4 class="fw-bold text-danger mb-0 mt-1">PKR {{ number_format($totalDeductions, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 text-center bg-light">
                        <small class="text-uppercase text-secondary fw-semibold">Net Payroll (All)</small>
                        <h4 class="fw-bold text-dark mb-0 mt-1">PKR {{ number_format($totalNet, 2) }}</h4>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Period</th>
                            <th>Gross Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                            <tr>
                                <td>
                                    <strong>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</strong>
                                    <br>
                                    <small class="text-muted">ID: {{ $payroll->employee->employee_id }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $payroll->employee->department?->name }}</span>
                                </td>
                                <td>{{ date('F Y', mktime(0,0,0, $payroll->month, 10, $payroll->year)) }}</td>
                                <td>{{ number_format($payroll->gross_salary, 2) }}</td>
                                <td class="text-success">+{{ number_format($payroll->total_allowances, 2) }}</td>
                                <td class="text-danger">-{{ number_format($payroll->total_deductions, 2) }}</td>
                                <td><strong>{{ number_format($payroll->net_salary, 2) }}</strong></td>
                                <td>
                                    @if($payroll->payment_status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No payroll records found matching the filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
