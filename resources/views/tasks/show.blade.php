@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Task Details</h4>

</div>

<div class="card-body">

<p><strong>Project:</strong> {{ $task->project->project_name }}</p>

<p><strong>Employee:</strong>
{{ $task->employee->first_name }}
{{ $task->employee->last_name }}
</p>

<p><strong>Title:</strong> {{ $task->title }}</p>

<p><strong>Description:</strong>
{{ $task->description }}
</p>

<p><strong>Priority:</strong> {{ $task->priority }}</p>

<p><strong>Status:</strong> {{ $task->status }}</p>

<p><strong>Due Date:</strong> {{ $task->due_date }}</p>

@if(auth()->user()->role === 'employee' && auth()->user()->employee && $task->employee_id === auth()->user()->employee->id)
<hr>
<form action="{{ route('employee.tasks.update-status', $task) }}" method="POST" class="mt-3 mb-3" style="max-width: 300px;">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="status" class="form-label fw-bold">Update Status:</label>
        <select name="status" id="status" class="form-select mb-2">
            <option value="To Do" {{ $task->status === 'To Do' ? 'selected' : '' }}>To Do</option>
            <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
            <option value="Doing" {{ $task->status === 'Doing' ? 'selected' : '' }}>Doing</option>
            <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
    </div>
</form>
@endif

<a href="{{ route('tasks.index') }}"
class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

@endsection
