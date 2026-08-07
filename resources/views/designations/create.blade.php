@extends('layouts.app')

@section('title', 'Add Designation')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4">Add Designation</h2>

    <form action="{{ route('designations.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                <option value="">-- Select Department --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                   value="{{ old('title') }}">
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>

        <button class="btn btn-primary">Save Designation</button>
        <a href="{{ route('designations.index') }}" class="btn btn-secondary">Cancel</a>

    </form>

</div>

@endsection
