@extends('layouts.app')

@section('title', 'Leaves')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Leave Management</h2>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('leaves.create') }}" class="btn btn-primary">
            Add Leave
        </a>
        @else
        <a href="{{ route('employee.leaves.create') }}" class="btn btn-primary">
            Apply Leave
        </a>
        @endif

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pending Leave Alert --}}
    @if($pendingLeaves > 0)

        <div class="alert alert-warning">

            <strong>{{ $pendingLeaves }}</strong>
            pending leave request(s) need approval.

        </div>

    @endif

    {{-- Dashboard Cards --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Total Leaves</h5>
                    <h2>{{ $totalLeaves }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Pending</h5>
                    <h2>{{ $pendingLeaves }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Approved</h5>
                    <h2>{{ $approvedLeaves }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Rejected</h5>
                    <h2>{{ $rejectedLeaves }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">

            <div class="card shadow">

                <div class="card-body">

                    <h5>This Month</h5>

                    <h2>{{ $monthlyLeaves }}</h2>

                </div>

            </div>

        </div>

    </div>

    {{-- Recent Leave Requests --}}
    <div class="card shadow mt-4">

        <div class="card-header">

            Recent Leave Requests

        </div>

        <div class="card-body">

            <table class="table table-hover">

                <thead>

                <tr>

                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Status</th>

                </tr>

                </thead>

                <tbody>

                @foreach($recentLeaves as $leave)

                    <tr>

                        <td>

                            {{ $leave->employee->first_name }}
                            {{ $leave->employee->last_name }}

                        </td>

                        <td>{{ $leave->leave_type }}</td>

                        <td>

                            <span class="badge bg-{{
                            $leave->status == 'Approved'
                            ? 'success'
                            : ($leave->status == 'Rejected'
                                ? 'danger'
                                : 'warning')
                            }}">

                                {{ $leave->status }}

                            </span>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

    {{-- Search and Filters --}}
    <form method="GET" class="row mb-3 mt-4">

        <div class="col-md-3">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search Employee">
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">

                <option value="">All Status</option>

                <option value="Pending"
                    {{ request('status') == 'Pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="Approved"
                    {{ request('status') == 'Approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option value="Rejected"
                    {{ request('status') == 'Rejected' ? 'selected' : '' }}>
                    Rejected
                </option>

            </select>
        </div>

        <div class="col-md-3">

            <select name="leave_type" class="form-select">

                <option value="">All Leave Types</option>

                <option value="Annual Leave"
                    {{ request('leave_type') == 'Annual Leave' ? 'selected' : '' }}>
                    Annual Leave
                </option>

                <option value="Sick Leave"
                    {{ request('leave_type') == 'Sick Leave' ? 'selected' : '' }}>
                    Sick Leave
                </option>

                <option value="Casual Leave"
                    {{ request('leave_type') == 'Casual Leave' ? 'selected' : '' }}>
                    Casual Leave
                </option>

                <option value="Emergency Leave"
                    {{ request('leave_type') == 'Emergency Leave' ? 'selected' : '' }}>
                    Emergency Leave
                </option>

            </select>

        </div>

        <div class="col-md-2">

            <button class="btn btn-primary w-100">
                Filter
            </button>

        </div>

    </form>

    <a href="{{ route('leaves.index') }}" class="btn btn-secondary mb-4">
        Reset Filters
    </a>

    {{-- Leave Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th width="220">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($leaves as $leave)

                        <tr>

                            <td>{{ $leave->id }}</td>

                            <td>
                                {{ $leave->employee->first_name ?? '' }}
                                {{ $leave->employee->last_name ?? '' }}
                            </td>

                            <td>{{ $leave->leave_type }}</td>

                            <td>{{ $leave->start_date }}</td>

                            <td>{{ $leave->end_date }}</td>

                            <td>

                                <span class="badge bg-{{
                                $leave->status == 'Approved'
                                ? 'success'
                                : ($leave->status == 'Rejected'
                                    ? 'danger'
                                    : 'warning')
                                }}">

                                    {{ $leave->status }}

                                </span>

                            </td>

                            <td>

                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('leaves.show', $leave) }}"
                                   class="btn btn-info btn-sm">
                                    View
                                </a>

                                <a href="{{ route('leaves.edit', $leave) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('leaves.destroy', $leave) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this leave?')">
                                        Delete
                                    </button>

                                </form>

                                @if($leave->status == 'Pending')

                                    <form action="{{ route('leaves.approve', $leave) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-success btn-sm">
                                            Approve
                                        </button>

                                    </form>

                                    <form action="{{ route('leaves.reject', $leave) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-danger btn-sm">
                                            Reject
                                        </button>

                                    </form>

                                @endif
                                @else
                                    <span class="badge bg-secondary">{{ $leave->status }}</span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center">
                                No leave records found.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $leaves->links() }}
            </div>

        </div>

    </div>

</div>

@endsection