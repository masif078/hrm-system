@extends('layouts.app')

@section('title', 'Holiday Management')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Holiday Management</h3>
                    <p class="text-muted mb-0">Define and manage company holidays and public observances.</p>
                </div>
                <a href="{{ route('holidays.create') }}" class="btn btn-success">
                    Add New Holiday
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
                        <th>Holiday Name</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th width="180" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $holiday)
                        <tr>
                            <td><strong>{{ $holiday->name }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($holiday->date)->format('F d, Y') }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $holiday->type }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this holiday?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No holidays defined yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $holidays->links() }}
    </div>
</div>
@endsection
