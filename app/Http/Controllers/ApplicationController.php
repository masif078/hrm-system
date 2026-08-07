<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['candidate', 'jobOpening.department'])->latest()->get();
        return view('applications.index', compact('applications'));
    }

    public function create()
    {
        $candidates = Candidate::all();
        $jobs = JobOpening::where('status', 'Open')->get();
        return view('applications.create', compact('candidates', 'jobs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'job_opening_id' => 'required|exists:job_openings,id',
            'status' => 'required|in:Applied,Shortlisted,HR Interview,Technical Interview,Final Interview,Offer Sent,Accepted,Rejected,Hired',
        ]);

        $app = Application::create($validated);

        // Sync candidate status
        $app->candidate->update(['status' => $request->status]);

        ActivityLog::log('Created Job Application', "Candidate ID: {$request->candidate_id} for Job ID: {$request->job_opening_id}");

        return redirect()->route('applications.index')->with('success', 'Application registered successfully.');
    }

    public function show(Application $application)
    {
        $application->load(['candidate', 'jobOpening.department', 'interviews.interviewer', 'interviews.feedback', 'offerLetter']);
        return view('applications.show', compact('application'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:Applied,Shortlisted,HR Interview,Technical Interview,Final Interview,Offer Sent,Accepted,Rejected,Hired',
        ]);

        $oldStatus = $application->status;
        $application->update(['status' => $request->status]);
        $application->candidate->update(['status' => $request->status]);

        ActivityLog::log('Updated Application Status', "App ID: {$application->id} from {$oldStatus} to {$request->status}");

        // If newly transitioned to Hired, automate onboarding
        if ($request->status === 'Hired' && $oldStatus !== 'Hired') {
            $this->onboardCandidate($application);
            return redirect()->route('applications.show', $application->id)->with('success', 'Application marked as Hired. Employee onboarding successfully automated!');
        }

        return redirect()->route('applications.show', $application->id)->with('success', 'Application status updated successfully.');
    }

    protected function onboardCandidate(Application $application)
    {
        $candidate = $application->candidate;
        $job = $application->jobOpening;

        // Parse Name
        $nameParts = explode(' ', $candidate->full_name, 2);
        $firstName = $nameParts[0] ?? 'Candidate';
        $lastName = $nameParts[1] ?? 'Onboarded';

        // Check if user already exists
        $user = User::where('email', $candidate->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $candidate->full_name,
                'email' => $candidate->email,
                'password' => bcrypt('password123'), // Default temporary password
                'role' => 'employee',
            ]);
        }

        // Check or create Designation based on Job Title
        $designation = Designation::where('title', $job->title)->first();
        if (!$designation) {
            $designation = Designation::create([
                'title' => $job->title,
                'department_id' => $job->department_id,
            ]);
        }

        // Check if employee already exists
        $employee = Employee::where('email', $candidate->email)->first();
        if (!$employee) {
            // Find or set base salary
            $salary = 50000; // Default base salary
            if ($job->salary_range) {
                // simple extraction of number if numeric
                preg_match('/\d+/', str_replace(',', '', $job->salary_range), $numMatches);
                if (!empty($numMatches[0])) {
                    $salary = (float)$numMatches[0];
                }
            }

            Employee::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $candidate->email,
                'phone' => $candidate->phone ?: '000-0000',
                'department_id' => $job->department_id,
                'designation_id' => $designation->id,
                'salary' => $salary,
                'hire_date' => date('Y-m-d'),
                'status' => 'Active',
            ]);

            ActivityLog::log('Automated Candidate Onboarding', "Onboarded {$candidate->full_name} as employee");
        }
    }
}
