@extends('layouts.app')

@section('title', 'Salary Structure Details')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Salary Structure Breakdown</h3>
                    <p class="text-muted mb-0">Detailed view of the defined earnings, allowances, and deductions.</p>
                </div>
                <div>
                    <a href="{{ route('salary-structures.edit', $salaryStructure->id) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('salary-structures.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Side: Employee Profile Summary -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="rounded-circle mb-3 bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($salaryStructure->employee->first_name, 0, 1) }}
                    </div>
                    <h4>{{ $salaryStructure->employee->first_name }} {{ $salaryStructure->employee->last_name }}</h4>
                    <p class="text-secondary mb-1">ID: {{ $salaryStructure->employee->employee_id }}</p>
                    <span class="badge bg-secondary mb-2">{{ $salaryStructure->employee->department?->name }}</span>
                    <span class="badge bg-info text-dark mb-3">{{ $salaryStructure->employee->designation?->title }}</span>
                    <hr>
                    <div class="text-start">
                        <p class="mb-2"><strong>Effective From:</strong> {{ $salaryStructure->effective_from }}</p>
                        <p class="mb-2"><strong>Status:</strong> 
                            @if($salaryStructure->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                        <p class="mb-0"><strong>Last Updated:</strong> {{ $salaryStructure->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Calculations & Breakdown -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Earnings & Deductions Breakdown</h5>
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
                            <!-- Earnings -->
                            <tr>
                                <td rowspan="5" class="align-middle fw-bold text-success bg-light text-center">Earnings & Allowances</td>
                                <td>Basic Salary</td>
                                <td class="text-end">{{ number_format($salaryStructure->basic_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <td>House Rent Allowance</td>
                                <td class="text-end">{{ number_format($salaryStructure->house_allowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Medical Allowance</td>
                                <td class="text-end">{{ number_format($salaryStructure->medical_allowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Transport Allowance</td>
                                <td class="text-end">{{ number_format($salaryStructure->transport_allowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Other Allowance</td>
                                <td class="text-end text-success">{{ number_format($salaryStructure->other_allowance, 2) }}</td>
                            </tr>

                            <!-- Deductions -->
                            <tr>
                                <td rowspan="3" class="align-middle fw-bold text-danger bg-light text-center">Deductions</td>
                                <td>Income Tax</td>
                                <td class="text-end text-danger">-{{ number_format($salaryStructure->tax, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Provident Fund (PF)</td>
                                <td class="text-end text-danger">-{{ number_format($salaryStructure->provident_fund, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Other Deduction</td>
                                <td class="text-end text-danger">-{{ number_format($salaryStructure->other_deduction, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-dark">
                            <?php
                                $total_allowances = $salaryStructure->house_allowance + $salaryStructure->medical_allowance + $salaryStructure->transport_allowance + $salaryStructure->other_allowance;
                                $gross_salary = $salaryStructure->basic_salary + $total_allowances;
                                $total_deductions = $salaryStructure->tax + $salaryStructure->provident_fund + $salaryStructure->other_deduction;
                            ?>
                            <tr>
                                <th colspan="2">Gross Salary (Basic + Allowances)</th>
                                <th class="text-end">{{ number_format($gross_salary, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="2">Total Deductions</th>
                                <th class="text-end text-warning">-{{ number_format($total_deductions, 2) }}</th>
                            </tr>
                            <tr class="fs-5">
                                <th colspan="2" class="text-info">Net Salary (Take Home)</th>
                                <th class="text-end text-info">{{ number_format($salaryStructure->net_salary, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
