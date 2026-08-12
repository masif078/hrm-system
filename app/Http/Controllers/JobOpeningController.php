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
        $departments = Department::orderBy('name')->get();
        return view('job-openings.index', compact('jobs', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('job-openings.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title'           => 'required|string|max:255',
            'department_id'   => 'required|exists:departments,id',
            'vacancies'       => 'required|integer|min:1',
            'employment_type' => 'required|in:Full Time,Part Time,Internship,Contract',
            'location'        => 'required|string|max:255',
            'salary_range'    => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'closing_date'    => 'nullable|date',
            'status'          => 'required|in:Open,Closed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $job = JobOpening::create($validated);
        ActivityLog::log('Created Job Opening', "Job: {$job->title}");
        $job->load('department');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Job posting created successfully.',
                'job'     => [
                    'id'              => $job->id,
                    'title'           => $job->title,
                    'department_name' => $job->department->name ?? 'N/A',
                    'employment_type' => $job->employment_type,
                    'location'        => $job->location,
                    'vacancies'       => $job->vacancies,
                    'closing_date'    => $job->closing_date ? date('M d, Y', strtotime($job->closing_date)) : 'N/A',
                    'status'          => $job->status,
                    'edit_url'        => route('job-openings.edit', $job->id),
                    'destroy_url'     => route('job-openings.destroy', $job->id),
                ]
            ]);
        }

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
