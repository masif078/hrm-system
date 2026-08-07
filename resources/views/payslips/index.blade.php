@extends('layouts.app')

@section('title', 'My Payslips')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">{{ auth()->user()->role === 'admin' ? 'All Payslips' : 'My Payslips' }}</h3>
                    <p class="text-muted mb-0">View, print, and download monthly salary payslips.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    @if(auth()->user()->role === 'admin')
                        <div class="col-md-4">
                            <label class="form-label small">Search Employee</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name...">
                        </div>
                    @endif
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-2 d-grid gap-2 d-md-flex align-items-end">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Month & Year</th>
                        <th>Net Salary</th>
                        <th>Payment Date</th>
                        <th width="240" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payslips as $payslip)
                        <tr>
                            <td>
                                <strong>{{ $payslip->employee->first_name }} {{ $payslip->employee->last_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $payslip->employee->department?->name }}</small>
                            </td>
                            <td>
                                {{ date('F', mktime(0, 0, 0, $payslip->month, 10)) }} {{ $payslip->year }}
                            </td>
                            <td><strong>PKR {{ number_format($payslip->net_salary, 2) }}</strong></td>
                            <td>{{ $payslip->payment_date ?: 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('payslips.show', $payslip->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('payslips.download', $payslip->id) }}" class="btn btn-primary btn-sm">Download PDF</a>
                                <a href="{{ route('payslips.print', $payslip->id) }}" target="_blank" class="btn btn-secondary btn-sm">Print</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No payslips found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $payslips->appends(request()->input())->links() }}
    </div>
</div>
@endsection
