@extends('layouts.app')

@section('title', 'Departments')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm mb-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <div>

                <h2 class="mb-0">
                    Departments
                </h2>

                <small class="text-muted">
                    Manage all company departments
                </small>

            </div>

            <a href="{{ route('departments.create') }}"
                class="btn btn-primary">

                + Add Department

            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" action="{{ route('departments.index') }}" class="row g-3 mb-4">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control" placeholder="Search by name or code..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-50">Search</button>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary w-50">Reset</a>
        </div>
    </form>

    <table class="table table-hover align-middle">

        <thead class="table-dark">

            <tr>

                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Employees</th>
                <th>Description</th>
                <th width="180">Action</th>

            </tr>

        </thead>

        <tbody>

        @forelse($departments as $department)

            <tr>

                <td>{{ $department->id }}</td>

                <td>{{ $department->name }}</td>

                <td><span class="badge bg-primary">

                    {{ $department->code }}

                </span></td>

                <td><span class="badge bg-success">

                    {{ $department->employees_count }}

                </span></td>

                <td>{{ $department->description ?? 'No Description' }}</td>

                <td>

                    <a href="{{ route('departments.edit', $department) }}"
                       class="btn btn-outline-warning btn-sm">

                        Edit

                    </a>

                    <form action="{{ route('departments.destroy', $department) }}"
                          method="POST"
                          class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Delete this department?')">

                            Delete

                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5" class="text-center">

                    No departments available.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection
