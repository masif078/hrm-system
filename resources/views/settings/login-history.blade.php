@extends('layouts.app')

@section('title', 'Login History Audit')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Login History Audit</h3>
        <p class="text-muted">Review history logs of user login and logout timestamps.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">User</th>
                            <th>Login Time</th>
                            <th>Logout Time</th>
                            <th>IP Address</th>
                            <th>Browser</th>
                            <th class="pe-4">Device</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $log->user->name ?? 'Deleted User' }}</span>
                                    <small class="text-muted d-block">{{ $log->user->email ?? '' }}</small>
                                </td>
                                <td>{{ date('M d, Y H:i:s', strtotime($log->login_time)) }}</td>
                                <td>
                                    @if($log->logout_time)
                                        {{ date('M d, Y H:i:s', strtotime($log->logout_time)) }}
                                    @else
                                        <span class="text-success small fw-semibold">Active Session</span>
                                    @endif
                                </td>
                                <td><code>{{ $log->ip }}</code></td>
                                <td class="small">{{ Str::limit($log->browser, 80) }}</td>
                                <td class="pe-4"><span class="badge bg-light text-dark border">{{ $log->device }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No login histories recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
