@extends('layouts.app')

@section('title', 'System Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">System Activity Logs</h3>
        <p class="text-muted">Audit trail tracking updates, deletions, and administrative actions.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Timestamp</th>
                            <th>Operator</th>
                            <th>Action Type</th>
                            <th>Details</th>
                            <th class="pe-4">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $log->user->name ?? 'System Observer' }}</span>
                                    <small class="text-muted d-block">{{ $log->user->role ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border border-secondary-subtle">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>{{ $log->details ?: 'No details recorded' }}</td>
                                <td class="pe-4"><code>{{ $log->ip }}</code></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No activity logs captured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
