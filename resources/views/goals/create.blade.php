@extends('layouts.app')

@section('title', 'Add New Goal')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Add New Goal</h3>
                    <p class="text-muted mb-0">Define targets and progression parameters for tracking.</p>
                </div>
                <a href="{{ route('goals.index') }}" class="btn btn-secondary">
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
                    <form action="{{ route('goals.store') }}" method="POST">
                        @csrf
                        
                        @if(auth()->user()->role === 'admin')
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee</label>
                                <select name="employee_id" id="employee_id" class="form-select" required>
                                    <option value="" disabled selected>Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="title" class="form-label">Goal Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Redesign Login Page, Finish Accounting Report" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description / Criteria</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Define success criteria for this goal">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="target_date" class="form-label">Target Completion Date</label>
                            <input type="date" name="target_date" id="target_date" class="form-control" value="{{ old('target_date') }}" required>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="progress" class="form-label">Initial Progress (%)</label>
                                <input type="number" name="progress" id="progress" class="form-control" min="0" max="100" value="{{ old('progress', 0) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ old('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ old('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Goal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
