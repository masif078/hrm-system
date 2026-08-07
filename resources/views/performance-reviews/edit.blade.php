@extends('layouts.app')

@section('title', 'Edit Evaluation')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit Performance Review Evaluation</h3>
                    <p class="text-muted mb-0">Modify evaluation ratings and remarks.</p>
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
                    <form action="{{ route('performance-reviews.update', $review->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee Evaluated</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $review->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="review_type" class="form-label">Evaluation Period Type</label>
                                <select name="review_type" id="review_type" class="form-select" required>
                                    <option value="Monthly" {{ old('review_type', $review->review_type) === 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="Quarterly" {{ old('review_type', $review->review_type) === 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="Annual" {{ old('review_type', $review->review_type) === 'Annual' ? 'selected' : '' }}>Annual</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="period" class="form-label">Evaluation Period Description</label>
                                <input type="text" name="period" id="period" class="form-control" value="{{ old('period', $review->period) }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="rating" class="form-label">Rating Value (1.00 to 5.00)</label>
                                <input type="number" step="0.01" min="1" max="5" name="rating" id="rating" class="form-control" value="{{ old('rating', $review->rating) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="review_date" class="form-label">Evaluation Date</label>
                                <input type="date" name="review_date" id="review_date" class="form-control" value="{{ old('review_date', $review->review_date) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="strengths" class="form-label">Strengths</label>
                            <textarea name="strengths" id="strengths" class="form-control" rows="3" required>{{ old('strengths', $review->strengths) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="improvements" class="form-label">Areas for Improvement</label>
                            <textarea name="improvements" id="improvements" class="form-control" rows="3" required>{{ old('improvements', $review->improvements) }}</textarea>
                        </div>

                        <div class="mb-4 col-md-6">
                            <label for="status" class="form-label">Review Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Pending" {{ old('status', $review->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Completed" {{ old('status', $review->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Evaluation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
