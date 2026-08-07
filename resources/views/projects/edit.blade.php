@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4>Edit Project</h4>

        </div>

        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('projects.update', $project) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Project Code</label>

                        <input
                            type="text"
                            name="project_code"
                            class="form-control"
                            value="{{ old('project_code', $project->project_code) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Project Name</label>

                        <input
                            type="text"
                            name="project_name"
                            class="form-control"
                            value="{{ old('project_name', $project->project_name) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Client</label>

                        <select
                            name="client_id"
                            class="form-select"
                            required>

                            <option value="">Select Client</option>

                            @foreach($clients as $client)

                                <option value="{{ $client->id }}"
                                    {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>

                                    {{ $client->company_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Project Manager</label>

                        <select
                            name="project_manager_id"
                            class="form-select"
                            required>

                            <option value="">Select Manager</option>

                            @foreach($employees as $employee)

                                <option value="{{ $employee->id }}"
                                    {{ old('project_manager_id', $project->project_manager_id) == $employee->id ? 'selected' : '' }}>

                                    {{ $employee->first_name }}
                                    {{ $employee->last_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Start Date</label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ old('start_date', $project->start_date) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>End Date</label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ old('end_date', $project->end_date) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Budget</label>

                        <input
                            type="number"
                            name="budget"
                            class="form-control"
                            value="{{ old('budget', $project->budget) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Status</label>

                        <select
                            name="status"
                            class="form-select">

                            @foreach(['Pending', 'In Progress', 'Completed', 'On Hold'] as $status)

                                <option value="{{ $status }}"
                                    {{ old('status', $project->status) == $status ? 'selected' : '' }}>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-12 mb-3">

                        <label>Description</label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control">{{ old('description', $project->description) }}</textarea>

                    </div>

                </div>

                <button class="btn btn-success">

                    Update Project

                </button>

                <a href="{{ route('projects.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

@endsection
