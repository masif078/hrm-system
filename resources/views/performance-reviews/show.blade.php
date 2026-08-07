@extends('layouts.app')

@section('title', 'Performance Evaluation Details')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Evaluation Details</h3>
                    <p class="text-muted mb-0">Detailed summary of review scorecard.</p>
                </div>
                <a href="{{ route('performance-reviews.index') }}" class="btn btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold">Scorecard Review Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 border-bottom pb-3 mb-3">
                        <div class="col-6">
                            <span class="text-muted d-block small">Employee</span>
                            <span class="fw-bold text-dark">{{ $review->employee->first_name }} {{ $review->employee->last_name }}</span>
                            <small class="text-secondary d-block">Department: {{ $review->employee->department?->name }}</small>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block small">Evaluator / Reviewer</span>
                            <span class="fw-bold text-dark">
                                @if($review->reviewer)
                                    {{ $review->reviewer->first_name }} {{ $review->reviewer->last_name }}
                                @else
                                    System Admin
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="row g-3 border-bottom pb-3 mb-3">
                        <div class="col-4">
                            <span class="text-muted d-block small">Review Type</span>
                            <span class="fw-semibold text-dark">{{ $review->review_type }}</span>
                        </div>
                        <div class="col-4">
                            <span class="text-muted d-block small">Period</span>
                            <span class="fw-semibold text-dark">{{ $review->period }}</span>
                        </div>
                        <div class="col-4">
                            <span class="text-muted d-block small">Review Date</span>
                            <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($review->review_date)->format('F d, Y') }}</span>
                        </div>
                    </div>

                    <div class="border rounded p-3 bg-light text-center mb-4">
                        <span class="text-muted d-block small mb-1">Overall Rating Score</span>
                        <h2 class="fw-bold text-primary mb-0">{{ number_format($review->rating, 2) }} / 5.00</h2>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold border-bottom pb-1">Key Strengths Demonstrated:</h6>
                        <p class="text-dark" style="white-space: pre-line;">{{ $review->strengths ?: 'No remarks recorded.' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold border-bottom pb-1">Areas Target for Improvement:</h6>
                        <p class="text-dark" style="white-space: pre-line;">{{ $review->improvements ?: 'No remarks recorded.' }}</p>
                    </div>

                    <div class="mt-4 border-top pt-3 d-flex justify-content-between">
                        <div>
                            <span class="text-muted small">Status:</span>
                            <span class="badge {{ $review->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $review->status }}
                            </span>
                        </div>
                        @if(auth()->user()->role === 'admin' && !$review->appraisal)
                            <a href="{{ route('appraisals.create', ['review_id' => $review->id]) }}" class="btn btn-primary">
                                Process Appraisal / Salary Action
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
