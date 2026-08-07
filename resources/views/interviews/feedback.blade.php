@extends('layouts.app')

@section('title', 'Interview Feedback Scorecard')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Interview Scorecard Feedback</h3>
        <p class="text-muted">Fill in ratings (1-5) and assessment remarks for candidate **{{ $interview->application->candidate->full_name }}**.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="{{ route('interviews.storeFeedback', $interview->id) }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label for="rating_technical" class="form-label fw-semibold">Technical Skills (1-5)</label>
                        <input type="number" name="rating_technical" id="rating_technical" min="1" max="5" class="form-control" value="{{ old('rating_technical', 3) }}" required>
                    </div>
                    <div class="col-6">
                        <label for="rating_communication" class="form-label fw-semibold">Communication (1-5)</label>
                        <input type="number" name="rating_communication" id="rating_communication" min="1" max="5" class="form-control" value="{{ old('rating_communication', 3) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label for="rating_behavior" class="form-label fw-semibold">Behavioral Fit (1-5)</label>
                        <input type="number" name="rating_behavior" id="rating_behavior" min="1" max="5" class="form-control" value="{{ old('rating_behavior', 3) }}" required>
                    </div>
                    <div class="col-6">
                        <label for="rating_confidence" class="form-label fw-semibold">Confidence (1-5)</label>
                        <input type="number" name="rating_confidence" id="rating_confidence" min="1" max="5" class="form-control" value="{{ old('rating_confidence', 3) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="rating_overall" class="form-label fw-semibold">Overall Rating (1-5)</label>
                    <input type="number" name="rating_overall" id="rating_overall" min="1" max="5" class="form-control" value="{{ old('rating_overall', 3) }}" required>
                </div>

                <div class="mb-4">
                    <label for="comments" class="form-label fw-semibold">Assessment Remarks / Comments</label>
                    <textarea name="comments" id="comments" rows="5" class="form-control" placeholder="Candidate's strengths, improvements, or recommendations..." required>{{ old('comments') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Save Scorecard</button>
                    <a href="{{ route('applications.show', $interview->application_id) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
