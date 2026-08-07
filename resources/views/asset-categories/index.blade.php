@extends('layouts.app')

@section('title', 'Asset Categories')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Asset Categories</h3>
            <p class="text-muted mb-0">Classify physical hardware, laptops, and credentials.</p>
        </div>
        <a href="{{ route('asset-categories.create') }}" class="btn btn-primary">Create Category</a>
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
                            <th class="ps-4">Category Name</th>
                            <th>Description</th>
                            <th>Created Date</th>
                            <th class="pe-4" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $cat->name }}</span>
                                </td>
                                <td>{{ $cat->description ?: 'No description' }}</td>
                                <td>{{ $cat->created_at->format('M d, Y') }}</td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('asset-categories.edit', $cat->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        <form action="{{ route('asset-categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No asset categories recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
