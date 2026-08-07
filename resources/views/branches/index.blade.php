@extends('layouts.app')

@section('title', 'Branch List')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Branches</h3>
            <p class="text-muted mb-0">Manage multiple office locations and branch manager profiles.</p>
        </div>
        <a href="{{ route('branches.create') }}" class="btn btn-primary">Add New Branch</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Branch Name</th>
                            <th>Location</th>
                            <th>Manager</th>
                            <th>Status</th>
                            <th class="pe-4" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $branch->name }}</span>
                                </td>
                                <td>{{ $branch->location }}</td>
                                <td>{{ $branch->manager ? ($branch->manager->first_name . ' ' . $branch->manager->last_name) : 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $branch->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $branch->status }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this office branch?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No branches logged.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
