@extends('layouts.app')

@section('title', 'Salary Structures')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Salary Structures</h3>
                    <p class="text-muted mb-0">Manage salary structures for active employees.</p>
                </div>
                <a href="{{ route('salary-structures.create') }}" class="btn btn-success">
                    + Define Salary Structure
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
                    <div class="col-md-9">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Employee Name...">
                    </div>
                    <div class="col-md-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-primary w-100">Search</button>
                        @if(request('search'))
                            <a href="{{ route('salary-structures.index') }}" class="btn btn-secondary w-100">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Department / Designation</th>
                        <th>Basic Salary</th>
                        <th>Net Salary</th>
                        <th>Effective From</th>
                        <th>Status</th>
                        <th width="220" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaryStructures as $structure)
                        <tr>
                            <td>
                                <strong>{{ $structure->employee->first_name }} {{ $structure->employee->last_name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $structure->employee->department?->name }}</span>
                                <span class="badge bg-info text-dark">{{ $structure->employee->designation?->title }}</span>
                            </td>
                            <td>{{ number_format($structure->basic_salary, 2) }}</td>
                            <td><strong>{{ number_format($structure->net_salary, 2) }}</strong></td>
                            <td>{{ $structure->effective_from }}</td>
                            <td>
                                @if($structure->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('salary-structures.show', $structure->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('salary-structures.edit', $structure->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('salary-structures.destroy', $structure->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this structure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No salary structures defined.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $salaryStructures->appends(request()->input())->links() }}
    </div>
</div>
@endsection
