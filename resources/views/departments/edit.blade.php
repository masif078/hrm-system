@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="container mt-4">

    <h2>Edit Department</h2>

    <form action="/departments/{{ $department->id }}" method="POST" class="mt-3">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $department->name) }}">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Code</label>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                   value="{{ old('code', $department->code) }}">
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $department->description) }}</textarea>
        </div>

        <a href="/departments" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

</div>
@endsection
