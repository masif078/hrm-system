@extends('layouts.app')

@section('title', 'Job Applications')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Job Applications</h3>
            <p class="text-muted mb-0">Track candidates applying against active vacancies.</p>
        </div>
        <a href="{{ route('applications.create') }}" class="btn btn-primary">New Job Application</a>
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
                            <th class="ps-4">Candidate</th>
                            <th>Job Title</th>
                            <th>Department</th>
                            <th>Applied Date</th>
                            <th>Current Stage</th>
                            <th class="pe-4" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $app->candidate->full_name }}</span>
                                    <small class="text-muted d-block">{{ $app->candidate->email }}</small>
                                </td>
                                <td>{{ $app->jobOpening->title }}</td>
                                <td>{{ $app->jobOpening->department->name ?? 'N/A' }}</td>
                                <td>{{ $app->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border border-secondary-subtle">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('applications.show', $app->id) }}" class="btn btn-outline-primary btn-sm">Process Pipeline</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No job applications recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
