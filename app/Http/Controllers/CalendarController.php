<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // 1. Resolve Employee
        if ($user->role === 'admin') {
            $employeeId = $request->input('employee_id');
            $employee = $employeeId ? Employee::find($employeeId) : Employee::first();
        } else {
            $employee = $user->employee;
        }

        if (!$employee) {
            return redirect()->back()->with('error', 'No employee profile found.');
        }

        // 2. Resolve Year and Month
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));

        $firstDayOfMonth = Carbon::create($year, $month, 1);
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        $startOfWeek = $firstDayOfMonth->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        // 3. Fetch Attendances for this month
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(function($item) {
                return $item->date;
            });

        // 4. Fetch Holidays for this month
        $holidays = Holiday::whereMonth('date', $month)
            ->get()
            ->keyBy(function($item) {
                return $item->date;
            });

        $employees = $user->role === 'admin' ? Employee::all() : collect();

        return view('attendance.calendar', compact(
            'employee',
            'employees',
            'year',
            'month',
            'daysInMonth',
            'startOfWeek',
            'attendances',
            'holidays',
            'firstDayOfMonth'
        ));
    }
}
