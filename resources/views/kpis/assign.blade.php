@extends('layouts.app')

@section('title', 'Assign KPI')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Assign KPI to Employee</h3>
                    <p class="text-muted mb-0">Link a performance indicator to an employee profile.</p>
                </div>
                <a href="{{ route('kpis.index') }}" class="btn btn-secondary">
                    Back to Dashboard
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
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('kpis.assign.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                <option value="" disabled selected>Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kpi_id" class="form-label">KPI Metric</label>
                            <select name="kpi_id" id="kpi_id" class="form-select" required>
                                <option value="" disabled selected>Select KPI</option>
                                @foreach($kpis as $kpi)
                                    <option value="{{ $kpi->id }}" {{ old('kpi_id') == $kpi->id ? 'selected' : '' }}>
                                        {{ $kpi->name }} (Target: {{ $kpi->target_value }} {{ $kpi->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="assigned_date" class="form-label">Assigned Date</label>
                            <input type="date" name="assigned_date" id="assigned_date" class="form-control" value="{{ old('assigned_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Assign KPI</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
