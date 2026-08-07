@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Edit Attendance</h4>

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

<form action="{{ route('attendances.update',$attendance) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">

<label>Employee</label>

<select
name="employee_id"
class="form-control"
required>

@foreach($employees as $employee)

<option value="{{ $employee->id }}"
    {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>

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
value="{{ old('date', $attendance->date) }}"
required>

</div>

<div class="mb-3">

<label>Check In</label>

<input
type="time"
name="check_in"
class="form-control"
value="{{ old('check_in', $attendance->check_in) }}">

</div>

<div class="mb-3">

<label>Check Out</label>

<input
type="time"
name="check_out"
class="form-control"
value="{{ old('check_out', $attendance->check_out) }}">

</div>

<div class="mb-3">

<label>Status</label>

<select
name="status"
class="form-control">

@foreach(['Present', 'Absent', 'Late', 'Leave'] as $status)
<option {{ old('status', $attendance->status) == $status ? 'selected' : '' }}>
    {{ $status }}
</option>
@endforeach

</select>

</div>

<button class="btn btn-success">

Update Attendance

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
