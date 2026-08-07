@extends('layouts.app')

@section('title', 'Asset Assignments')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Asset Assignments</h3>
            <p class="text-muted mb-0">Assign hardware devices to employees and record checkout returns.</p>
        </div>
        <a href="{{ route('asset-assignments.create') }}" class="btn btn-primary">Check-out Asset</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Asset</th>
                            <th>Assigned To</th>
                            <th>Assign Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th class="pe-4" width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assign)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $assign->asset->name }}</span>
                                    <small class="text-muted d-block">Serial: {{ $assign->asset->serial_number }}</small>
                                </td>
                                <td>{{ $assign->employee->first_name }} {{ $assign->employee->last_name }}</td>
                                <td>
                                    {{ date('M d, Y', strtotime($assign->assign_date)) }}
                                    <small class="text-muted d-block">Condition: {{ $assign->condition_upon_assign }}</small>
                                </td>
                                <td>
                                    @if($assign->return_date)
                                        {{ date('M d, Y', strtotime($assign->return_date)) }}
                                        <small class="text-muted d-block">Return Condition: {{ $assign->condition_upon_return }}</small>
                                    @else
                                        <span class="text-warning small fw-semibold">Possessed</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $assign->status === 'Assigned' ? 'bg-primary' : ($assign->status === 'Returned' ? 'bg-success' : 'bg-danger') }}">
                                        {{ $assign->status }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    @if($assign->status === 'Assigned')
                                        <a href="{{ route('asset-assignments.edit', $assign->id) }}" class="btn btn-outline-success btn-sm">Process Return (Check-in)</a>
                                    @else
                                        <span class="text-muted small">Done</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No assignments logged.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
