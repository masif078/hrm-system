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
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'full_name'     => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'experience'    => 'nullable|numeric|min:0|max:50',
            'qualification' => 'nullable|string|max:255',
            'source'        => 'nullable|string|max:255',
            'status'        => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $validated['resume'] = $path;
        }

        $candidate = Candidate::create($validated);
        ActivityLog::log('Created Candidate', "Candidate: {$candidate->full_name} via {$candidate->source}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Candidate registered successfully.',
                'candidate' => [
                    'id'            => $candidate->id,
                    'full_name'     => $candidate->full_name,
                    'email'         => $candidate->email,
                    'phone'         => $candidate->phone ?: 'N/A',
                    'experience'    => ($candidate->experience ?? 0) . ' years',
                    'qualification' => $candidate->qualification ?: 'N/A',
                    'source'        => $candidate->source ?: 'N/A',
                    'status'        => $candidate->status,
                    'resume_url'    => $candidate->resume ? asset('storage/' . $candidate->resume) : null,
                    'edit_url'      => route('candidates.edit', $candidate->id),
                    'destroy_url'   => route('candidates.destroy', $candidate->id),
                ]
            ]);
        }

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
