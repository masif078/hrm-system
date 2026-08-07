@extends('layouts.app')

@section('title', 'Generate Offer Letter')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Generate Offer Letter</h3>
        <p class="text-muted">Create formal job offer details for candidate **{{ $application->candidate->full_name }}**.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="{{ route('offer-letters.store') }}" method="POST">
                @csrf
                <input type="hidden" name="application_id" value="{{ $application->id }}">

                <div class="mb-3">
                    <label for="salary_offered" class="form-label fw-semibold">Offered Base Salary (PKR / month)</label>
                    <input type="number" name="salary_offered" id="salary_offered" class="form-control @error('salary_offered') is-invalid @enderror" value="{{ old('salary_offered', 50000) }}" required>
                    @error('salary_offered')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="joining_date" class="form-label fw-semibold">Proposed Joining Date</label>
                    <input type="date" name="joining_date" id="joining_date" class="form-control @error('joining_date') is-invalid @enderror" value="{{ old('joining_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                    @error('joining_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Generate Offer</button>
                    <a href="{{ route('applications.show', $application->id) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
