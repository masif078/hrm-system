<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Shift;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function employeeIndex(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('employee.dashboard')
                ->with('error', 'No employee profile linked to your account. Please contact admin.');
        }

        $query = Attendance::with('employee')
            ->where('employee_id', $employee->id);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        return view('attendance.index', compact('attendances', 'todayAttendance'));
    }

    public function checkIn()
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return redirect()->route('employee.dashboard')->with('error', 'Employee profile not found.');
        }

        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        if ($existing) {
            return redirect()->route('employee.attendances.index')
                ->with('error', 'You have already checked in today.');
        }

        $status = 'Present';
        $lateArrival = false;

        $shift = $employee->shift;
        if ($shift) {
            $currentTime = now()->format('H:i:s');
            if ($currentTime > $shift->late_mark_after) {
                $status = 'Late';
                $lateArrival = true;
            }
        }

        Attendance::create([
            'employee_id'  => $employee->id,
            'date'         => today(),
            'check_in'     => now()->format('H:i:s'),
            'status'       => $status,
            'late_arrival' => $lateArrival,
        ]);

        $isHoliday = Holiday::whereDate('date', today())->exists();
        $msg = $isHoliday ? 'Checked in successfully (Holiday Duty).' : 'Checked in successfully.';

        return redirect()->route('employee.attendances.index')
            ->with('success', $msg);
    }

    public function checkOut()
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return redirect()->route('employee.dashboard')->with('error', 'Employee profile not found.');
        }

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            return redirect()->route('employee.attendances.index')
                ->with('error', 'You have not checked in today.');
        }

        if ($attendance->check_out) {
            return redirect()->route('employee.attendances.index')
                ->with('error', 'You have already checked out today.');
        }

        $checkOutTimeStr = now()->format('H:i:s');
        $earlyCheckout = false;
        
        $shift = $employee->shift;
        if ($shift) {
            if ($checkOutTimeStr < $shift->early_checkout_before) {
                $earlyCheckout = true;
            }
        }

        // Calculate working hours
        $checkInDateTime = \Carbon\Carbon::parse($attendance->date . ' ' . $attendance->check_in);
        $checkOutDateTime = now();
        $workingHours = round($checkInDateTime->diffInMinutes($checkOutDateTime) / 60.0, 2);

        // Calculate overtime
        $overtimeHours = 0.00;
        $isHoliday = Holiday::whereDate('date', today())->exists();

        if ($isHoliday) {
            $overtimeHours = $workingHours;
        } elseif ($shift) {
            $shiftStart = \Carbon\Carbon::parse($attendance->date . ' ' . $shift->start_time);
            $shiftEnd = \Carbon\Carbon::parse($attendance->date . ' ' . $shift->end_time);
            $shiftDuration = round($shiftStart->diffInMinutes($shiftEnd) / 60.0, 2);

            if ($workingHours > $shiftDuration) {
                $overtimeHours = round($workingHours - $shiftDuration, 2);
            }
        }

        $attendance->update([
            'check_out'      => $checkOutTimeStr,
            'early_checkout' => $earlyCheckout,
            'working_hours'  => $workingHours,
            'overtime_hours' => $overtimeHours,
        ]);

        return redirect()->route('employee.attendances.index')
            ->with('success', 'Checked out successfully.');
    }

    public function index(Request $request)
    {
        if (auth()->user()->role === 'employee') {
            return $this->employeeIndex($request);
        }

        $query = Attendance::with('employee');

        if ($request->filled('search')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $attendances = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('attendance.index', compact('attendances'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized Access.');
        }

        $employees = Employee::all();

        return view('attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized Access.');
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'check_in'    => 'nullable',
            'check_out'   => 'nullable',
            'status'      => 'required',
        ]);

        Attendance::create($request->all());

        return redirect()
                ->route('attendances.index')
                ->with('success', 'Attendance added successfully.');
    }

    public function edit(Attendance $attendance)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized Access.');
        }

        $employees = Employee::all();

        return view('attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized Access.');
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'check_in'    => 'nullable',
            'check_out'   => 'nullable',
            'status'      => 'required',
        ]);

        $attendance->update($request->all());

        return redirect()
                ->route('attendances.index')
                ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized Access.');
        }

        $attendance->delete();

        return redirect()
                ->route('attendances.index')
                ->with('success', 'Attendance deleted successfully.');
    }

    public function show(Attendance $attendance)
    {
        if (auth()->user()->role === 'employee') {
            $employee = auth()->user()->employee;
            if (!$employee || $attendance->employee_id != $employee->id) {
                abort(403, 'Unauthorized Access: You can only view your own attendance records.');
            }
        }

        $attendance->load('employee');

        return view('attendance.show', compact('attendance'));
    }
}
