@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Edit Task</h4>

</div>

<div class="card-body">

@if($errors->any())

<div class="alert alert-danger">

<ul>

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<form action="{{ route('tasks.update',$task) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">

<label>Project</label>

<select name="project_id" class="form-control" required>

@foreach($projects as $project)

<option value="{{ $project->id }}"
    {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>

{{ $project->project_name }}

</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label>Employee</label>

<select name="employee_id" class="form-control">

<option value="">Leave Unassigned</option>

@foreach($employees as $employee)

<option value="{{ $employee->id }}"
    {{ old('employee_id', $task->employee_id) == $employee->id ? 'selected' : '' }}>

{{ $employee->first_name }}
{{ $employee->last_name }}

</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label>Task Title</label>

<input
type="text"
name="title"
class="form-control"
value="{{ old('title', $task->title) }}"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control">{{ old('description', $task->description) }}</textarea>

</div>

<div class="mb-3">

<label>Due Date</label>

<input
type="date"
name="due_date"
class="form-control"
value="{{ old('due_date', $task->due_date) }}"
required>

</div>

<div class="mb-3">

<label>Priority</label>

<select
name="priority"
class="form-control">

@foreach(['Low', 'Medium', 'High'] as $priority)
<option {{ old('priority', $task->priority) == $priority ? 'selected' : '' }}>
    {{ $priority }}
</option>
@endforeach

</select>

</div>

<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

@foreach(['To Do', 'In Progress', 'Doing', 'Completed'] as $status)
<option {{ old('status', $task->status) == $status ? 'selected' : '' }}>
    {{ $status }}
</option>
@endforeach

</select>

</div>

<button class="btn btn-success">

Update Task

</button>

<a href="{{ route('tasks.index') }}"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

@endsection
