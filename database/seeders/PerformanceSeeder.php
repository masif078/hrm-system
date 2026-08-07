<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Employee;
use App\Models\Designation;
use App\Models\Kpi;
use App\Models\KpiAssignment;
use App\Models\KpiScore;
use App\Models\Goal;
use App\Models\PerformanceReview;
use App\Models\Appraisal;

class PerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create KPIs
        $kpi1 = Kpi::create([
            'name' => 'Code Quality',
            'description' => 'Calculated based on code reviews, lack of bugs, and compliance with guidelines.',
            'target_value' => 100,
            'unit' => '%',
        ]);

        $kpi2 = Kpi::create([
            'name' => 'Task Delivery',
            'description' => 'Number of tasks successfully completed and deployed within the deadline.',
            'target_value' => 15,
            'unit' => 'tasks',
        ]);

        $kpi3 = Kpi::create([
            'name' => 'Client Satisfaction',
            'description' => 'Feedback rating scored directly by active clients on projects.',
            'target_value' => 5,
            'unit' => 'points',
        ]);

        // Get existing employees
        $employees = Employee::all();
        $designations = Designation::all();

        if ($employees->isEmpty()) {
            return;
        }

        // 2. Assign KPIs and scores to employees
        foreach ($employees as $index => $employee) {
            // Assign Code Quality and Task Delivery
            KpiAssignment::create([
                'employee_id' => $employee->id,
                'kpi_id' => $kpi1->id,
                'assigned_date' => date('Y-m-d'),
                'status' => 'Active',
            ]);

            KpiAssignment::create([
                'employee_id' => $employee->id,
                'kpi_id' => $kpi2->id,
                'assigned_date' => date('Y-m-d'),
                'status' => 'Active',
            ]);

            // Score KPIs
            KpiScore::create([
                'employee_id' => $employee->id,
                'kpi_id' => $kpi1->id,
                'score' => rand(75, 98),
                'period_month' => date('n'),
                'period_year' => date('Y'),
                'comments' => 'Demonstrated good quality code with minor reviews.',
            ]);

            KpiScore::create([
                'employee_id' => $employee->id,
                'kpi_id' => $kpi2->id,
                'score' => rand(10, 15),
                'period_month' => date('n'),
                'period_year' => date('Y'),
                'comments' => 'Delivered tasks mostly on schedule.',
            ]);

            // 3. Create Goals
            Goal::create([
                'employee_id' => $employee->id,
                'title' => 'Complete Payroll System Integration',
                'description' => 'Integrate salary generation and PDF payslip downloads.',
                'target_date' => date('Y-m-d', strtotime('+15 days')),
                'progress' => 80,
                'status' => 'In Progress',
            ]);

            Goal::create([
                'employee_id' => $employee->id,
                'title' => 'Refactor Legacy Auth Controller',
                'description' => 'Remove old dependencies and clean route declarations.',
                'target_date' => date('Y-m-d', strtotime('-5 days')),
                'progress' => 100,
                'status' => 'Completed',
            ]);

            // 4. Create Performance Reviews (Rating scale 1-5)
            $rating = $index % 2 == 0 ? 4.50 : 3.20; // Some high performers, some average
            $review = PerformanceReview::create([
                'employee_id' => $employee->id,
                'reviewer_id' => $employees->first()->id, // Evaluated by first employee
                'review_type' => 'Quarterly',
                'period' => 'Q2 ' . date('Y'),
                'rating' => $rating,
                'strengths' => 'Strong communication, timely code integration, very helpful team-mate.',
                'improvements' => 'Focus more on unit test coverage for new endpoints.',
                'review_date' => date('Y-m-d', strtotime('-10 days')),
                'status' => 'Completed',
            ]);

            // 5. Create Appraisal increment histories
            if ($rating >= 4.0) {
                // High performers get increment/promotions
                $newSalary = $employee->salary * 1.15; // 15% increment
                $newDesignationId = $designations->count() > 1 ? $designations->last()->id : null;
                
                Appraisal::create([
                    'employee_id' => $employee->id,
                    'performance_review_id' => $review->id,
                    'rating_class' => 'High Performer',
                    'action_type' => $newDesignationId ? 'Both' : 'Increment',
                    'previous_salary' => $employee->salary,
                    'new_salary' => $newSalary,
                    'previous_designation_id' => $employee->designation_id,
                    'new_designation_id' => $newDesignationId,
                    'effective_date' => date('Y-m-d'),
                    'status' => 'Approved', // Approved auto-applies the effects in real system, but let's seed it
                ]);

                // Apply seeded changes to employee table
                $employee->salary = $newSalary;
                if ($newDesignationId) {
                    $employee->designation_id = $newDesignationId;
                }
                $employee->save();
            }
        }
    }
}
