@extends('layouts.app')

@section('title', 'Designations')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Designations</h2>
                <small class="text-muted">Manage all company designations</small>
            </div>
            <a href="{{ route('designations.create') }}" class="btn btn-primary">
                + Add Designation
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('designations.index') }}" class="row g-3 mb-4">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control" placeholder="Search by designation title..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-50">Search</button>
            <a href="{{ route('designations.index') }}" class="btn btn-secondary w-50">Reset</a>
        </div>
    </form>

    <table class="table table-hover align-middle">

        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Department</th>
                <th>Description</th>
                <th width="180">Action</th>
            </tr>
        </thead>

        <tbody>

        @forelse($designations as $designation)
            <tr>
                <td>{{ $designation->id }}</td>
                <td>{{ $designation->title }}</td>
                <td>
                    <span class="badge bg-primary">
                        {{ $designation->department->name ?? '-' }}
                    </span>
                </td>
                <td>{{ $designation->description ?? 'No Description' }}</td>
                <td>
                    <a href="{{ route('designations.edit', $designation) }}"
                       class="btn btn-outline-warning btn-sm">Edit</a>

                    <form action="{{ route('designations.destroy', $designation) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Delete this designation?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No designations available.</td>
            </tr>
        @endforelse

        </tbody>

    </table>

</div>

@endsection
