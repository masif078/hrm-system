@extends('layouts.app')

@section('title', 'Edit Policy')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Edit Company Policy</h3>
        <p class="text-muted">Modify policy directives.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 800px;">
        <div class="card-body p-4">
            <form action="{{ route('company-policies.update', $companyPolicy->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label fw-semibold">Policy Title</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $companyPolicy->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="type" class="form-label fw-semibold">Policy Category</label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                            @foreach(['General', 'Leave Policy', 'Attendance Policy', 'Overtime Policy', 'Holiday Policy'] as $opt)
                                <option value="{{ $opt }}" {{ old('type', $companyPolicy->type) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="content" class="form-label fw-semibold">Policy Content</label>
                    <textarea name="content" id="content" rows="12" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $companyPolicy->content) }}</textarea>
                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Policy</button>
                    <a href="{{ route('company-policies.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
