@extends('layouts.app')

@section('title', 'Add Leave')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <h4 class="mb-0">Add Leave</h4>
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

            <form action="{{ auth()->user()->role === 'employee' ? route('employee.leaves.store') : route('leaves.store') }}" method="POST">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Employee</label>

                    @if(auth()->user()->role === 'employee')
                        @if(auth()->user()->employee)
                            <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                            <input type="text" class="form-control" value="{{ auth()->user()->employee->first_name }} {{ auth()->user()->employee->last_name }}" disabled>
                        @else
                            <div class="alert alert-warning mb-0">No employee profile linked to your user account. Please contact admin to link your profile.</div>
                        @endif
                    @else
                    <select name="employee_id" class="form-select" required>

                        <option value="">Select Employee</option>

                        @foreach($employees as $employee)

                            <option value="{{ $employee->id }}"
                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>

                                {{ $employee->first_name }}
                                {{ $employee->last_name }}

                            </option>

                        @endforeach

                    </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Leave Type</label>

                    <select name="leave_type" class="form-select" required>

                        <option value="">Select Leave Type</option>
                        <option value="Annual Leave">Annual Leave</option>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Casual Leave">Casual Leave</option>
                        <option value="Emergency Leave">Emergency Leave</option>

                    </select>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Start Date</label>

                        <input type="date"
                               name="start_date"
                               class="form-control"
                               value="{{ old('start_date') }}"
                               required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">End Date</label>

                        <input type="date"
                               name="end_date"
                               class="form-control"
                               value="{{ old('end_date') }}"
                               required>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">Reason</label>

                    <textarea name="reason"
                              class="form-control"
                              rows="4"
                              placeholder="Enter reason for leave">{{ old('reason') }}</textarea>

                </div>

                @if(auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                @else
                    <input type="hidden" name="status" value="Pending">
                @endif

                <button type="submit" class="btn btn-success">
                    {{ auth()->user()->role === 'employee' ? 'Apply Leave' : 'Save Leave' }}
                </button>

                <a href="{{ auth()->user()->role === 'employee' ? route('employee.leaves.index') : route('leaves.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@endsection
