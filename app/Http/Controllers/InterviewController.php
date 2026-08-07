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
        $interviews = Interview::with(['application.candidate', 'interviewer'])->latest()->get();
        return view('interviews.index', compact('interviews'));
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
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'date' => 'required|date',
            'time' => 'required',
            'interviewer_id' => 'required|exists:employees,id',
            'meeting_link' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        $interview = Interview::create($validated);
        
        // Auto transition application stage to Interview
        $app = Application::find($request->application_id);
        $app->update(['status' => 'HR Interview']);
        $app->candidate->update(['status' => 'HR Interview']);

        ActivityLog::log('Scheduled Interview', "Interview ID: {$interview->id} for Candidate: {$app->candidate->full_name}");

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
