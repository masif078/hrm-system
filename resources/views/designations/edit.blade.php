@extends('layouts.app')

@section('title', 'Edit Designation')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4">Edit Designation</h2>

    <form action="{{ route('designations.update', $designation) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                <option value="">-- Select Department --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ $designation->department_id == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control"
                   value="{{ old('title', $designation->title) }}">
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $designation->description) }}</textarea>
        </div>

        <button class="btn btn-primary">Update Designation</button>
        <a href="{{ route('designations.index') }}" class="btn btn-secondary">Cancel</a>

    </form>

</div>

@endsection
