@extends('layouts.app')

@section('title', 'Payroll Details')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Payroll Details</h3>
                    <p class="text-muted mb-0">Breakdown of generated earnings and deductions for the selected month.</p>
                </div>
                <div>
                    <a href="{{ route('payrolls.edit', $payroll->id) }}" class="btn btn-warning">Update Payment</a>
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="rounded-circle mb-3 bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($payroll->employee->first_name, 0, 1) }}
                    </div>
                    <h4>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</h4>
                    <p class="text-secondary mb-1">ID: {{ $payroll->employee->employee_id }}</p>
                    <span class="badge bg-secondary mb-2">{{ $payroll->employee->department?->name }}</span>
                    <span class="badge bg-info text-dark mb-3">{{ $payroll->employee->designation?->title }}</span>
                    <hr>
                    <div class="text-start">
                        <p class="mb-2"><strong>Payroll Month:</strong> {{ date('F Y', mktime(0, 0, 0, $payroll->month, 10, $payroll->year)) }}</p>
                        <p class="mb-2"><strong>Payment Status:</strong> 
                            @if($payroll->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </p>
                        @if($payroll->payment_status === 'paid')
                            <p class="mb-2"><strong>Payment Date:</strong> {{ $payroll->payment_date }}</p>
                        @endif
                        <p class="mb-0"><strong>Remarks:</strong> {{ $payroll->remarks ?: 'No remarks provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown Card -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Calculated Breakdown</h5>
                    @if($payroll->salaryStructure)
                        <span class="text-secondary small">Based on structure #{{ $payroll->salary_structure_id }}</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>Component</th>
                                <th class="text-end" width="200">Amount (PKR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($payroll->salaryStructure)
                                <tr>
                                    <td rowspan="5" class="align-middle fw-bold text-success bg-light text-center">Earnings & Allowances</td>
                                    <td>Basic Salary</td>
                                    <td class="text-end">{{ number_format($payroll->salaryStructure->basic_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>House Rent Allowance</td>
                                    <td class="text-end">{{ number_format($payroll->salaryStructure->house_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Medical Allowance</td>
                                    <td class="text-end">{{ number_format($payroll->salaryStructure->medical_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Transport Allowance</td>
                                    <td class="text-end">{{ number_format($payroll->salaryStructure->transport_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Other Allowance</td>
                                    <td class="text-end text-success">{{ number_format($payroll->salaryStructure->other_allowance, 2) }}</td>
                                </tr>

                                <tr>
                                    <td rowspan="3" class="align-middle fw-bold text-danger bg-light text-center">Deductions</td>
                                    <td>Income Tax</td>
                                    <td class="text-end text-danger">-{{ number_format($payroll->salaryStructure->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Provident Fund (PF)</td>
                                    <td class="text-end text-danger">-{{ number_format($payroll->salaryStructure->provident_fund, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Other Deduction</td>
                                    <td class="text-end text-danger">-{{ number_format($payroll->salaryStructure->other_deduction, 2) }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">
                                        Original salary structure details are no longer available.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th colspan="2">Total Allowances</th>
                                <th class="text-end text-success">+{{ number_format($payroll->total_allowances, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="2">Gross Salary</th>
                                <th class="text-end">{{ number_format($payroll->gross_salary, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="2">Total Deductions</th>
                                <th class="text-end text-warning">-{{ number_format($payroll->total_deductions, 2) }}</th>
                            </tr>
                            <tr class="fs-5">
                                <th colspan="2" class="text-info">Net Salary (Paid Out)</th>
                                <th class="text-end text-info">{{ number_format($payroll->net_salary, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
