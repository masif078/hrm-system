<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\InterviewFeedback;
use App\Models\Application;
use App\Models\Employee;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index()
    {
        $interviews = Interview::with(['application.candidate', 'application.jobOpening', 'interviewer'])->latest()->get();
        $applications = Application::with(['candidate', 'jobOpening'])->latest()->get();
        $employees = Employee::orderBy('first_name')->get();
        return view('interviews.index', compact('interviews', 'applications', 'employees'));
    }

    public function create(Request $request)
    {
        $applicationId = $request->query('application_id');
        $application = Application::with('candidate')->findOrFail($applicationId);
        $employees = Employee::all();
        return view('interviews.create', compact('application', 'employees'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'application_id' => 'required|exists:applications,id',
            'date'           => 'required|date',
            'time'           => 'required',
            'interviewer_id' => 'required|exists:employees,id',
            'meeting_link'   => 'nullable|url',
            'notes'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $interview = Interview::create($validated);

        $app = Application::find($request->application_id);
        if ($app) {
            $app->update(['status' => 'HR Interview']);
            if ($app->candidate) {
                $app->candidate->update(['status' => 'HR Interview']);
            }
        }

        ActivityLog::log('Scheduled Interview', "Interview ID: {$interview->id} for Candidate: " . ($app->candidate->full_name ?? 'N/A'));

        $interview->load(['application.candidate', 'application.jobOpening', 'interviewer']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Interview scheduled successfully.',
                'interview' => [
                    'id'              => $interview->id,
                    'candidate_name'  => $interview->application->candidate->full_name ?? 'N/A',
                    'candidate_email' => $interview->application->candidate->email ?? '',
                    'job_title'       => $interview->application->jobOpening->title ?? 'N/A',
                    'interview_date'  => date('M d, Y', strtotime($interview->date)),
                    'interview_time'  => date('h:i A', strtotime($interview->time)),
                    'interviewer_name'=> $interview->interviewer ? ($interview->interviewer->first_name . ' ' . $interview->interviewer->last_name) : 'N/A',
                    'meeting_link'    => $interview->meeting_link,
                    'process_url'     => route('applications.show', $interview->application_id),
                ]
            ]);
        }

        return redirect()->route('applications.show', $request->application_id)->with('success', 'Interview scheduled and candidate stage set to HR Interview.');
    }

    public function show(Interview $interview)
    {
        $interview->load(['application.candidate', 'interviewer', 'feedback']);
        return view('interviews.show', compact('interview'));
    }

    public function feedback(Interview $interview)
    {
        $interview->load('application.candidate');
        return view('interviews.feedback', compact('interview'));
    }

    public function storeFeedback(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'rating_technical' => 'required|integer|min:1|max:5',
            'rating_communication' => 'required|integer|min:1|max:5',
            'rating_behavior' => 'required|integer|min:1|max:5',
            'rating_confidence' => 'required|integer|min:1|max:5',
            'rating_overall' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
        ]);

        $feedback = InterviewFeedback::updateOrCreate(
            ['interview_id' => $interview->id],
            $validated
        );

        ActivityLog::log('Recorded Interview Feedback', "Interview ID: {$interview->id}");

        return redirect()->route('applications.show', $interview->application_id)->with('success', 'Interview scorecard feedback saved successfully.');
    }
}
