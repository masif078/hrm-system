@extends('layouts.app')

@section('title', 'Edit Salary Structure')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit Salary Structure</h3>
                    <p class="text-muted mb-0">Modify salary, allowances, and deductions for the employee.</p>
                </div>
                <a href="{{ route('salary-structures.index') }}" class="btn btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('salary-structures.update', $salaryStructure->id) }}" method="POST" id="salaryForm">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Side: Basic Info & Allowances -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">Earnings & Allowances</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $salaryStructure->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="basic_salary" class="form-label">Basic Salary <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="basic_salary" id="basic_salary" class="form-control calc-trigger @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary', $salaryStructure->basic_salary) }}" required>
                            @error('basic_salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="house_allowance" class="form-label">House Rent Allowance</label>
                            <input type="number" step="0.01" name="house_allowance" id="house_allowance" class="form-control calc-trigger" value="{{ old('house_allowance', $salaryStructure->house_allowance) }}">
                        </div>

                        <div class="mb-3">
                            <label for="medical_allowance" class="form-label">Medical Allowance</label>
                            <input type="number" step="0.01" name="medical_allowance" id="medical_allowance" class="form-control calc-trigger" value="{{ old('medical_allowance', $salaryStructure->medical_allowance) }}">
                        </div>

                        <div class="mb-3">
                            <label for="transport_allowance" class="form-label">Transport Allowance</label>
                            <input type="number" step="0.01" name="transport_allowance" id="transport_allowance" class="form-control calc-trigger" value="{{ old('transport_allowance', $salaryStructure->transport_allowance) }}">
                        </div>

                        <div class="mb-3">
                            <label for="other_allowance" class="form-label">Other Allowance</label>
                            <input type="number" step="0.01" name="other_allowance" id="other_allowance" class="form-control calc-trigger" value="{{ old('other_allowance', $salaryStructure->other_allowance) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Deductions & Net Output -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">Deductions & Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="tax" class="form-label">Income Tax</label>
                            <input type="number" step="0.01" name="tax" id="tax" class="form-control calc-trigger" value="{{ old('tax', $salaryStructure->tax) }}">
                        </div>

                        <div class="mb-3">
                            <label for="provident_fund" class="form-label">Provident Fund (PF)</label>
                            <input type="number" step="0.01" name="provident_fund" id="provident_fund" class="form-control calc-trigger" value="{{ old('provident_fund', $salaryStructure->provident_fund) }}">
                        </div>

                        <div class="mb-3">
                            <label for="other_deduction" class="form-label">Other Deduction</label>
                            <input type="number" step="0.01" name="other_deduction" id="other_deduction" class="form-control calc-trigger" value="{{ old('other_deduction', $salaryStructure->other_deduction) }}">
                        </div>

                        <div class="mb-3">
                            <label for="effective_from" class="form-label">Effective From <span class="text-danger">*</span></label>
                            <input type="date" name="effective_from" id="effective_from" class="form-control @error('effective_from') is-invalid @enderror" value="{{ old('effective_from', $salaryStructure->effective_from) }}" required>
                            @error('effective_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status', $salaryStructure->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $salaryStructure->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Auto Calculation Preview -->
                <div class="card bg-light border-primary shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h5 class="text-primary mb-3">Live Salary Calculation</h5>
                        <div class="row">
                            <div class="col-4">
                                <small class="text-muted d-block">Gross Earnings</small>
                                <strong id="previewGross" class="text-success fs-5">0.00</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Total Deductions</small>
                                <strong id="previewDeductions" class="text-danger fs-5">0.00</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Net Salary</small>
                                <strong id="previewNet" class="text-dark fs-4">0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">Update Salary Structure</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const triggers = document.querySelectorAll('.calc-trigger');
        
        function calculate() {
            const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
            const house = parseFloat(document.getElementById('house_allowance').value) || 0;
            const medical = parseFloat(document.getElementById('medical_allowance').value) || 0;
            const transport = parseFloat(document.getElementById('transport_allowance').value) || 0;
            const otherAllow = parseFloat(document.getElementById('other_allowance').value) || 0;
            
            const tax = parseFloat(document.getElementById('tax').value) || 0;
            const pf = parseFloat(document.getElementById('provident_fund').value) || 0;
            const otherDeduct = parseFloat(document.getElementById('other_deduction').value) || 0;
            
            const gross = basic + house + medical + transport + otherAllow;
            const deductions = tax + pf + otherDeduct;
            const net = gross - deductions;
            
            document.getElementById('previewGross').innerText = gross.toFixed(2);
            document.getElementById('previewDeductions').innerText = deductions.toFixed(2);
            
            const netEl = document.getElementById('previewNet');
            netEl.innerText = net.toFixed(2);
            if (net < 0) {
                netEl.className = 'text-danger fs-4';
            } else {
                netEl.className = 'text-dark fs-4';
            }
        }
        
        triggers.forEach(input => {
            input.addEventListener('input', calculate);
        });
        
        // Initial run
        calculate();
    });
</script>
@endsection
