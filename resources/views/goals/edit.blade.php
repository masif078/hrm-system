@extends('layouts.app')

@section('title', 'Edit Goal')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit Goal Progress</h3>
                    <p class="text-muted mb-0">Update target status and completion details.</p>
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
                    <form action="{{ route('goals.update', $goal->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if(auth()->user()->role === 'admin')
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee</label>
                                <select name="employee_id" id="employee_id" class="form-select" required>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $goal->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="title" class="form-label">Goal Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $goal->title) }}" {{ auth()->user()->role !== 'admin' ? 'readonly' : '' }} required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" {{ auth()->user()->role !== 'admin' ? 'readonly' : '' }}>{{ old('description', $goal->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="target_date" class="form-label">Target Completion Date</label>
                            <input type="date" name="target_date" id="target_date" class="form-control" value="{{ old('target_date', $goal->target_date) }}" {{ auth()->user()->role !== 'admin' ? 'readonly' : '' }} required>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="progress" class="form-label">Progress (%)</label>
                                <input type="number" name="progress" id="progress" class="form-control" min="0" max="100" value="{{ old('progress', $goal->progress) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Pending" {{ old('status', $goal->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ old('status', $goal->status) === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ old('status', $goal->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ old('status', $goal->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Goal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
