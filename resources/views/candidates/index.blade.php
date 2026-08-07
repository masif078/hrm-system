@extends('layouts.app')

@section('title', 'Candidates')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Candidates</h3>
            <p class="text-muted mb-0">Track applicants, parsed resumes, and recruitment pipelines.</p>
        </div>
        <a href="{{ route('candidates.create') }}" class="btn btn-primary">Add Candidate</a>
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
                            <th class="ps-4">Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Experience</th>
                            <th>Qualification</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th class="pe-4" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($candidates as $candidate)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-dark">{{ $candidate->full_name }}</span>
                                        @if($candidate->resume)
                                            <small><a href="{{ asset('storage/' . $candidate->resume) }}" target="_blank" class="text-primary text-decoration-none">View Resume</a></small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $candidate->email }}</td>
                                <td>{{ $candidate->phone ?: 'N/A' }}</td>
                                <td>{{ $candidate->experience }} years</td>
                                <td>{{ $candidate->qualification ?: 'N/A' }}</td>
                                <td>{{ $candidate->source ?: 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border border-secondary-subtle">
                                        {{ $candidate->status }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this candidate record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No candidates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
