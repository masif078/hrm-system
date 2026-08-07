@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4>Create Project</h4>

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

            <form action="{{ route('projects.store') }}" method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Project Code</label>

                        <input
                            type="text"
                            name="project_code"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Project Name</label>

                        <input
                            type="text"
                            name="project_name"
                            class="form-control"
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

                                <option value="{{ $client->id }}">

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

                                <option value="{{ $employee->id }}">

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
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>End Date</label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Budget</label>

                        <input
                            type="number"
                            name="budget"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Status</label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="Pending">Pending</option>

                            <option value="In Progress">In Progress</option>

                            <option value="Completed">Completed</option>

                            <option value="On Hold">On Hold</option>

                        </select>

                    </div>

                    <div class="col-md-12 mb-3">

                        <label>Description</label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"></textarea>

                    </div>

                </div>

                <button
                    class="btn btn-success">

                    Save Project

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
