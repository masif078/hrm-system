<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Shift;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\Attendance;
use Carbon\Carbon;

class AdvancedAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Shifts
        $dayShift = Shift::create([
            'name' => 'Day Shift',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'late_mark_after' => '09:15:00',
            'early_checkout_before' => '16:45:00',
        ]);

        $nightShift = Shift::create([
            'name' => 'Night Shift',
            'start_time' => '21:00:00',
            'end_time' => '05:00:00',
            'late_mark_after' => '21:15:00',
            'early_checkout_before' => '04:45:00',
        ]);

        // 2. Create Holidays
        Holiday::create([
            'name' => 'Independence Day',
            'date' => date('Y') . '-08-14',
            'type' => 'National',
        ]);

        Holiday::create([
            'name' => 'Quaid-e-Azam Day',
            'date' => date('Y') . '-12-25',
            'type' => 'National',
        ]);

        // 3. Update Employees with Shift and Allocate Leave Balances
        $employees = Employee::all();
        foreach ($employees as $index => $employee) {
            $employee->update([
                'shift_id' => $dayShift->id,
            ]);

            // Allocate Leave Balances
            LeaveBalance::create([
                'employee_id' => $employee->id,
                'leave_type' => 'Casual',
                'allocated' => 12,
                'used' => 2,
            ]);

            LeaveBalance::create([
                'employee_id' => $employee->id,
                'leave_type' => 'Sick',
                'allocated' => 10,
                'used' => 1,
            ]);

            LeaveBalance::create([
                'employee_id' => $employee->id,
                'leave_type' => 'Annual',
                'allocated' => 15,
                'used' => 0,
            ]);

            // Create some past attendances for this month
            // Day 1: Present (On-time)
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => date('Y-m-') . '01',
                'check_in' => '08:55:00',
                'check_out' => '17:05:00',
                'status' => 'Present',
                'working_hours' => 8.17,
                'overtime_hours' => 0.17,
                'late_arrival' => false,
                'early_checkout' => false,
            ]);

            // Day 2: Late Arrival
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => date('Y-m-') . '02',
                'check_in' => '09:25:00',
                'check_out' => '17:00:00',
                'status' => 'Late',
                'working_hours' => 7.58,
                'overtime_hours' => 0.00,
                'late_arrival' => true,
                'early_checkout' => false,
            ]);

            // Day 3: Early Checkout
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => date('Y-m-') . '03',
                'check_in' => '08:50:00',
                'check_out' => '16:30:00',
                'status' => 'Present',
                'working_hours' => 7.67,
                'overtime_hours' => 0.00,
                'late_arrival' => false,
                'early_checkout' => true,
            ]);
        }
    }
}
