<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\Payroll;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();

        if ($employees->isEmpty()) {
            return;
        }

        foreach ($employees as $index => $employee) {
            // 1. Create Salary Structure
            $basic = 50000 + ($index * 15000);
            $house = 8000;
            $medical = 4000;
            $transport = 5000;
            $other_allow = 2000;
            
            $tax = 1500 + ($index * 500);
            $pf = 2500;
            $other_deduct = 1000;

            $gross = $basic + $house + $medical + $transport + $other_allow;
            $deductions = $tax + $pf + $other_deduct;
            $net = $gross - $deductions;

            $structure = SalaryStructure::create([
                'employee_id' => $employee->id,
                'basic_salary' => $basic,
                'house_allowance' => $house,
                'medical_allowance' => $medical,
                'transport_allowance' => $transport,
                'other_allowance' => $other_allow,
                'tax' => $tax,
                'provident_fund' => $pf,
                'other_deduction' => $other_deduct,
                'net_salary' => $net,
                'effective_from' => '2026-01-01',
                'status' => 'active',
            ]);

            // 2. Create Payrolls for last two months (June & July 2026)
            // Month 6 (June 2026) - Paid
            Payroll::create([
                'employee_id' => $employee->id,
                'salary_structure_id' => $structure->id,
                'month' => 6,
                'year' => 2026,
                'gross_salary' => $gross,
                'total_allowances' => $house + $medical + $transport + $other_allow,
                'total_deductions' => $deductions,
                'net_salary' => $net,
                'payment_date' => '2026-07-02',
                'payment_status' => 'paid',
                'remarks' => 'June salary transferred successfully.',
            ]);

            // Month 7 (July 2026) - Pending for some, paid for others
            $status = ($index % 2 === 0) ? 'paid' : 'pending';
            $paidDate = ($status === 'paid') ? '2026-08-01' : null;

            Payroll::create([
                'employee_id' => $employee->id,
                'salary_structure_id' => $structure->id,
                'month' => 7,
                'year' => 2026,
                'gross_salary' => $gross,
                'total_allowances' => $house + $medical + $transport + $other_allow,
                'total_deductions' => $deductions,
                'net_salary' => $net,
                'payment_date' => $paidDate,
                'payment_status' => $status,
                'remarks' => ($status === 'paid') ? 'July salary transferred.' : 'Awaiting admin approval.',
            ]);
        }
    }
}
