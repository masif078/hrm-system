@extends('layouts.app')

@section('title', 'Performance Reports')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="mb-1">Performance & Appraisals Analytics</h3>
                    <p class="text-muted mb-0">Overview of top/low performers, KPI progress scores, increments, and promotion logs.</p>
                </div>
                
                {{-- Filter by Department --}}
                <form method="GET" class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                    <select name="department_id" class="form-select form-select-sm" style="width: 220px;">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('reports.performance') }}" class="btn btn-secondary btn-sm">Reset</a>
                </form>
            </div>
        </div>
    </div>

    {{-- Top & Low Performers Row --}}
    <div class="row g-4 mb-4">
        {{-- Top Performers --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold">Top Performers (Rating >= 4.0)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Avg Rating</th>
                                    <th>Goal Completion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPerformers as $employee)
                                    <tr>
                                        <td><strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong></td>
                                        <td><span class="text-success fw-bold">{{ number_format($employee->performance_reviews_avg_rating, 2) }} / 5.00</span></td>
                                        <td>{{ $employee->completed_goals_count }} of {{ $employee->goals_count }} completed</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">No top performers tracked.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Low Performers --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold">Needs Improvement (Rating < 3.0)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Avg Rating</th>
                                    <th>Total Goals</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowPerformers as $employee)
                                    <tr>
                                        <td><strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong></td>
                                        <td><span class="text-danger fw-bold">{{ number_format($employee->performance_reviews_avg_rating, 2) }} / 5.00</span></td>
                                        <td>{{ $employee->goals_count }} goals</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">No low performers tracked.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI Progress & Appraisal History --}}
    <div class="row g-4">
        {{-- KPI completion --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold">KPI Achievement Scores</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>KPI Metric</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kpiScores as $score)
                                    <tr>
                                        <td><strong>{{ $score->employee->first_name }} {{ $score->employee->last_name }}</strong></td>
                                        <td>{{ $score->kpi->name }}</td>
                                        <td>
                                            <strong>{{ $score->score }}</strong> / {{ $score->kpi->target_value }} {{ $score->kpi->unit }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">No KPI scores recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Promotion & Salary Increment Logs --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">Salary Increments & Promotion Logs</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Action Type</th>
                                    <th>Salary Change</th>
                                    <th>Promotion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appraisals as $appraisal)
                                    <tr>
                                        <td><strong>{{ $appraisal->employee->first_name }} {{ $appraisal->employee->last_name }}</strong></td>
                                        <td><span class="badge bg-secondary">{{ $appraisal->action_type }}</span></td>
                                        <td>
                                            @if($appraisal->action_type === 'Increment' || $appraisal->action_type === 'Both')
                                                PKR {{ number_format($appraisal->previous_salary, 2) }} -> <strong>PKR {{ number_format($appraisal->new_salary, 2) }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($appraisal->action_type === 'Promotion' || $appraisal->action_type === 'Both')
                                                {{ $appraisal->newDesignation?->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">No approved promotions or increments recorded.</td>
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
