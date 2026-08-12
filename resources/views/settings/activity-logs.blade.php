@extends('layouts.app')

@section('title', 'System Activity Logs')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'System Activity Logs']
    ]" />

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">System Activity Audit Trail</h3>
                <p class="text-secondary small mb-0">Real-time audit log tracking user authentication, record updates, deletions, and administrative operations.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-bold fs-7">
                    <i class="bi bi-shield-check me-1"></i> Audit Logging Active
                </span>
            </div>
        </div>
    </div>

    {{-- Filter & Search Card --}}
    <div class="card border border-light-subtle shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4">
            <form action="{{ route('settings.activity-logs') }}" method="GET" class="row g-3 align-items-end">
                {{-- Search Bar --}}
                <div class="col-md-4">
                    <label for="search" class="form-label fw-bold text-dark small mb-1">Search Audit Details</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-light-subtle text-secondary"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" id="search" class="form-control border-light-subtle rounded-end-3" placeholder="Search details, actions, or IP..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Operator / User --}}
                <div class="col-md-3">
                    <label for="user_id" class="form-label fw-bold text-dark small mb-1">Filter by Operator</label>
                    <select name="user_id" id="user_id" class="form-select border-light-subtle rounded-3">
                        <option value="">All Operators</option>
                        @foreach($users ?? [] as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ ucfirst($u->role ?? 'User') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Type --}}
                <div class="col-md-3">
                    <label for="action" class="form-label fw-bold text-dark small mb-1">Action Category</label>
                    <select name="action" id="action" class="form-select border-light-subtle rounded-3">
                        <option value="">All Actions</option>
                        @foreach($actions ?? [] as $act)
                            <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Date Filter & Buttons --}}
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 w-100 fw-bold shadow-sm">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('settings.activity-logs') }}" class="btn btn-outline-secondary rounded-3 px-3" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- System Activity Logs Table Card (100% Screen Fit, Zero Scroll, SaaS Layout) --}}
    <div class="card border border-light-subtle shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive w-100 overflow-hidden">
            <table class="table align-middle mb-0 w-100" id="activityLogsTable" style="font-size: 0.8rem; table-layout: auto;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-3 py-2.5 text-nowrap" width="130">Timestamp</th>
                        <th class="px-2 py-2.5 text-nowrap" width="170">Operator</th>
                        <th class="px-2 py-2.5 text-nowrap" width="160">Action Category</th>
                        <th class="px-2 py-2.5">Details & Context</th>
                        <th class="pe-3 text-end py-2.5 text-nowrap" width="140">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="hover-row">
                            {{-- Stacked Clean Timestamp (Date on top 'M d, Y', Time below 'H:i:s') --}}
                            <td class="ps-3 py-2.5 align-middle text-nowrap">
                                <span class="fw-bold text-dark d-block mb-0" style="font-size: 0.8rem;">
                                    {{ $log->created_at ? $log->created_at->format('M d, Y') : 'N/A' }}
                                </span>
                                <span class="text-secondary d-block font-monospace opacity-75" style="font-size: 0.725rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $log->created_at ? $log->created_at->format('H:i:s') : '00:00:00' }}
                                </span>
                            </td>

                            {{-- Operator with Avatar Initial Circle & Role --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap">
                                <div class="d-flex align-items-center gap-2" style="white-space: nowrap;">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block mb-0" style="font-size: 0.8rem;">{{ $log->user->name ?? 'System Observer' }}</span>
                                        <span class="text-secondary opacity-75 d-block" style="font-size: 0.725rem;">
                                            {{ ucfirst($log->user->role ?? 'System Admin') }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Color-Coded Action Category Pill Badges --}}
                            <td class="px-2 py-2.5 align-middle text-nowrap">
                                @php
                                    $act = strtolower($log->action ?? '');
                                    $badgeClass = 'bg-primary-subtle text-primary border border-primary-subtle'; // Default Blue
                                    $iconClass = 'bi-info-circle-fill';

                                    if (\Illuminate\Support\Str::contains($act, ['created', 'added', 'sent', 'assigned', 'create', 'offer'])) {
                                        $badgeClass = 'bg-success-subtle text-success border border-success-subtle'; // Green
                                        $iconClass = 'bi-plus-circle-fill';
                                    } elseif (\Illuminate\Support\Str::contains($act, ['updated', 'modified', 'changed', 'saved', 'edit'])) {
                                        $badgeClass = 'bg-warning-subtle text-warning border border-warning-subtle'; // Orange
                                        $iconClass = 'bi-pencil-square';
                                    } elseif (\Illuminate\Support\Str::contains($act, ['deleted', 'removed', 'destroyed', 'delete', 'returned'])) {
                                        $badgeClass = 'bg-danger-subtle text-danger border border-danger-subtle'; // Red
                                        $iconClass = 'bi-trash-fill';
                                    } elseif (\Illuminate\Support\Str::contains($act, ['log', 'auth', 'login'])) {
                                        $badgeClass = 'bg-info-subtle text-info border border-info-subtle'; // Cyan
                                        $iconClass = 'bi-key-fill';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.725rem;">
                                    <i class="bi {{ $iconClass }} me-1"></i> {{ $log->action }}
                                </span>
                            </td>

                            {{-- Details & Context (Wraps cleanly) --}}
                            <td class="px-2 py-2.5 align-middle">
                                <span class="text-secondary fw-medium d-block" style="line-height: 1.35; white-space: normal; word-break: break-word;">
                                    {{ $log->details ?: 'No details recorded' }}
                                </span>
                            </td>

                            {{-- IP Address (Monospace Gray Badge with PC Icon) --}}
                            <td class="pe-3 py-2.5 text-end align-middle text-nowrap" width="140">
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-3 px-2 py-1 font-monospace d-inline-flex align-items-center gap-1.5" style="font-size: 0.725rem;">
                                    <i class="bi bi-pc-display opacity-75"></i> {{ $log->ip ?: '127.0.0.1' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state title="No System Activity Logs Captured" icon="bi-journal-x" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
