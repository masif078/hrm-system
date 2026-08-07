@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4>Leave Report</h4>
        </div>
        <div class="card-body">

            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <select name="employee" class="form-select">
                        <option value="">All Employees</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ request('employee') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="leave_type" class="form-control"
                        placeholder="Leave Type" value="{{ request('leave_type') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success w-100">Filter</button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('reports.leaves') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>

            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                            <td>{{ $leave->leave_type }}</td>
                            <td>{{ $leave->start_date }}</td>
                            <td>{{ $leave->end_date }}</td>
                            <td>
                                <span class="badge bg-{{ $leave->status == 'Approved' ? 'success' : ($leave->status == 'Rejected' ? 'danger' : 'warning') }}">
                                    {{ $leave->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Leave Records Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $leaves->links() }}

        </div>
    </div>
</div>
@endsection
