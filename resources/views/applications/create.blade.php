@extends('layouts.app')

@section('title', 'New Job Application')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Submit Job Application</h3>
        <p class="text-muted">Link a candidate profile to an open job post.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="{{ route('applications.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="candidate_id" class="form-label fw-semibold">Candidate Profile</label>
                    <select name="candidate_id" id="candidate_id" class="form-select @error('candidate_id') is-invalid @enderror" required>
                        <option value="">Select Candidate</option>
                        @foreach($candidates as $candidate)
                            <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                {{ $candidate->full_name }} ({{ $candidate->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('candidate_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="job_opening_id" class="form-label fw-semibold">Open Job Posting</label>
                    <select name="job_opening_id" id="job_opening_id" class="form-select @error('job_opening_id') is-invalid @enderror" required>
                        <option value="">Select Job Opening</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ old('job_opening_id') == $job->id ? 'selected' : '' }}>
                                {{ $job->title }} ({{ $job->location }} - {{ $job->vacancies }} Vacancy)
                            </option>
                        @endforeach
                    </select>
                    @error('job_opening_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Initial Stage</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Applied">Applied</option>
                        <option value="Shortlisted">Shortlisted</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                    <a href="{{ route('applications.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
