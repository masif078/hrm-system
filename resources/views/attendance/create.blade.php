@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Mark Attendance</h4>

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

<form action="{{ route('attendances.store') }}" method="POST">

@csrf

<div class="mb-3">

<label>Employee</label>

<select
name="employee_id"
class="form-control"
required>

@foreach($employees as $employee)

<option value="{{ $employee->id }}">

{{ $employee->first_name }}
{{ $employee->last_name }}

</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label>Date</label>

<input
type="date"
name="date"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Check In</label>

<input
type="time"
name="check_in"
class="form-control">

</div>

<div class="mb-3">

<label>Check Out</label>

<input
type="time"
name="check_out"
class="form-control">

</div>

<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option>Present</option>
<option>Absent</option>
<option>Late</option>
<option>Leave</option>

</select>

</div>

<button class="btn btn-success">

Save Attendance

</button>

<a href="{{ route('attendances.index') }}"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

@endsection
