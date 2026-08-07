@extends('layouts.app')

@section('title', 'Leave Balances')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Leave Balances</h3>
                    <p class="text-muted mb-0">Track and allocate annual, sick, or casual leaves for employees.</p>
                </div>
                <a href="{{ route('leave-balances.create') }}" class="btn btn-success">
                    Allocate Leaves
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
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Allocated Leaves</th>
                        <th>Used Leaves</th>
                        <th>Remaining Leaves</th>
                        <th width="180" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $balance)
                        <tr>
                            <td>
                                <strong>{{ $balance->employee->first_name }} {{ $balance->employee->last_name }}</strong>
                                <small class="text-muted d-block">ID: {{ $balance->employee->employee_id }}</small>
                            </td>
                            <td>{{ $balance->leave_type }}</td>
                            <td>{{ $balance->allocated }}</td>
                            <td>{{ $balance->used }}</td>
                            <td>
                                @php $remaining = $balance->allocated - $balance->used; @endphp
                                <span class="badge {{ $remaining > 2 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $remaining }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('leave-balances.edit', $balance->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('leave-balances.destroy', $balance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this allocation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No leave balances allocated yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $balances->links() }}
    </div>
</div>
@endsection
