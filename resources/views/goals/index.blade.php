@extends('layouts.app')

@section('title', 'Goals Tracking')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Goals Tracking</h3>
                    <p class="text-muted mb-0">Define, track, and complete target goals for employees.</p>
                </div>
                <a href="{{ route('goals.create') }}" class="btn btn-success">
                    Add New Goal
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter for Admin --}}
    @if(auth()->user()->role === 'admin')
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <select name="employee_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter Goals</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Goals Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        @if(auth()->user()->role === 'admin')
                            <th>Employee</th>
                        @endif
                        <th>Goal Title</th>
                        <th>Target Date</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th width="160" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($goals as $goal)
                        <tr>
                            @if(auth()->user()->role === 'admin')
                                <td>
                                    <strong>{{ $goal->employee->first_name }} {{ $goal->employee->last_name }}</strong>
                                    <small class="text-muted d-block">ID: {{ $goal->employee->employee_id }}</small>
                                </td>
                            @endif
                            <td>
                                <strong>{{ $goal->title }}</strong>
                                <small class="text-muted d-block">{{ $goal->description }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($goal->target_date)->format('M d, Y') }}</td>
                            <td>
                                <div class="progress" style="height: 15px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $goal->progress }}%;" aria-valuenow="{{ $goal->progress }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $goal->progress }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $goal->status === 'Completed' ? 'bg-success' : ($goal->status === 'In Progress' ? 'bg-warning text-dark' : ($goal->status === 'Cancelled' ? 'bg-danger' : 'bg-secondary')) }}">
                                    {{ $goal->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this goal?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="text-center py-4 text-muted">No goals tracked yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $goals->links() }}
    </div>
</div>
@endsection
