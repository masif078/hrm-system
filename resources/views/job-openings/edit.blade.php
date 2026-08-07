@extends('layouts.app')

@section('title', 'Edit Job Opening')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Edit Job Posting</h3>
        <p class="text-muted">Modify published job details.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 800px;">
        <div class="card-body p-4">
            <form action="{{ route('job-openings.update', $jobOpening->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Job Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $jobOpening->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="department_id" class="form-label fw-semibold">Department</label>
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $jobOpening->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="vacancies" class="form-label fw-semibold">Vacancies Count</label>
                        <input type="number" name="vacancies" id="vacancies" min="1" class="form-control @error('vacancies') is-invalid @enderror" value="{{ old('vacancies', $jobOpening->vacancies) }}" required>
                        @error('vacancies')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="employment_type" class="form-label fw-semibold">Employment Type</label>
                        <select name="employment_type" id="employment_type" class="form-select @error('employment_type') is-invalid @enderror" required>
                            <option value="Full Time" {{ old('employment_type', $jobOpening->employment_type) == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                            <option value="Part Time" {{ old('employment_type', $jobOpening->employment_type) == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                            <option value="Internship" {{ old('employment_type', $jobOpening->employment_type) == 'Internship' ? 'selected' : '' }}>Internship</option>
                            <option value="Contract" {{ old('employment_type', $jobOpening->employment_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                        </select>
                        @error('employment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="location" class="form-label fw-semibold">Location</label>
                        <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $jobOpening->location) }}" required>
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="salary_range" class="form-label fw-semibold">Salary Range</label>
                        <input type="text" name="salary_range" id="salary_range" class="form-control @error('salary_range') is-invalid @enderror" value="{{ old('salary_range', $jobOpening->salary_range) }}">
                        @error('salary_range')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="closing_date" class="form-label fw-semibold">Closing Date</label>
                        <input type="date" name="closing_date" id="closing_date" class="form-control @error('closing_date') is-invalid @enderror" value="{{ old('closing_date', $jobOpening->closing_date) }}">
                        @error('closing_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Job Description</label>
                    <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $jobOpening->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Open" {{ old('status', $jobOpening->status) == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Closed" {{ old('status', $jobOpening->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Job</button>
                    <a href="{{ route('job-openings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
