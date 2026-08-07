@extends('layouts.app')

@section('title', 'Performance Reviews')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Performance Reviews</h3>
                    <p class="text-muted mb-0">Record and evaluate employee performance monthly, quarterly, or annually.</p>
                </div>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('performance-reviews.create') }}" class="btn btn-success">
                        Create Review Evaluation
                    </a>
                @endif
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
                        <select name="review_type" class="form-select">
                            <option value="">All Review Types</option>
                            <option value="Monthly" {{ request('review_type') === 'Monthly' ? 'selected' : '' }}>Monthly Review</option>
                            <option value="Quarterly" {{ request('review_type') === 'Quarterly' ? 'selected' : '' }}>Quarterly Review</option>
                            <option value="Annual" {{ request('review_type') === 'Annual' ? 'selected' : '' }}>Annual Review</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter Reviews</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Reviews List --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Reviewer</th>
                        <th>Type</th>
                        <th>Period</th>
                        <th>Rating</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th width="160" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                <strong>{{ $review->employee->first_name }} {{ $review->employee->last_name }}</strong>
                                <small class="text-muted d-block">ID: {{ $review->employee->employee_id }}</small>
                            </td>
                            <td>
                                @if($review->reviewer)
                                    {{ $review->reviewer->first_name }} {{ $review->reviewer->last_name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $review->review_type }}</td>
                            <td>{{ $review->period }}</td>
                            <td>
                                <strong class="text-primary">{{ number_format($review->rating, 2) }} / 5.00</strong>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($review->review_date)->format('M d, Y') }}</td>
                            <td>
                                <span class="badge {{ $review->status === 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $review->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('performance-reviews.show', $review->id) }}" class="btn btn-info btn-sm">View</a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('performance-reviews.edit', $review->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('performance-reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this evaluation?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No performance reviews recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
