@extends('layouts.app')

@section('title', 'Create Performance Evaluation')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Create Performance Review Evaluation</h3>
                    <p class="text-muted mb-0">Record a new rating class, strengths, and areas of improvement.</p>
                </div>
                <a href="{{ route('performance-reviews.index') }}" class="btn btn-secondary">
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
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('performance-reviews.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee Evaluated</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                <option value="" disabled selected>Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="review_type" class="form-label">Evaluation Period Type</label>
                                <select name="review_type" id="review_type" class="form-select" required>
                                    <option value="Monthly" {{ old('review_type') === 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="Quarterly" {{ old('review_type') === 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="Annual" {{ old('review_type') === 'Annual' ? 'selected' : '' }}>Annual</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="period" class="form-label">Evaluation Period Description</label>
                                <input type="text" name="period" id="period" class="form-control" value="{{ old('period') }}" placeholder="e.g. Q2 2026, June 2026" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="rating" class="form-label">Rating Value (1.00 to 5.00)</label>
                                <input type="number" step="0.01" min="1" max="5" name="rating" id="rating" class="form-control" value="{{ old('rating', 3.00) }}" placeholder="e.g. 4.50" required>
                            </div>
                            <div class="col-md-6">
                                <label for="review_date" class="form-label">Evaluation Date</label>
                                <input type="date" name="review_date" id="review_date" class="form-control" value="{{ old('review_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="strengths" class="form-label">Strengths</label>
                            <textarea name="strengths" id="strengths" class="form-control" rows="3" placeholder="Key strengths shown by the employee">{{ old('strengths') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="improvements" class="form-label">Areas for Improvement</label>
                            <textarea name="improvements" id="improvements" class="form-control" rows="3" placeholder="Key areas where development is needed">{{ old('improvements') }}</textarea>
                        </div>

                        <div class="mb-4 col-md-6">
                            <label for="status" class="form-label">Review Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Completed" {{ old('status', 'Completed') === 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Evaluation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
