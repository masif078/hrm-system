@extends('layouts.app')

@section('title', 'Appraisals & Increments')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Appraisals & Salary Increments</h3>
                    <p class="text-muted mb-0">Record and approve designation promotions and salary increments.</p>
                </div>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('appraisals.create') }}" class="btn btn-success">
                        Create Salary/Promotion Appraisal
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter for Admin --}}
    @if(auth()->user()->role === 'admin')
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <select name="employee_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter Appraisals</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Appraisals Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Rating Grade</th>
                            <th>Action Type</th>
                            <th>Previous Salary</th>
                            <th>New Salary</th>
                            <th>Designation Promotion</th>
                            <th>Effective Date</th>
                            <th>Status</th>
                            @if(auth()->user()->role === 'admin')
                                <th width="120" class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appraisals as $appraisal)
                            <tr>
                                <td>
                                    <strong>{{ $appraisal->employee->first_name }} {{ $appraisal->employee->last_name }}</strong>
                                    <small class="text-muted d-block">ID: {{ $appraisal->employee->employee_id }}</small>
                                </td>
                                <td>
                                    {{ $appraisal->rating_class }}
                                    @if($appraisal->performanceReview)
                                        <small class="text-muted d-block">Score: {{ number_format($appraisal->performanceReview->rating, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $appraisal->action_type }}</span>
                                </td>
                                <td>PKR {{ number_format($appraisal->previous_salary, 2) }}</td>
                                <td>
                                    @if($appraisal->new_salary > $appraisal->previous_salary)
                                        <span class="text-success fw-bold">PKR {{ number_format($appraisal->new_salary, 2) }}</span>
                                    @else
                                        PKR {{ number_format($appraisal->new_salary, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if($appraisal->newDesignation)
                                        <span class="text-muted small d-block">From: {{ $appraisal->previousDesignation?->name ?: '-' }}</span>
                                        <span class="text-primary fw-semibold">To: {{ $appraisal->newDesignation->name }}</span>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($appraisal->effective_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $appraisal->status === 'Approved' ? 'bg-success' : ($appraisal->status === 'Rejected' ? 'bg-danger' : 'bg-secondary') }}">
                                        {{ $appraisal->status }}
                                    </span>
                                </td>
                                @if(auth()->user()->role === 'admin')
                                    <td class="text-center">
                                        @if($appraisal->status === 'Draft')
                                            <a href="{{ route('appraisals.edit', $appraisal->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('appraisals.destroy', $appraisal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this appraisal?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? 9 : 8 }}" class="text-center py-4 text-muted">No appraisals recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $appraisals->links() }}
    </div>
</div>
@endsection
