<?php

namespace App\Http\Controllers;

use App\Models\JobOpening;
use App\Models\Department;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class JobOpeningController extends Controller
{
    public function index()
    {
        $jobs = JobOpening::with('department')->latest()->get();
        return view('job-openings.index', compact('jobs'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('job-openings.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'vacancies' => 'required|integer|min:1',
            'employment_type' => 'required|in:Full Time,Part Time,Internship,Contract',
            'location' => 'required|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'closing_date' => 'nullable|date',
            'status' => 'required|in:Open,Closed',
        ]);

        $job = JobOpening::create($validated);
        ActivityLog::log('Created Job Opening', "Job: {$job->title}");

        return redirect()->route('job-openings.index')->with('success', 'Job opening created successfully.');
    }

    public function edit(JobOpening $jobOpening)
    {
        $departments = Department::all();
        return view('job-openings.edit', compact('jobOpening', 'departments'));
    }

    public function update(Request $request, JobOpening $jobOpening)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'vacancies' => 'required|integer|min:1',
            'employment_type' => 'required|in:Full Time,Part Time,Internship,Contract',
            'location' => 'required|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'closing_date' => 'nullable|date',
            'status' => 'required|in:Open,Closed',
        ]);

        $jobOpening->update($validated);
        ActivityLog::log('Updated Job Opening', "Job: {$jobOpening->title}");

        return redirect()->route('job-openings.index')->with('success', 'Job opening updated successfully.');
    }

    public function destroy(JobOpening $jobOpening)
    {
        ActivityLog::log('Deleted Job Opening', "Job: {$jobOpening->title}");
        $jobOpening->delete();
        return redirect()->route('job-openings.index')->with('success', 'Job opening deleted successfully.');
    }
}
