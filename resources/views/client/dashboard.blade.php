@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="container-fluid px-0">
    @if(isset($noProfile) && $noProfile)
        <div class="card border-0 shadow-sm mt-4 bg-white rounded-4">
            <div class="card-body p-5 text-center">
                <i class="bi bi-exclamation-triangle text-warning display-1 mb-4"></i>
                <h3 class="fw-bold">No Client Profile Found</h3>
                <p class="text-muted mb-4">Your user account is not linked to any client profile record. Please contact the administrator to setup your profile details.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger px-4 rounded-3">Logout</button>
                </form>
            </div>
        </div>
    @else
        {{-- Header Banner Card --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Welcome back, {{ $client->contact_person }} 👋</h3>
                    <p class="text-secondary small mb-0">Here is the execution overview and real-time status for your corporate projects.</p>
                </div>
                <div class="text-secondary small fw-semibold bg-light px-3.5 py-2 rounded-3 border border-light-subtle">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> {{ today()->format('F d, Y') }}
                </div>
            </div>
        </div>

        {{-- Stats Cards Row --}}
        <div class="row g-4 mb-4">
            {{-- Card 1: Total Projects --}}
            <div class="col-xl-3 col-md-6">
                <x-stat-card 
                    title="Total Projects" 
                    :value="$totalProjectsCount" 
                    color="blue" 
                    icon="bi-folder2-open" 
                    trend="Corporate" 
                    trendType="neutral" 
                    :link="route('client.projects.index')" 
                />
            </div>

            {{-- Card 2: Active Projects --}}
            <div class="col-xl-3 col-md-6">
                <x-stat-card 
                    title="Active Projects" 
                    :value="$activeProjectsCount" 
                    color="green" 
                    icon="bi-activity" 
                    trend="In execution" 
                    trendType="up" 
                    :link="route('client.projects.index')" 
                />
            </div>

            {{-- Card 3: Pending Tasks --}}
            <div class="col-xl-3 col-md-6">
                <x-stat-card 
                    title="Pending Tasks" 
                    :value="$pendingTasksCount" 
                    color="amber" 
                    icon="bi-hourglass-split" 
                    trend="Awaiting" 
                    trendType="down" 
                    :link="route('client.tasks.index')" 
                />
            </div>

            {{-- Card 4: Completed Tasks --}}
            <div class="col-xl-3 col-md-6">
                <x-stat-card 
                    title="Completed Tasks" 
                    :value="$completedTasksCount" 
                    color="purple" 
                    icon="bi-check2-circle" 
                    trend="Finished" 
                    trendType="up" 
                    :link="route('client.tasks.index')" 
                />
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- Client Projects Table --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-code text-primary me-2"></i>My Projects Status</h5>
                        <a href="{{ route('client.projects.index') }}" class="text-decoration-none text-primary small fw-semibold">View All Projects &rarr;</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-dark" style="background-color: #0F172A;">
                                <tr>
                                    <th class="ps-4 py-3">Code</th>
                                    <th class="py-3">Project Name</th>
                                    <th class="py-3">Project Manager</th>
                                    <th class="py-3">Timeline</th>
                                    <th class="py-3">Status</th>
                                    <th class="pe-4 py-3">Budget</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                    <tr class="hover-row">
                                        <td class="ps-4 text-secondary small fw-bold">#{{ $project->project_code }}</td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $project->project_name }}</span>
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
                                            <small class="text-secondary">
                                                {{ $project->start_date }} to {{ $project->end_date }}
                                            </small>
                                        </td>
                                        <td>
                                            <x-status-badge :status="$project->status" />
                                        </td>
                                        <td class="pe-4 fw-bold text-dark">
                                            PKR {{ number_format($project->budget, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-0">
                                            <x-empty-state title="No Projects Found" icon="bi-kanban" />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tasks Row --}}
        <div class="row g-4">
            {{-- Project Tasks --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-check text-primary me-2"></i>Recent Project Tasks</h5>
                        <a href="{{ route('client.tasks.index') }}" class="text-decoration-none text-primary small fw-semibold">View All Tasks &rarr;</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-dark" style="background-color: #0F172A;">
                                <tr>
                                    <th class="ps-4 py-3">Task</th>
                                    <th class="py-3">Project</th>
                                    <th class="py-3">Assigned Developer</th>
                                    <th class="py-3">Priority</th>
                                    <th class="py-3">Due Date</th>
                                    <th class="pe-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTasks as $task)
                                    <tr class="hover-row">
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark">{{ $task->title }}</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary small fw-semibold">{{ $task->project->project_name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            {{ $task->employee->first_name ?? '' }} {{ $task->employee->last_name ?? '' }}
                                        </td>
                                        <td>
                                            <x-status-badge :status="$task->priority" />
                                        </td>
                                        <td>
                                            <small class="text-secondary">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</small>
                                        </td>
                                        <td class="pe-4">
                                            <x-status-badge :status="$task->status" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-0">
                                            <x-empty-state title="No Tasks Found" icon="bi-check2-square" />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection