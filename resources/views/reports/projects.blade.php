@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4>Project Reports</h4>
        </div>
        <div class="card-body">

            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control"
                        placeholder="Project Name" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="client" class="form-select">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}"
                                {{ request('client') == $client->id ? 'selected' : '' }}>
                                {{ $client->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="manager" class="form-select">
                        <option value="">Project Manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}"
                                {{ request('manager') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->first_name }} {{ $manager->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-dark w-100">Filter</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('reports.projects') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>

            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Manager</th>
                        <th>Budget</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>{{ $project->project_code }}</td>
                            <td>{{ $project->project_name }}</td>
                            <td>{{ $project->client->company_name }}</td>
                            <td>{{ $project->manager->first_name }} {{ $project->manager->last_name }}</td>
                            <td>${{ number_format($project->budget, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $project->status == 'Completed' ? 'success' : ($project->status == 'Pending' ? 'warning' : 'primary') }}">
                                    {{ $project->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Projects Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $projects->links() }}

        </div>
    </div>
</div>
@endsection
