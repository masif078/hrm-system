@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Create Task</h4>

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

<form action="{{ route('tasks.store') }}" method="POST">

@csrf

<div class="mb-3">

<label>Project</label>

<select name="project_id" class="form-control" required>

@foreach($projects as $project)

<option value="{{ $project->id }}">

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

<option value="{{ $employee->id }}">

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
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control">
</textarea>

</div>

<div class="mb-3">

<label>Due Date</label>

<input
type="date"
name="due_date"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Priority</label>

<select
name="priority"
class="form-control">

<option>Low</option>
<option>Medium</option>
<option>High</option>

</select>

</div>

<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="To Do">To Do</option>
<option value="In Progress">In Progress</option>
<option value="Doing">Doing</option>
<option value="Completed">Completed</option>

</select>

</div>

<button class="btn btn-success">

Save Task

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
