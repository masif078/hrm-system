<?php

namespace App\Http\Controllers;

use App\Models\OfferLetter;
use App\Models\Application;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OfferLetterController extends Controller
{
    public function index()
    {
        $offerLetters = OfferLetter::with(['application.candidate', 'application.jobOpening.department'])->latest()->get();
        $applications = Application::with(['candidate', 'jobOpening'])->latest()->get();
        return view('offer-letters.index', compact('offerLetters', 'applications'));
    }

    public function create(Request $request)
    {
        $applicationId = $request->query('application_id');
        $application = Application::with('candidate')->findOrFail($applicationId);
        return view('offer-letters.create', compact('application'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'application_id' => 'required|exists:applications,id',
            'salary_offered' => 'required|numeric|min:0',
            'joining_date'   => 'required|date',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $offer = OfferLetter::create([
            'application_id' => $validated['application_id'],
            'salary_offered' => $validated['salary_offered'],
            'joining_date'   => $validated['joining_date'],
            'status'         => 'Pending',
            'sent_date'       => date('Y-m-d'),
        ]);

        $app = Application::find($request->application_id);
        if ($app) {
            $app->update(['status' => 'Offer Sent']);
            if ($app->candidate) {
                $app->candidate->update(['status' => 'Offer Sent']);
            }
        }

        ActivityLog::log('Sent Offer Letter', "Offer ID: {$offer->id} for Candidate: " . ($app->candidate->full_name ?? 'N/A'));

        $offer->load(['application.candidate', 'application.jobOpening.department']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'     => true,
                'message'     => 'Offer letter generated and candidate stage set to Offer Sent.',
                'offerLetter' => [
                    'id'              => $offer->id,
                    'candidate_name'  => $offer->application->candidate->full_name ?? 'N/A',
                    'candidate_email' => $offer->application->candidate->email ?? '',
                    'job_title'       => $offer->application->jobOpening->title ?? 'N/A',
                    'department_name' => $offer->application->jobOpening->department->name ?? 'N/A',
                    'salary_offered'  => number_format($offer->salary_offered, 2),
                    'joining_date'   => date('M d, Y', strtotime($offer->joining_date)),
                    'sent_date'       => date('M d, Y', strtotime($offer->sent_date)),
                    'status'          => $offer->status,
                    'show_url'        => route('offer-letters.show', $offer->id),
                    'print_url'       => route('offer-letters.print', $offer->id),
                ]
            ]);
        }

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
        if ($app) {
            $app->update(['status' => $request->status]);
            if ($app->candidate) {
                $app->candidate->update(['status' => $request->status]);
            }
        }

        ActivityLog::log('Updated Offer Letter Status', "Offer ID: {$offerLetter->id} changed to {$request->status}");

        return redirect()->route('applications.show', $offerLetter->application_id)->with('success', "Offer letter marked as {$request->status}.");
    }
}
