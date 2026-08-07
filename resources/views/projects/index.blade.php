@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between">

                <div>

                    <h3>Projects</h3>

                    <p class="text-muted">

                        Manage Company Projects

                    </p>

                </div>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('projects.create') }}" class="btn btn-success">
                    + Add Project
                </a>
                @endif

            </div>

        </div>

    </div>

    <div class="card shadow">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-10">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Search Project">

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-primary w-100">

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <br>

    <table class="table table-hover align-middle">

        <thead class="table-dark">

        <tr>

            <th>Code</th>

            <th>Project</th>

            <th>Client</th>

            <th>Manager</th>

            <th>Budget</th>

            <th>Status</th>

            <th width="170">

                Action

            </th>

        </tr>

        </thead>

        <tbody>

        @forelse($projects as $project)

        <tr>

            <td>

                {{ $project->project_code }}

            </td>

            <td>

                <strong>

                    {{ $project->project_name }}

                </strong>

            </td>

            <td>

                {{ $project->client?->company_name }}

            </td>

            <td>

                {{ $project->manager?->first_name }}
                {{ $project->manager?->last_name }}

            </td>

            <td>

                {{ number_format($project->budget,2) }}

            </td>

            <td>

                @if($project->status=='Completed')

                    <span class="badge bg-success">

                        Completed

                    </span>

                @elseif($project->status=='In Progress')

                    <span class="badge bg-primary">

                        In Progress

                    </span>

                @elseif($project->status=='Pending')

                    <span class="badge bg-warning">

                        Pending

                    </span>

                @else

                    <span class="badge bg-danger">

                        On Hold

                    </span>

                @endif

            </td>

            <td>

                <a href="{{ route('projects.show', $project) }}"
                   class="btn btn-info btn-sm text-white">View</a>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('projects.edit',$project) }}"
                   class="btn btn-warning btn-sm">Edit</a>

                <form
                    action="{{ route('projects.destroy',$project) }}"
                    method="POST"
                    class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Delete Project?')">
                        Delete
                    </button>
                </form>
                @endif

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="7"
                class="text-center">

                No Projects Found.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

    {{ $projects->links() }}

</div>

@endsection