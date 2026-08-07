@extends('layouts.app')

@section('title', 'Record KPI Score')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Record KPI Score</h3>
                    <p class="text-muted mb-0">Track achievement scores for employee KPI metrics.</p>
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
                    <form action="{{ route('kpis.score.store') }}" method="POST">
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

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="period_month" class="form-label">Period Month</label>
                                <select name="period_month" id="period_month" class="form-select" required>
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ old('period_month', date('n')) == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="period_year" class="form-label">Period Year</label>
                                <select name="period_year" id="period_year" class="form-select" required>
                                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                        <option value="{{ $y }}" {{ old('period_year', date('Y')) == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="score" class="form-label">Score Attained</label>
                            <input type="number" step="0.01" name="score" id="score" class="form-control" value="{{ old('score') }}" placeholder="e.g. 85.50" required>
                        </div>

                        <div class="mb-4">
                            <label for="comments" class="form-label">Comments / Notes</label>
                            <textarea name="comments" id="comments" class="form-control" rows="2">{{ old('comments') }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save KPI Score</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
