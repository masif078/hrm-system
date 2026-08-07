@extends('layouts.app')

@section('title', 'Add Candidate')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Add Candidate</h3>
        <p class="text-muted">Register applicant profile. Uploading a `.txt` resume will trigger the **Resume Parser** (optional).</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 800px;">
        <div class="card-body p-4">
            <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="full_name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="source" class="form-label fw-semibold">Source (e.g. LinkedIn, Referral)</label>
                        <input type="text" name="source" id="source" class="form-control @error('source') is-invalid @enderror" value="{{ old('source') }}">
                        @error('source')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold">Address</label>
                    <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="resume" class="form-label fw-semibold">Upload Resume (PDF, DOCX, TXT)</label>
                    <input type="file" name="resume" id="resume" class="form-control @error('resume') is-invalid @enderror">
                    <small class="text-muted d-block mt-1">If a TXT file with keywords like `skills: PHP`, `experience: 3`, `qualification: BS` is uploaded, it will automatically populate fields.</small>
                    @error('resume')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr class="text-muted my-4">
                <h5 class="fw-bold text-dark mb-3">Manual Override / Fallback Fields</h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="experience" class="form-label fw-semibold">Experience (Years)</label>
                        <input type="number" name="experience" id="experience" min="0" class="form-control @error('experience') is-invalid @enderror" value="{{ old('experience', 0) }}">
                        @error('experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="qualification" class="form-label fw-semibold">Qualification</label>
                        <input type="text" name="qualification" id="qualification" class="form-control @error('qualification') is-invalid @enderror" value="{{ old('qualification') }}">
                        @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="skills" class="form-label fw-semibold">Skills (Comma separated)</label>
                    <input type="text" name="skills" id="skills" class="form-control @error('skills') is-invalid @enderror" value="{{ old('skills') }}">
                    @error('skills')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Hiring Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Applied">Applied</option>
                        <option value="Shortlisted">Shortlisted</option>
                        <option value="HR Interview">HR Interview</option>
                        <option value="Technical Interview">Technical Interview</option>
                        <option value="Final Interview">Final Interview</option>
                        <option value="Offer Sent">Offer Sent</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Hired">Hired</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Candidate</button>
                    <a href="{{ route('candidates.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
