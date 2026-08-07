@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Project Details: {{ $project->project_name }}</h4>
            <a href="{{ route('projects.index') }}" class="btn btn-light btn-sm text-primary">Back to Projects</a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Project Code:</div>
                <div class="col-md-9">{{ $project->project_code }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Client:</div>
                <div class="col-md-9">{{ $project->client?->company_name ?? 'N/A' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Project Manager:</div>
                <div class="col-md-9">
                    {{ $project->manager?->first_name }} {{ $project->manager?->last_name }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Budget:</div>
                <div class="col-md-9">PKR {{ number_format($project->budget, 2) }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Status:</div>
                <div class="col-md-9">
                    <span class="badge bg-{{ $project->status === 'Completed' ? 'success' : ($project->status === 'In Progress' ? 'primary' : 'warning') }}">
                        {{ $project->status }}
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Timeline:</div>
                <div class="col-md-9">{{ $project->start_date }} to {{ $project->end_date ?? 'Indefinite' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Description:</div>
                <div class="col-md-9">{{ $project->description ?? 'No Description' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Tasks in this Project</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Title</th>
                            <th>Assigned Employee</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="pe-4">Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->tasks as $task)
                            <tr>
                                <td class="ps-4">{{ $task->id }}</td>
                                <td><strong>{{ $task->title }}</strong></td>
                                <td>
                                    {{ $task->employee?->first_name }} {{ $task->employee?->last_name }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $task->priority === 'High' ? 'danger' : ($task->priority === 'Medium' ? 'warning' : 'secondary') }}">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $task->status === 'Completed' ? 'success' : ($task->status === 'To Do' ? 'secondary' : ($task->status === 'Doing' ? 'info' : 'primary')) }}">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td class="pe-4">{{ $task->due_date }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No tasks assigned to this project yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
