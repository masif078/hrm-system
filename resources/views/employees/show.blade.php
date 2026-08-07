@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow mb-4">

<div class="card-header bg-primary text-white">

<h4>Employee Profile</h4>

</div>

<div class="card-body">

<p><strong>Name:</strong>
{{ $employee->first_name }}
{{ $employee->last_name }}
</p>

<p><strong>Email:</strong>
{{ $employee->email }}
</p>

<p><strong>Phone:</strong>
{{ $employee->phone }}
</p>

<p><strong>Department:</strong>
{{ $employee->department->name }}
</p>

<p><strong>Designation:</strong>
{{ $employee->designation->title }}
</p>

</div>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h5>Total Leaves</h5>

<h2>{{ $employee->leaves->count() }}</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h5>Pending</h5>

<h2>{{ $employee->leaves->where('status','Pending')->count() }}</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h5>Approved</h5>

<h2>{{ $employee->leaves->where('status','Approved')->count() }}</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h5>Rejected</h5>

<h2>{{ $employee->leaves->where('status','Rejected')->count() }}</h2>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header">

Leave History

</div>

<div class="card-body">

<table class="table table-hover">

<thead>

<tr>

<th>Type</th>
<th>Start</th>
<th>End</th>
<th>Status</th>

</tr>

</thead>

<tbody>

@forelse($employee->leaves as $leave)

<tr>

<td>{{ $leave->leave_type }}</td>

<td>{{ $leave->start_date }}</td>

<td>{{ $leave->end_date }}</td>

<td>

<span class="badge bg-{{
$leave->status=='Approved'
?'success'
:($leave->status=='Rejected'
?'danger'
:'warning')
}}">

{{ $leave->status }}

</span>

</td>

</tr>

@empty

<tr>

<td colspan="4" class="text-center">

No Leave History

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</div>

@endsection
