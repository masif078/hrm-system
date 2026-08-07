@extends('layouts.app')

@section('title', 'Attendance Calendar')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="mb-1">Attendance Calendar</h3>
                    <p class="text-muted mb-0">Monthly attendance grid for <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong></p>
                </div>
                
                {{-- Month and Year selection form --}}
                <form action="{{ route('attendance.calendar') }}" method="GET" class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                    @if(auth()->user()->role === 'admin')
                        <select name="employee_id" class="form-select form-select-sm" style="width: 200px;">
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ $employee->id == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    
                    <select name="month" class="form-select form-select-sm" style="width: 120px;">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endfor
                    </select>

                    <select name="year" class="form-select form-select-sm" style="width: 100px;">
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                </form>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Calendar Grid --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0 text-center text-dark">
                {{ date('F Y', mktime(0, 0, 0, $month, 10, $year)) }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle mb-0" style="table-layout: fixed; min-width: 700px;">
                    <thead class="table-dark">
                        <tr>
                            <th width="14.28%">Sun</th>
                            <th width="14.28%">Mon</th>
                            <th width="14.28%">Tue</th>
                            <th width="14.28%">Wed</th>
                            <th width="14.28%">Thu</th>
                            <th width="14.28%">Fri</th>
                            <th width="14.28%">Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            {{-- Print empty cells until startOfWeek --}}
                            @for($i = 0; $i < $startOfWeek; $i++)
                                <td class="bg-light" style="height: 100px;"></td>
                            @endfor

                            {{-- Print days of month --}}
                            @for($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $currentDateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                    $attendance = $attendances->get($currentDateStr);
                                    $holiday = $holidays->get($currentDateStr);
                                    $dayOfWeek = \Carbon\Carbon::create($year, $month, $day)->dayOfWeek;
                                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6); // Sat & Sun
                                @endphp

                                <td style="height: 100px; vertical-align: top; text-align: left; position: relative;" class="{{ $isWeekend ? 'bg-light-subtle' : '' }}">
                                    <div class="fw-bold p-1">{{ $day }}</div>
                                    
                                    <div class="px-1">
                                        @if($holiday)
                                            <div class="small bg-secondary text-white p-1 rounded" style="font-size: 11px;">
                                                Holiday: {{ $holiday->name }}
                                            </div>
                                        @elseif($attendance)
                                            @if($attendance->status === 'Present')
                                                <div class="small bg-success text-white p-1 rounded" style="font-size: 11px;">
                                                    Present
                                                    @if($attendance->check_in)
                                                        <span class="d-block text-white-50" style="font-size: 9px;">In: {{ substr($attendance->check_in, 0, 5) }}</span>
                                                    @endif
                                                </div>
                                            @elseif($attendance->status === 'Late')
                                                <div class="small bg-warning text-dark p-1 rounded" style="font-size: 11px;">
                                                    Late
                                                    @if($attendance->check_in)
                                                        <span class="d-block text-muted" style="font-size: 9px;">In: {{ substr($attendance->check_in, 0, 5) }}</span>
                                                    @endif
                                                </div>
                                            @elseif($attendance->status === 'Leave')
                                                <div class="small bg-info text-white p-1 rounded" style="font-size: 11px;">
                                                    Leave
                                                </div>
                                            @else
                                                <div class="small bg-danger text-white p-1 rounded" style="font-size: 11px;">
                                                    Absent
                                                </div>
                                            @endif
                                        @elseif($isWeekend)
                                            <div class="small text-muted p-1" style="font-size: 11px;">Weekend</div>
                                        @else
                                            <div class="small text-secondary p-1" style="font-size: 11px;">-</div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Break row on Saturday (6th index in row) --}}
                                @if(($startOfWeek + $day) % 7 == 0 && $day < $daysInMonth)
                                    </tr><tr>
                                @endif
                            @endfor

                            {{-- Pad remaining cells in the last row --}}
                            @php
                                $remainingCells = (7 - (($startOfWeek + $daysInMonth) % 7)) % 7;
                            @endphp
                            @for($i = 0; $i < $remainingCells; $i++)
                                <td class="bg-light" style="height: 100px;"></td>
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
