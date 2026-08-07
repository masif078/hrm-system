@extends('layouts.app')

@section('title', 'Shift Management')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Shift Management</h3>
                    <p class="text-muted mb-0">Define and manage working hours and grace thresholds for employees.</p>
                </div>
                <a href="{{ route('shifts.create') }}" class="btn btn-success">
                    Add New Shift
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Shift Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Late Threshold</th>
                        <th>Early Check-out Threshold</th>
                        <th width="180" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shifts as $shift)
                        <tr>
                            <td><strong>{{ $shift->name }}</strong></td>
                            <td>{{ date('h:i A', strtotime($shift->start_time)) }}</td>
                            <td>{{ date('h:i A', strtotime($shift->end_time)) }}</td>
                            <td>{{ date('h:i A', strtotime($shift->late_mark_after)) }}</td>
                            <td>{{ date('h:i A', strtotime($shift->early_checkout_before)) }}</td>
                            <td class="text-center">
                                <a href="{{ route('shifts.edit', $shift->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this shift?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No shifts defined yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $shifts->links() }}
    </div>
</div>
@endsection
