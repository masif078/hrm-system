@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="container-fluid">
    @if(isset($noProfile) && $noProfile)
        <div class="card border-0 shadow-sm mt-4 bg-white">
            <div class="card-body p-5 text-center">
                <i class="bi bi-exclamation-triangle text-warning display-1 mb-4"></i>
                <h3 class="fw-bold">No Client Profile Found</h3>
                <p class="text-muted mb-4">Your user account is not linked to any client profile record. Please contact the administrator to setup your profile details.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger px-4">Logout</button>
                </form>
            </div>
        </div>
    @else
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Welcome, {{ $client->contact_person }} 👋</h2>
                <p class="text-muted mb-0">Here is the execution overview for your corporate projects.</p>
            </div>
            <div class="text-secondary small fw-semibold bg-white px-3 py-2 rounded shadow-sm">
                <i class="bi bi-calendar3 me-2"></i> {{ today()->format('F d, Y') }}
            </div>
        </div>

        {{-- Stats Cards Row --}}
        <div class="row g-4 mb-4">
            {{-- Card 1: Total Projects --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px;">
                            <i class="bi bi-folder2-open fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block small mb-1">Total Projects</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $totalProjectsCount }}</h3>
                            <small class="text-secondary small">Corporate contracts</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Active Projects --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px;">
                            <i class="bi bi-activity fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block small mb-1">Active Projects</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $activeProjectsCount }}</h3>
                            <small class="text-success small fw-semibold"><i class="bi bi-play-fill"></i> In execution</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Pending Tasks --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="rounded-3 bg-warning-subtle text-warning d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px;">
                            <i class="bi bi-hourglass-split fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block small mb-1">Pending Tasks</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $pendingTasksCount }}</h3>
                            <small class="text-secondary small">Awaiting completion</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Completed Tasks --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="rounded-3 bg-info-subtle text-info d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px;">
                            <i class="bi bi-check2-circle fs-3"></i>
                        </div>
                        <div>
                            <span class="text-muted d-block small mb-1">Completed Tasks</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $completedTasksCount }}</h3>
                            <small class="text-secondary small">Out of {{ $totalTasksCount }} total tasks</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- Client Projects Table --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-white">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-code text-primary me-2"></i>My Projects Status</h5>
                        <a href="{{ route('client.projects.index') }}" class="text-decoration-none text-primary small fw-semibold">View All Projects</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Code</th>
                                        <th>Project Name</th>
                                        <th>Project Manager</th>
                                        <th>Timeline</th>
                                        <th>Status</th>
                                        <th class="pe-4">Budget</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($projects as $project)
                                        <tr>
                                            <td class="ps-4 text-secondary small fw-bold">#{{ $project->project_code }}</td>
                                            <td>
                                                <span class="fw-semibold text-dark">{{ $project->project_name }}</span>
                                            </td>
                                            <td>
                                                @if($project->manager)
                                                    <span class="fw-semibold text-dark">{{ $project->manager->first_name }} {{ $project->manager->last_name }}</span>
                                                    <small class="text-muted d-block">Phone: {{ $project->manager->phone ?? 'N/A' }}</small>
                                                @else
                                                    <span class="text-muted small">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $project->start_date }} to {{ $project->end_date }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($project->status === 'Completed')
                                                    <span class="badge bg-success text-white">Completed</span>
                                                @elseif($project->status === 'In Progress')
                                                    <span class="badge bg-info text-white">In Progress</span>
                                                @elseif($project->status === 'On Hold')
                                                    <span class="badge bg-warning text-dark">On Hold</span>
                                                @else
                                                    <span class="badge bg-secondary text-white">Pending</span>
                                                @endif
                                            </td>
                                            <td class="pe-4 fw-bold text-dark">
                                                ${{ number_format($project->budget, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No projects found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tasks Row --}}
        <div class="row g-4">
            {{-- Project Tasks --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-white">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-check text-primary me-2"></i>Recent Project Tasks</h5>
                        <a href="{{ route('client.tasks.index') }}" class="text-decoration-none text-primary small fw-semibold">View All Tasks</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Task</th>
                                        <th>Project</th>
                                        <th>Assigned Developer</th>
                                        <th>Priority</th>
                                        <th>Due Date</th>
                                        <th class="pe-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTasks as $task)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-semibold text-dark">{{ $task->title }}</span>
                                            </td>
                                            <td>
                                                <span class="text-secondary small fw-semibold">{{ $task->project->project_name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                {{ $task->employee->first_name ?? '' }} {{ $task->employee->last_name ?? '' }}
                                            </td>
                                            <td>
                                                @if($task->priority === 'High')
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">High</span>
                                                @elseif($task->priority === 'Medium')
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Medium</span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Low</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</small>
                                            </td>
                                            <td class="pe-4">
                                                @if($task->status === 'Completed')
                                                    <span class="badge bg-success text-white">Completed</span>
                                                @elseif($task->status === 'In Progress')
                                                    <span class="badge bg-info text-white">In Progress</span>
                                                @else
                                                    <span class="badge bg-secondary text-white">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No tasks available for your projects.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection