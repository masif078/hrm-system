@extends('layouts.app')

@section('title', 'Edit Candidate')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Edit Candidate Profile</h3>
        <p class="text-muted">Modify candidate background details and application pipeline stage.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 800px;">
        <div class="card-body p-4">
            <form action="{{ route('candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="full_name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $candidate->full_name) }}" required>
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $candidate->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $candidate->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="source" class="form-label fw-semibold">Source</label>
                        <input type="text" name="source" id="source" class="form-control @error('source') is-invalid @enderror" value="{{ old('source', $candidate->source) }}">
                        @error('source')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold">Address</label>
                    <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $candidate->address) }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="resume" class="form-label fw-semibold">Replace Resume File (Optional)</label>
                    <input type="file" name="resume" id="resume" class="form-control @error('resume') is-invalid @enderror">
                    @if($candidate->resume)
                        <small class="text-muted d-block mt-1">Current Resume: <a href="{{ asset('storage/' . $candidate->resume) }}" target="_blank">{{ basename($candidate->resume) }}</a></small>
                    @endif
                    @error('resume')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr class="text-muted my-4">
                <h5 class="fw-bold text-dark mb-3">Hiring Metrics</h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="experience" class="form-label fw-semibold">Experience (Years)</label>
                        <input type="number" name="experience" id="experience" min="0" class="form-control @error('experience') is-invalid @enderror" value="{{ old('experience', $candidate->experience) }}">
                        @error('experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="qualification" class="form-label fw-semibold">Qualification</label>
                        <input type="text" name="qualification" id="qualification" class="form-control @error('qualification') is-invalid @enderror" value="{{ old('qualification', $candidate->qualification) }}">
                        @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="skills" class="form-label fw-semibold">Skills</label>
                    <input type="text" name="skills" id="skills" class="form-control @error('skills') is-invalid @enderror" value="{{ old('skills', $candidate->skills) }}">
                    @error('skills')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Hiring Pipeline Stage</label>
                    <select name="status" id="status" class="form-select" required>
                        @foreach(['Applied', 'Shortlisted', 'HR Interview', 'Technical Interview', 'Final Interview', 'Offer Sent', 'Accepted', 'Rejected', 'Hired'] as $stage)
                            <option value="{{ $stage }}" {{ old('status', $candidate->status) == $stage ? 'selected' : '' }}>{{ $stage }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="{{ route('candidates.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
