@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Advanced Attendance & Leave Report</h4>
        </div>
        <div class="card-body">

            {{-- Filter Form --}}
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Employee</label>
                    <select name="employee" class="form-select">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ request('employee') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Specific Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Month</label>
                    <select name="month" class="form-select">
                        <option value="">All Months</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small fw-bold">Year</label>
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                        <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <a href="{{ route('reports.attendance') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>

            {{-- Summary Stats Row (Plain text metrics, no icons/emojis) --}}
            <div class="row g-3 mb-4 text-center">
                <div class="col-md-3">
                    <div class="border rounded p-3 bg-light">
                        <span class="text-muted d-block small mb-1">Total Hours Worked</span>
                        <h4 class="fw-bold mb-0">{{ number_format($totalWorkingHours, 2) }} hrs</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 bg-light">
                        <span class="text-muted d-block small mb-1">Total Overtime Worked</span>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($totalOvertimeHours, 2) }} hrs</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 bg-light">
                        <span class="text-muted d-block small mb-1">Late Arrival Logs</span>
                        <h4 class="fw-bold mb-0 text-warning">{{ $totalLateArrivals }} times</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 bg-light">
                        <span class="text-muted d-block small mb-1">Early Checkout Logs</span>
                        <h4 class="fw-bold mb-0 text-danger">{{ $totalEarlyCheckouts }} times</h4>
                    </div>
                </div>
            </div>

            {{-- Attendance Table --}}
            <h5 class="fw-bold mb-3 border-bottom pb-2">Attendance Logs</h5>
            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Working Hours</th>
                            <th>Overtime Hours</th>
                            <th>Late Arrival</th>
                            <th>Early Checkout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>
                                    <strong>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</strong>
                                    <small class="text-muted d-block">{{ $attendance->employee->department?->name }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                <td>{{ $attendance->check_in ? date('h:i A', strtotime($attendance->check_in)) : '-' }}</td>
                                <td>{{ $attendance->check_out ? date('h:i A', strtotime($attendance->check_out)) : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $attendance->status == 'Present' ? 'success' : ($attendance->status == 'Late' ? 'warning' : 'danger') }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td>{{ $attendance->working_hours ? $attendance->working_hours . ' hrs' : '-' }}</td>
                                <td>
                                    @if($attendance->overtime_hours > 0)
                                        <span class="text-success fw-semibold">{{ $attendance->overtime_hours }} hrs</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->late_arrival)
                                        <span class="badge bg-warning text-dark">Yes</span>
                                    @else
                                        <span class="text-muted">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->early_checkout)
                                        <span class="badge bg-danger text-white">Yes</span>
                                    @else
                                        <span class="text-muted">No</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No Attendance logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $attendances->links() }}

            {{-- Leave Balances Summary Section --}}
            <h5 class="fw-bold mb-3 border-bottom pb-2 mt-5">Leave Balances Summary</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Allocated Leaves</th>
                            <th>Used Leaves</th>
                            <th>Remaining Leaves</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveBalances as $balance)
                            @php $remaining = $balance->allocated - $balance->used; @endphp
                            <tr>
                                <td><strong>{{ $balance->employee->first_name }} {{ $balance->employee->last_name }}</strong></td>
                                <td>{{ $balance->leave_type }}</td>
                                <td>{{ $balance->allocated }}</td>
                                <td>{{ $balance->used }}</td>
                                <td>
                                    <span class="badge {{ $remaining > 2 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $remaining }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">No leave allocations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
