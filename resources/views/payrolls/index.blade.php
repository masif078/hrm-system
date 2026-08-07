@extends('layouts.app')

@section('title', 'Payroll Directory')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Payroll Directory</h3>
                    <p class="text-muted mb-0">View and manage generated monthly payrolls.</p>
                </div>
                <div>
                    <a href="{{ route('payrolls.dashboard') }}" class="btn btn-info text-white me-2">
                        Payroll Dashboard
                    </a>
                    <a href="{{ route('payrolls.create') }}" class="btn btn-success">
                        + Generate Payroll
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small">Search Employee</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Month</label>
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
                        <label class="form-label small">Year</label>
                        <select name="year" class="form-select">
                            <option value="">All Years</option>
                            @for ($y=date('Y')-2; $y<=date('Y')+1; $y++)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Payment Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid gap-2 d-md-flex align-items-end">
                        <button class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('payrolls.index') }}" class="btn btn-secondary w-100">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll List -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Month & Year</th>
                        <th>Gross Salary</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th width="240" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td>
                                <strong>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $payroll->employee->department?->name }}</small>
                            </td>
                            <td>
                                {{ date('F', mktime(0, 0, 0, $payroll->month, 10)) }} {{ $payroll->year }}
                            </td>
                            <td>{{ number_format($payroll->gross_salary, 2) }}</td>
                            <td class="text-danger">-{{ number_format($payroll->total_deductions, 2) }}</td>
                            <td><strong>{{ number_format($payroll->net_salary, 2) }}</strong></td>
                            <td>
                                @if($payroll->payment_status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                    <br>
                                    <small class="text-muted">{{ $payroll->payment_date }}</small>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('payrolls.show', $payroll->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('payrolls.edit', $payroll->id) }}" class="btn btn-warning btn-sm">Pay/Edit</a>
                                <form action="{{ route('payrolls.destroy', $payroll->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payroll record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No payroll records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $payrolls->appends(request()->input())->links() }}
    </div>
</div>
@endsection
