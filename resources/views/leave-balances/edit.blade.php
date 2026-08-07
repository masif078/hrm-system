@extends('layouts.app')

@section('title', 'Edit Leave Allocation')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit Leave Allocation</h3>
                    <p class="text-muted mb-0">Modify allocated or used leaves for employee.</p>
                </div>
                <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('leave-balances.update', $balance->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $balance->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="leave_type" class="form-label">Leave Type</label>
                            <select name="leave_type" id="leave_type" class="form-select" required>
                                <option value="Casual" {{ old('leave_type', $balance->leave_type) === 'Casual' ? 'selected' : '' }}>Casual Leave</option>
                                <option value="Sick" {{ old('leave_type', $balance->leave_type) === 'Sick' ? 'selected' : '' }}>Sick Leave</option>
                                <option value="Annual" {{ old('leave_type', $balance->leave_type) === 'Annual' ? 'selected' : '' }}>Annual Leave</option>
                                <option value="Maternity" {{ old('leave_type', $balance->leave_type) === 'Maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                <option value="Paternity" {{ old('leave_type', $balance->leave_type) === 'Paternity' ? 'selected' : '' }}>Paternity Leave</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="allocated" class="form-label">Allocated Leaves (Number of Days)</label>
                            <input type="number" name="allocated" id="allocated" class="form-control" min="0" value="{{ old('allocated', $balance->allocated) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="used" class="form-label">Used Leaves</label>
                            <input type="number" name="used" id="used" class="form-control" min="0" value="{{ old('used', $balance->used) }}" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Allocation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
