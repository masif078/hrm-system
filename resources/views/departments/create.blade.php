@extends('layouts.app')

@section('title', 'Add Department')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4">Add Department</h2>

    <form action="{{ route('departments.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label>Department Name</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name') }}">

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Department Code</label>

            <input
                type="text"
                name="code"
                class="form-control"
                value="{{ old('code') }}">

            @error('code')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label>Description</label>

            <textarea
                name="description"
                class="form-control"
                rows="4">{{ old('description') }}</textarea>
        </div>

        <button class="btn btn-primary">
            Save Department
        </button>

        <a href="{{ route('departments.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection
