@extends('layouts.app')

@section('title', 'Advanced Attendance & Leave Report')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Reports', 'url' => route('reports.index')],
        ['label' => 'Attendance Report']
    ]" />

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Advanced Attendance & Leave Report</h3>
                <p class="text-secondary small mb-0">Track employee working hours, overtime hours, late arrival logs, and overall leave balance summaries.</p>
            </div>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-3">
                &larr; Back to Reports
            </a>
        </div>
    </div>

    {{-- Filter Section with Increased Status & Year Dropdown Width --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" action="{{ route('reports.attendance') }}">
            <div class="row g-3 align-items-end">
                <div class="col-xl-3 col-md-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">Employee</label>
                    <select name="employee" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-md-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">Specific Date</label>
                    <input type="date"
                           name="date"
                           class="form-control rounded-3 border-light-subtle shadow-2xs"
                           value="{{ request('date') }}">
                </div>

                <div class="col-xl-2 col-md-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">Month</label>
                    <select name="month" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Months</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-xl-2 col-md-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">Year</label>
                    <select name="year" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Years</option>
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Status Dropdown with Increased Width col-xl-3 --}}
                <div class="col-xl-3 col-md-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">Status</label>
                    <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Status</option>
                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                        <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                        <option value="Leave" {{ request('status') == 'Leave' ? 'selected' : '' }}>Leave</option>
                    </select>
                </div>

                <div class="col-xl-12 col-md-12 d-flex gap-2 justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary rounded-3 fw-bold text-white shadow-sm py-2 px-4 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                    <a href="{{ route('reports.attendance') }}" class="btn btn-outline-secondary rounded-3 fw-semibold py-2 px-4 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Metric Cards Row (Summary Stats) --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Total Hours Worked" 
                :value="number_format($totalWorkingHours, 2) . ' hrs'" 
                color="blue" 
                icon="bi-clock-history" 
                trend="Working Hours" 
                trendType="neutral" 
            />
        </div>

        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Total Overtime" 
                :value="number_format($totalOvertimeHours, 2) . ' hrs'" 
                color="green" 
                icon="bi-graph-up-arrow" 
                trend="Extra Hours" 
                trendType="up" 
            />
        </div>

        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Late Arrival Logs" 
                :value="$totalLateArrivals . ' times'" 
                color="amber" 
                icon="bi-clock-fill" 
                trend="Late Marked" 
                trendType="down" 
            />
        </div>

        <div class="col-xl-3 col-md-6">
            <x-stat-card 
                title="Early Checkout Logs" 
                :value="$totalEarlyCheckouts . ' times'" 
                color="purple" 
                icon="bi-box-arrow-right" 
                trend="Early Departure" 
                trendType="down" 
            />
        </div>
    </div>

    {{-- Attendance Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-5">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calendar3-range text-primary me-2"></i>Attendance Logs</h5>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Employee</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Check In</th>
                        <th class="py-3">Check Out</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Working Hours</th>
                        <th class="py-3">Overtime</th>
                        <th class="py-3">Late Arrival</th>
                        <th class="pe-4 text-end py-3">Early Checkout</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr class="hover-row">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="$attendance->employee->first_name . ' ' . $attendance->employee->last_name" size="sm" />
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</span>
                                        <small class="text-secondary opacity-75 d-block">{{ $attendance->employee->department?->name ?? 'No Dept' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-secondary small">{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                            <td class="text-secondary small fw-medium">{{ $attendance->check_in ? date('h:i A', strtotime($attendance->check_in)) : '-' }}</td>
                            <td class="text-secondary small fw-medium">{{ $attendance->check_out ? date('h:i A', strtotime($attendance->check_out)) : '-' }}</td>
                            <td>
                                <x-status-badge :status="$attendance->status" />
                            </td>
                            <td class="fw-semibold text-dark">{{ $attendance->working_hours ? $attendance->working_hours . ' hrs' : '-' }}</td>
                            <td>
                                @if($attendance->overtime_hours > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold">{{ $attendance->overtime_hours }} hrs</span>
                                @else
                                    <span class="text-secondary opacity-50">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->late_arrival)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1 fw-bold">Yes</span>
                                @else
                                    <span class="text-secondary opacity-50">No</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                @if($attendance->early_checkout)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold">Yes</span>
                                @else
                                    <span class="text-secondary opacity-50">No</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-0">
                                <x-empty-state title="No Attendance Logs Found" icon="bi-clock-history" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $attendances->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    {{-- Leave Balances Summary Section --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-card-checklist text-primary me-2"></i>Leave Balances Summary</h5>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Employee</th>
                        <th class="py-3">Leave Type</th>
                        <th class="py-3">Allocated Leaves</th>
                        <th class="py-3">Used Leaves</th>
                        <th class="pe-4 text-end py-3">Remaining Leaves</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveBalances as $balance)
                        @php $remaining = $balance->allocated - $balance->used; @endphp
                        <tr class="hover-row">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="$balance->employee->first_name . ' ' . $balance->employee->last_name" size="sm" />
                                    <span class="fw-bold text-dark">{{ $balance->employee->first_name }} {{ $balance->employee->last_name }}</span>
                                </div>
                            </td>
                            <td class="fw-semibold text-secondary">{{ $balance->leave_type }}</td>
                            <td class="fw-bold text-dark">{{ $balance->allocated }} days</td>
                            <td class="text-secondary fw-medium">{{ $balance->used }} days</td>
                            <td class="pe-4 text-end">
                                <span class="badge {{ $remaining > 2 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} rounded-pill px-3 py-1.5 fw-bold">
                                    {{ $remaining }} days left
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <x-empty-state title="No Leave Allocations Found" icon="bi-calendar-x" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
