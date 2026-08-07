@extends('layouts.app')

@section('content')

<div class="container">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Attendance Details</h4>

</div>

<div class="card-body">

<p><strong>Employee:</strong>
{{ $attendance->employee->first_name }}
{{ $attendance->employee->last_name }}
</p>

<p><strong>Date:</strong>
{{ $attendance->date }}
</p>

<p><strong>Check In:</strong>
{{ $attendance->formatted_check_in }}
</p>

<p><strong>Check Out:</strong>
{{ $attendance->formatted_check_out }}
</p>

<p><strong>Status:</strong>
{{ $attendance->status }}
</p>

<a href="{{ route('attendances.index') }}"
class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

@endsection
