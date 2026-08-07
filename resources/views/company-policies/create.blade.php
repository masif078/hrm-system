@extends('layouts.app')

@section('title', 'Create Policy')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Create Company Policy</h3>
        <p class="text-muted">Draft a new rule or code of conduct policy for staff.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 800px;">
        <div class="card-body p-4">
            <form action="{{ route('company-policies.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label fw-semibold">Policy Title</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="type" class="form-label fw-semibold">Policy Category</label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="General" {{ old('type') == 'General' ? 'selected' : '' }}>General</option>
                            <option value="Leave Policy" {{ old('type') == 'Leave Policy' ? 'selected' : '' }}>Leave Policy</option>
                            <option value="Attendance Policy" {{ old('type') == 'Attendance Policy' ? 'selected' : '' }}>Attendance Policy</option>
                            <option value="Overtime Policy" {{ old('type') == 'Overtime Policy' ? 'selected' : '' }}>Overtime Policy</option>
                            <option value="Holiday Policy" {{ old('type') == 'Holiday Policy' ? 'selected' : '' }}>Holiday Policy</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="content" class="form-label fw-semibold">Policy Content / Description</label>
                    <textarea name="content" id="content" rows="12" class="form-control @error('content') is-invalid @enderror" placeholder="Describe the policy parameters, thresholds, and conditions..." required>{{ old('content') }}</textarea>
                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Publish Policy</button>
                    <a href="{{ route('company-policies.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
