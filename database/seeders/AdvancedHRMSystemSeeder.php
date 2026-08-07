<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Department;
use App\Models\Employee;
use App\Models\JobOpening;
use App\Models\Candidate;
use App\Models\Application;
use App\Models\Interview;
use App\Models\InterviewFeedback;
use App\Models\OfferLetter;
use App\Models\AssetCategory;
use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\Branch;
use App\Models\CompanyPolicy;
use App\Models\CompanySetting;

class AdvancedHRMSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get or create Department/Employee fallbacks
        $dept = Department::first() ?: Department::create(['name' => 'IT Department']);
        $emp = Employee::first() ?: Employee::create([
            'first_name' => 'Ali',
            'last_name' => 'Ahmed',
            'email' => 'ali@hrm.com',
            'phone' => '0300-1234567',
            'salary' => 60000.00,
            'hire_date' => date('Y-m-d'),
            'status' => 'Active',
            'department_id' => $dept->id,
        ]);

        // 2. Job Openings
        $job1 = JobOpening::create([
            'title' => 'Laravel Backend Developer',
            'department_id' => $dept->id,
            'vacancies' => 2,
            'employment_type' => 'Full Time',
            'location' => 'Islamabad Branch (Onsite)',
            'salary_range' => '80,000 - 120,000',
            'description' => 'We are seeking a talented Laravel backend developer to join our product squad.',
            'closing_date' => date('Y-m-d', strtotime('+30 days')),
            'status' => 'Open',
        ]);

        $job2 = JobOpening::create([
            'title' => 'HR Operations Specialist',
            'department_id' => $dept->id,
            'vacancies' => 1,
            'employment_type' => 'Full Time',
            'location' => 'Lahore Branch',
            'salary_range' => '50,000 - 70,000',
            'description' => 'Responsible for employee files, payroll assistance, and office policies management.',
            'closing_date' => date('Y-m-d', strtotime('+15 days')),
            'status' => 'Open',
        ]);

        // 3. Candidates
        $cand1 = Candidate::create([
            'full_name' => 'Sara Khan',
            'email' => 'sara@example.com',
            'phone' => '0331-5556677',
            'address' => 'G-9, Islamabad',
            'skills' => 'PHP, Laravel, Git, Rest APIs',
            'experience' => 3,
            'qualification' => 'BS Computer Science',
            'source' => 'LinkedIn',
            'status' => 'Technical Interview',
        ]);

        $cand2 = Candidate::create([
            'full_name' => 'Bilal Ahmed',
            'email' => 'bilal.ahmed@example.com',
            'phone' => '0321-4448899',
            'address' => 'DHA Phase 5, Lahore',
            'skills' => 'HR Management, Employee Relations, Communication',
            'experience' => 2,
            'qualification' => 'MBA HR',
            'source' => 'Referral',
            'status' => 'Offer Sent',
        ]);

        // 4. Applications
        $app1 = Application::create([
            'candidate_id' => $cand1->id,
            'job_opening_id' => $job1->id,
            'status' => 'Technical Interview',
        ]);

        $app2 = Application::create([
            'candidate_id' => $cand2->id,
            'job_opening_id' => $job2->id,
            'status' => 'Offer Sent',
        ]);

        // 5. Interviews & Feedback
        $interview1 = Interview::create([
            'application_id' => $app1->id,
            'date' => date('Y-m-d'),
            'time' => '11:00:00',
            'interviewer_id' => $emp->id,
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            'notes' => 'Discuss OOP concepts, Laravel framework lifecycles, and database query optimizations.',
        ]);

        InterviewFeedback::create([
            'interview_id' => $interview1->id,
            'rating_technical' => 4,
            'rating_communication' => 5,
            'rating_behavior' => 4,
            'rating_confidence' => 4,
            'rating_overall' => 4,
            'comments' => 'Strong communication, has a good grasp of PHP fundamentals, and answered databases queries nicely.',
        ]);

        // 6. Offer Letter
        OfferLetter::create([
            'application_id' => $app2->id,
            'salary_offered' => 65000.00,
            'joining_date' => date('Y-m-d', strtotime('+10 days')),
            'status' => 'Pending',
            'sent_date' => date('Y-m-d'),
        ]);

        // 7. Asset Categories
        $cat1 = AssetCategory::create([
            'name' => 'Laptops',
            'description' => 'Office development laptops including MacBook, Lenovo Thinkpad, Dell Latitude.',
        ]);

        $cat2 = AssetCategory::create([
            'name' => 'Monitors',
            'description' => 'IPS monitors and office desk screens.',
        ]);

        // 8. Assets
        $asset1 = Asset::create([
            'name' => 'MacBook Pro M3 Pro 16"',
            'asset_category_id' => $cat1->id,
            'serial_number' => 'SN-APPLE-M3-9988',
            'cost' => 450000.00,
            'purchase_date' => date('Y-m-d', strtotime('-6 months')),
            'warranty_expiry' => date('Y-m-d', strtotime('+6 months')),
            'status' => 'Assigned',
        ]);

        $asset2 = Asset::create([
            'name' => 'ThinkPad T14 Gen 4',
            'asset_category_id' => $cat1->id,
            'serial_number' => 'SN-LENOVO-T14-5544',
            'cost' => 280000.00,
            'purchase_date' => date('Y-m-d', strtotime('-1 year')),
            'warranty_expiry' => date('Y-m-d', strtotime('-1 day')), // Expired
            'status' => 'Available',
        ]);

        $asset3 = Asset::create([
            'name' => 'Dell UltraSharp U2723QE',
            'asset_category_id' => $cat2->id,
            'serial_number' => 'SN-DELL-US27-2233',
            'cost' => 120000.00,
            'purchase_date' => date('Y-m-d', strtotime('-3 months')),
            'warranty_expiry' => date('Y-m-d', strtotime('+9 months')),
            'status' => 'Available',
        ]);

        // 9. Asset Assignment
        AssetAssignment::create([
            'asset_id' => $asset1->id,
            'employee_id' => $emp->id,
            'assign_date' => date('Y-m-d', strtotime('-5 months')),
            'condition_upon_assign' => 'Excellent / New Box Pack',
            'status' => 'Assigned',
        ]);

        // 10. Branches
        Branch::create([
            'name' => 'Islamabad Head Office',
            'location' => 'Evacuee Trust Complex, G-5, Islamabad',
            'manager_id' => $emp->id,
            'status' => 'Active',
        ]);

        Branch::create([
            'name' => 'Lahore Branch',
            'location' => 'Gulberg III, Lahore',
            'manager_id' => null,
            'status' => 'Active',
        ]);

        // 11. Company Policies
        CompanyPolicy::create([
            'title' => 'Standard Annual Leave Policy',
            'type' => 'Leave Policy',
            'content' => "1. Every full-time employee is entitled to 20 days of paid leaves per calendar year.\n2. Leaves must be requested at least 3 days in advance.\n3. Sick leaves do not require advance notice but medical certificate is required if leave exceeds 2 days.",
        ]);

        CompanyPolicy::create([
            'title' => 'Office Late Mark Grace Period Policy',
            'type' => 'Attendance Policy',
            'content' => "1. Standard shift start time is 09:00 AM.\n2. Grace period of 15 minutes is allowed (till 09:15 AM).\n3. Any arrival after 09:15 AM is flagged as Late.\n4. Three consecutive late marks result in 0.5 day salary deduction.",
        ]);

        // 12. Default Company Settings
        CompanySetting::set('company_name', 'HRM Solutions Ltd');
        CompanySetting::set('company_email', 'contact@hrmsolutions.com');
        CompanySetting::set('company_phone', '051-111-222-333');
        CompanySetting::set('company_address', 'F-6 Markaz, Islamabad, Pakistan');
        CompanySetting::set('company_timezone', 'Asia/Karachi');
        CompanySetting::set('company_currency', 'PKR');
    }
}
