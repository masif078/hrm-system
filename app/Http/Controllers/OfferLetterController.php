<?php

namespace App\Http\Controllers;

use App\Models\OfferLetter;
use App\Models\Application;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OfferLetterController extends Controller
{
    public function create(Request $request)
    {
        $applicationId = $request->query('application_id');
        $application = Application::with('candidate')->findOrFail($applicationId);
        return view('offer-letters.create', compact('application'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'salary_offered' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
        ]);

        $offer = OfferLetter::create([
            'application_id' => $request->application_id,
            'salary_offered' => $request->salary_offered,
            'joining_date' => $request->joining_date,
            'status' => 'Pending',
            'sent_date' => date('Y-m-d'),
        ]);

        $app = Application::find($request->application_id);
        $app->update(['status' => 'Offer Sent']);
        $app->candidate->update(['status' => 'Offer Sent']);

        ActivityLog::log('Sent Offer Letter', "Offer ID: {$offer->id} for Candidate: {$app->candidate->full_name}");

        return redirect()->route('applications.show', $request->application_id)->with('success', 'Offer letter generated and candidate stage set to Offer Sent.');
    }

    public function show(OfferLetter $offerLetter)
    {
        $offerLetter->load(['application.candidate', 'application.jobOpening.department']);
        return view('offer-letters.show', compact('offerLetter'));
    }

    public function print(OfferLetter $offerLetter)
    {
        $offerLetter->load(['application.candidate', 'application.jobOpening.department']);
        return view('offer-letters.print', compact('offerLetter'));
    }

    public function updateStatus(Request $request, OfferLetter $offerLetter)
    {
        $validated = $request->validate([
            'status' => 'required|in:Accepted,Rejected',
        ]);

        $offerLetter->update(['status' => $request->status]);

        $app = $offerLetter->application;
        $app->update(['status' => $request->status]);
        $app->candidate->update(['status' => $request->status]);

        ActivityLog::log('Updated Offer Letter Status', "Offer ID: {$offerLetter->id} changed to {$request->status}");

        return redirect()->route('applications.show', $offerLetter->application_id)->with('success', "Offer letter marked as {$request->status}.");
    }
}
