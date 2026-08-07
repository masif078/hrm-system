@extends('layouts.app')

@section('title', 'Generate Payroll')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Generate Monthly Payroll</h3>
                    <p class="text-muted mb-0">Generate salary payroll for active employees based on their active salary structures.</p>
                </div>
                <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
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

    <div class="row">
        <!-- Main Form Card -->
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Payroll Parameters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payrolls.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="month" class="form-label">Month <span class="text-danger">*</span></label>
                                <select name="month" id="month" class="form-select" required>
                                    <option value="">Select Month</option>
                                    @for ($m=1; $m<=12; $m++)
                                        <option value="{{ $m }}" {{ old('month', date('n')) == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                                <select name="year" id="year" class="form-select" required>
                                    @for ($y=date('Y')-1; $y<=date('Y')+1; $y++)
                                        <option value="{{ $y }}" {{ old('year', date('Y')) == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Target Employee (Optional)</label>
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value="">Generate for ALL Active Employees</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Leave blank to generate payroll in bulk for all eligible active employees.</div>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks / Description</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control" placeholder="E.g., Monthly payroll run for active staff.">{{ old('remarks') }}</textarea>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Generate payroll for the selected month/year? Existing payrolls will be skipped.')">
                                Run Payroll Processing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
