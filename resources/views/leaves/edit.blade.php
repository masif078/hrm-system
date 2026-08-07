@extends('layouts.app')

@section('title', 'Edit Leave')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <h4 class="mb-0">Edit Leave</h4>
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

            <form action="{{ route('leaves.update', $leave) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Employee</label>

                    <select name="employee_id" class="form-select" required>

                        <option value="">Select Employee</option>

                        @foreach($employees as $employee)

                            <option value="{{ $employee->id }}"
                                {{ $leave->employee_id == $employee->id ? 'selected' : '' }}>

                                {{ $employee->first_name }}
                                {{ $employee->last_name }}

                            </option>

                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Leave Type</label>

                    <select name="leave_type" class="form-select" required>

                        <option value="Annual Leave"
                            {{ $leave->leave_type == 'Annual Leave' ? 'selected' : '' }}>
                            Annual Leave
                        </option>

                        <option value="Sick Leave"
                            {{ $leave->leave_type == 'Sick Leave' ? 'selected' : '' }}>
                            Sick Leave
                        </option>

                        <option value="Casual Leave"
                            {{ $leave->leave_type == 'Casual Leave' ? 'selected' : '' }}>
                            Casual Leave
                        </option>

                        <option value="Emergency Leave"
                            {{ $leave->leave_type == 'Emergency Leave' ? 'selected' : '' }}>
                            Emergency Leave
                        </option>

                    </select>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Start Date</label>

                        <input type="date"
                               name="start_date"
                               class="form-control"
                               value="{{ $leave->start_date }}"
                               required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">End Date</label>

                        <input type="date"
                               name="end_date"
                               class="form-control"
                               value="{{ $leave->end_date }}"
                               required>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">Reason</label>

                    <textarea name="reason"
                              class="form-control"
                              rows="4">{{ $leave->reason }}</textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select" required>

                        <option value="Pending"
                            {{ $leave->status == 'Pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="Approved"
                            {{ $leave->status == 'Approved' ? 'selected' : '' }}>
                            Approved
                        </option>

                        <option value="Rejected"
                            {{ $leave->status == 'Rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>

                    </select>

                </div>

                <button type="submit" class="btn btn-primary">
                    Update Leave
                </button>

                <a href="{{ route('leaves.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection
