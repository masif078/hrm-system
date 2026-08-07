@extends('layouts.app')

@section('title', 'Job Openings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Job Openings</h3>
            <p class="text-muted mb-0">Manage active and closed job recruitment postings.</p>
        </div>
        <a href="{{ route('job-openings.create') }}" class="btn btn-primary">Create Job Posting</a>
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
                            <th class="ps-4">Job Title</th>
                            <th>Department</th>
                            <th>Employment Type</th>
                            <th>Location</th>
                            <th>Vacancies</th>
                            <th>Closing Date</th>
                            <th>Status</th>
                            <th class="pe-4" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $job->title }}</span>
                                </td>
                                <td>{{ $job->department->name ?? 'N/A' }}</td>
                                <td>{{ $job->employment_type }}</td>
                                <td>{{ $job->location }}</td>
                                <td>{{ $job->vacancies }}</td>
                                <td>{{ $job->closing_date ? date('M d, Y', strtotime($job->closing_date)) : 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $job->status === 'Open' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $job->status }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('job-openings.edit', $job->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        <form action="{{ route('job-openings.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No job postings recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
