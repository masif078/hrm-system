@extends('layouts.app')

@section('title', 'Edit Branch')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Edit Branch Office</h3>
        <p class="text-muted">Modify office location details and managers.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Branch Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $branch->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label fw-semibold">Location / Address</label>
                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $branch->location) }}" required>
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="manager_id" class="form-label fw-semibold">Assign Branch Manager</label>
                    <select name="manager_id" id="manager_id" class="form-select @error('manager_id') is-invalid @enderror">
                        <option value="">Select Manager</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('manager_id', $branch->manager_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Branch Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Active" {{ old('status', $branch->status) === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $branch->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Branch</button>
                    <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
