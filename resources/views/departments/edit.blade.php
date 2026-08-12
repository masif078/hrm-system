@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Departments', 'url' => route('departments.index')],
        ['label' => 'Edit Department']
    ]" />

    {{-- Centered Compact Form Card (Max-Width 600px) --}}
    <div class="mx-auto" style="max-width: 600px;">
        <div class="card border border-light-subtle shadow-sm rounded-4 bg-white p-4 p-md-5">
            {{-- Header Title --}}
            <div class="border-bottom border-light-subtle pb-3 mb-4">
                <h4 class="fw-bold text-dark mb-1">Edit Department</h4>
                <p class="text-secondary small mb-0">Update department title, code, and operational description.</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('departments.update', $department) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Department Name --}}
                <div class="mb-3.5">
                    <label class="form-label small fw-semibold text-secondary mb-1">Department Name <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="name"
                        class="form-control rounded-3 border-light-subtle shadow-2xs @error('name') is-invalid @enderror"
                        placeholder="e.g. Human Resources"
                        value="{{ old('name', $department->name) }}"
                        required>
                    @error('name')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Department Code --}}
                <div class="mb-3.5">
                    <label class="form-label small fw-semibold text-secondary mb-1">Department Code <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="code"
                        class="form-control rounded-3 border-light-subtle shadow-2xs @error('code') is-invalid @enderror"
                        placeholder="e.g. HR-001"
                        value="{{ old('code', $department->code) }}"
                        required>
                    @error('code')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">Description</label>
                    <textarea
                        name="description"
                        class="form-control rounded-3 border-light-subtle shadow-2xs @error('description') is-invalid @enderror"
                        rows="4"
                        placeholder="Provide a short summary of department responsibilities...">{{ old('description', $department->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Bottom Right Actions --}}
                <div class="d-flex justify-content-end gap-2.5 pt-2 border-top border-light-subtle">
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary fw-semibold px-4 py-2 rounded-3">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm">
                        Update Department
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
