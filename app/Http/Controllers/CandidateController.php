<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\ActivityLog;
use App\Helpers\ResumeParser;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::latest()->get();
        return view('candidates.index', compact('candidates'));
    }

    public function create()
    {
        return view('candidates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,txt,docx|max:2048',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:Applied,Shortlisted,HR Interview,Technical Interview,Final Interview,Offer Sent,Accepted,Rejected,Hired',
        ]);

        $candidateData = $request->except('resume');
        
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $candidateData['resume'] = $path;
            
            // Trigger Resume Parser Helper
            $parsed = ResumeParser::parse(storage_path('app/public/' . $path));
            $candidateData['skills'] = $parsed['skills'];
            $candidateData['experience'] = $parsed['experience'];
            $candidateData['qualification'] = $parsed['qualification'];
        } else {
            // Default manual entries if no resume
            $candidateData['skills'] = $request->input('skills', 'N/A');
            $candidateData['experience'] = $request->input('experience', 0);
            $candidateData['qualification'] = $request->input('qualification', 'N/A');
        }

        $candidate = Candidate::create($candidateData);
        ActivityLog::log('Created Candidate', "Candidate: {$candidate->full_name} via {$candidate->source}");

        return redirect()->route('candidates.index')->with('success', 'Candidate registered successfully.');
    }

    public function edit(Candidate $candidate)
    {
        return view('candidates.edit', compact('candidate'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,txt,docx|max:2048',
            'skills' => 'nullable|string',
            'experience' => 'nullable|integer',
            'qualification' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:Applied,Shortlisted,HR Interview,Technical Interview,Final Interview,Offer Sent,Accepted,Rejected,Hired',
        ]);

        $candidateData = $request->except('resume');

        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $candidateData['resume'] = $path;

            // Trigger Resume Parser Helper
            $parsed = ResumeParser::parse(storage_path('app/public/' . $path));
            $candidateData['skills'] = $parsed['skills'];
            $candidateData['experience'] = $parsed['experience'];
            $candidateData['qualification'] = $parsed['qualification'];
        }

        $candidate->update($candidateData);
        ActivityLog::log('Updated Candidate', "Candidate: {$candidate->full_name}");

        return redirect()->route('candidates.index')->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        ActivityLog::log('Deleted Candidate', "Candidate: {$candidate->full_name}");
        $candidate->delete();
        return redirect()->route('candidates.index')->with('success', 'Candidate record deleted successfully.');
    }
}
