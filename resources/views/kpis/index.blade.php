@extends('layouts.app')

@section('title', 'KPI Management')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="mb-1">KPI & Performance Metrics</h3>
                    <p class="text-muted mb-0">Create Key Performance Indicators, assign them to employees, and record progress scores.</p>
                </div>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <a href="{{ route('kpis.create') }}" class="btn btn-success">Create KPI</a>
                    <a href="{{ route('kpis.assign') }}" class="btn btn-primary">Assign KPI</a>
                    <a href="{{ route('kpis.score') }}" class="btn btn-info">Record Score</a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabs/Sections for KPIs, Assignments, Scores --}}
    <div class="row g-4">
        {{-- Left: KPIs List --}}
        <div class="col-md-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold">KPI Definitions</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Target</th>
                                    <th width="120" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kpis as $kpi)
                                    <tr>
                                        <td>
                                            <strong>{{ $kpi->name }}</strong>
                                            <small class="text-muted d-block">{{ Str::limit($kpi->description, 60) }}</small>
                                        </td>
                                        <td>{{ $kpi->target_value }} {{ $kpi->unit }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('kpis.edit', $kpi->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('kpis.destroy', $kpi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this KPI?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No KPIs defined yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Assignments & Scores --}}
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">Active KPI Assignments</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>KPI</th>
                                    <th>Assigned Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assign)
                                    <tr>
                                        <td><strong>{{ $assign->employee->first_name }} {{ $assign->employee->last_name }}</strong></td>
                                        <td>{{ $assign->kpi->name }}</td>
                                        <td>{{ $assign->assigned_date }}</td>
                                        <td>
                                            <span class="badge {{ $assign->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $assign->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">No active KPI assignments.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-info text-dark py-3">
                    <h5 class="mb-0 fw-bold">KPI Scores History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>KPI</th>
                                    <th>Period</th>
                                    <th>Score</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scores as $score)
                                    <tr>
                                        <td><strong>{{ $score->employee->first_name }} {{ $score->employee->last_name }}</strong></td>
                                        <td>{{ $score->kpi->name }}</td>
                                        <td>{{ date('F Y', mktime(0, 0, 0, $score->period_month, 10, $score->period_year)) }}</td>
                                        <td>
                                            <strong>{{ $score->score }}</strong> / {{ $score->kpi->target_value }} {{ $score->kpi->unit }}
                                        </td>
                                        <td><small class="text-muted">{{ $score->comments }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">No KPI scores recorded yet.</td>
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
