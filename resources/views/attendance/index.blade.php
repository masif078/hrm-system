@extends('layouts.app')

@section('content')

<div class="container">

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card shadow mb-4">

<div class="card-body d-flex justify-content-between align-items-center">

<div>

<h3>Attendance Management</h3>

<p class="text-muted mb-0">
Manage employee attendance
</p>

</div>

@if(auth()->user()->role === 'admin')
<a href="{{ route('attendances.create') }}" class="btn btn-primary">
+ Mark Attendance
</a>
@else
<div class="d-flex gap-2">
    @if(!$todayAttendance)
        <form method="POST" action="{{ route('employee.attendances.checkin') }}">
            @csrf
            <button class="btn btn-success">Check In</button>
        </form>
    @elseif(!$todayAttendance->check_out)
        <form method="POST" action="{{ route('employee.attendances.checkout') }}">
            @csrf
            <button class="btn btn-danger">Check Out</button>
        </form>
    @else
        <span class="btn btn-secondary disabled">Attendance Done for Today</span>
    @endif
</div>
@endif

</div>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h5>Total Records</h5>

<h2>{{ $attendances->total() }}</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h5>Present</h5>

<h2>

{{ \App\Models\Attendance::where('status','Present')->count() }}

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h5>Absent</h5>

<h2>

{{ \App\Models\Attendance::where('status','Absent')->count() }}

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h5>Late</h5>

<h2>

{{ \App\Models\Attendance::where('status','Late')->count() }}

</h2>

</div>

</div>

</div>

</div>

<div class="card shadow mb-4">

<div class="card-body">

<h5>Today's Attendance</h5>

<h2>

{{ \App\Models\Attendance::whereDate('date', today())->count() }}

</h2>

</div>

</div>

<form method="GET" class="row mb-4">

    <div class="col-md-4">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            class="form-control"
            placeholder="Search Employee">
    </div>

    <div class="col-md-3">
        <input
            type="date"
            name="date"
            value="{{ request('date') }}"
            class="form-control">
    </div>

    <div class="col-md-3">
        <select
            name="status"
            class="form-select">

            <option value="">All Status</option>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="Late">Late</option>
            <option value="Leave">Leave</option>

        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary w-100">
            Filter
        </button>
    </div>

</form>

<a href="{{ route('attendances.index') }}"
   class="btn btn-secondary mb-4">

    Reset Filters

</a>

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Employee</th>
<th>Date</th>
<th>Check In</th>
<th>Check Out</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

@forelse($attendances as $attendance)

<tr>

<td>{{ $attendance->id }}</td>

<td>
{{ $attendance->employee->first_name }}
{{ $attendance->employee->last_name }}
</td>

<td>{{ $attendance->date }}</td>

<td>{{ $attendance->formatted_check_in }}</td>

<td>{{ $attendance->formatted_check_out }}</td>

<td>

<span class="badge bg-{{
$attendance->status=='Present'
?'success'
:($attendance->status=='Late'
?'warning'
:($attendance->status=='Leave'
?'info'
:'danger'))
}}">

{{ $attendance->status }}

</span>

</td>

<td>

@if(auth()->user()->role === 'admin')
<a href="{{ route('attendances.show',$attendance) }}"
class="btn btn-info btn-sm">View</a>

<a href="{{ route('attendances.edit',$attendance) }}"
class="btn btn-warning btn-sm">Edit</a>

<form
action="{{ route('attendances.destroy',$attendance) }}"
method="POST"
class="d-inline">
@csrf
@method('DELETE')
<button
class="btn btn-danger btn-sm"
onclick="return confirm('Delete Attendance?')">
Delete
</button>
</form>
@else
<span class="badge bg-secondary">{{ $attendance->status }}</span>
@endif

</td>

</tr>

@empty

<tr>

<td colspan="7" class="text-center">

No Attendance Found.

</td>

</tr>

@endforelse

</tbody>

</table>

{{ $attendances->links() }}

</div>

@endsection
