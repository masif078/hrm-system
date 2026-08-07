@extends('layouts.app')

@section('title','Employees')

@section('content')

<div class="container">

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h3 class="mb-1">

                        Employee Management

                    </h3>

                    <p class="text-muted mb-0">

                        Manage all company employees.

                    </p>

                </div>

                <a href="{{ route('employees.create') }}"
                   class="btn btn-success">

                    + Add Employee

                </a>

            </div>

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-4">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search Employee">

                    </div>

                    <div class="col-md-3">

                        <select
                            name="department"
                            class="form-select">

                            <option value="">All Departments</option>

                            @foreach($departments as $department)

                                <option
                                    value="{{ $department->id }}"
                                    {{ request('department') == $department->id ? 'selected' : '' }}>

                                    {{ $department->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">All Status</option>

                            <option value="Active"
                                {{ request('status') == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ request('status') == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2 d-grid">

                        <button class="btn btn-primary">

                            Filter

                        </button>

                        <a href="{{ route('employees.index') }}"
                           class="btn btn-secondary mt-2">

                            Reset Filters

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <table class="table table-bordered">

        <thead class="table-dark">

        <tr>

            <th>ID</th>

            <th>Employee</th>

            <th>Email</th>

            <th>Department</th>

            <th>Designation</th>

            <th>Status</th>

            <th width="170">Action</th>

        </tr>

        </thead>

        <tbody>

        @forelse($employees as $employee)

        <tr>

            <td>{{ $employee->id }}</td>

            <td>

                <strong>

                    {{ $employee->first_name }}
                    {{ $employee->last_name }}

                </strong>

            </td>

            <td>{{ $employee->email }}</td>

            <td>

                <span class="badge bg-primary">

                    {{ $employee->department?->name }}

                </span>

            </td>

            <td>

                <span class="badge bg-info">

                    {{ $employee->designation?->title }}

                </span>

            </td>

            <td>

            @if($employee->status=='Active')

                <span class="badge bg-success">

                    Active

                </span>

            @else

                <span class="badge bg-danger">

                    Inactive

                </span>

            @endif

            </td>

            <td>

            <a href="{{ route('employees.show', $employee) }}"
               class="btn btn-info btn-sm">

                View

            </a>

            <a href="{{ route('employees.edit',$employee) }}"
            class="btn btn-warning btn-sm">

            Edit

            </a>

            <form
            action="{{ route('employees.destroy',$employee) }}"
            method="POST"
            class="d-inline">

            @csrf

            @method('DELETE')

            <button
            class="btn btn-danger btn-sm"
            onclick="return confirm('Delete this employee?')">

            Delete

            </button>

            </form>

            </td>

        </tr>

        @empty

        <tr>

        <td colspan="7" class="text-center">

        No employees found.

        </td>

        </tr>

        @endforelse

        </tbody>

    </table>

    <div class="mt-4">

        {{ $employees->links() }}

    </div>

</div>

@endsection
