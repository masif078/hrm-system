<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerformanceReview;
use App\Models\Employee;

class PerformanceReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            $query = PerformanceReview::with(['employee', 'reviewer']);
            
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }
            if ($request->filled('review_type')) {
                $query->where('review_type', $request->review_type);
            }

            $reviews = $query->latest()->paginate(10);
            $employees = Employee::orderBy('first_name')->get();
            return view('performance-reviews.index', compact('reviews', 'employees'));
        } else {
            $employee = $user->employee;
            if (!$employee) {
                return redirect()->back()->with('error', 'Employee profile not found.');
            }

            $reviews = PerformanceReview::with('reviewer')
                ->where('employee_id', $employee->id)
                ->latest()
                ->paginate(10);
            return view('performance-reviews.index', compact('reviews'));
        }
    }

    public function create()
    {
        $employees = Employee::all();
        return view('performance-reviews.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_type' => 'required|in:Monthly,Quarterly,Annual',
            'period' => 'required|string|max:255',
            'rating' => 'required|numeric|min:1|max:5',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'review_date' => 'required|date',
            'status' => 'required|in:Pending,Completed',
        ]);

        $reviewer = auth()->user()->employee;
        $validated['reviewer_id'] = $reviewer ? $reviewer->id : null;

        PerformanceReview::create($validated);

        return redirect()->route('performance-reviews.index')
            ->with('success', 'Performance review created successfully.');
    }

    public function show(string $id)
    {
        $review = PerformanceReview::with(['employee', 'reviewer'])->findOrFail($id);
        
        $user = auth()->user();
        if ($user->role !== 'admin' && $review->employee_id !== $user->employee->id) {
            abort(403);
        }

        return view('performance-reviews.show', compact('review'));
    }

    public function edit(string $id)
    {
        $review = PerformanceReview::findOrFail($id);
        $employees = Employee::all();
        return view('performance-reviews.edit', compact('review', 'employees'));
    }

    public function update(Request $request, string $id)
    {
        $review = PerformanceReview::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_type' => 'required|in:Monthly,Quarterly,Annual',
            'period' => 'required|string|max:255',
            'rating' => 'required|numeric|min:1|max:5',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'review_date' => 'required|date',
            'status' => 'required|in:Pending,Completed',
        ]);

        $review->update($validated);

        return redirect()->route('performance-reviews.index')
            ->with('success', 'Performance review updated successfully.');
    }

    public function destroy(string $id)
    {
        $review = PerformanceReview::findOrFail($id);
        $review->delete();

        return redirect()->route('performance-reviews.index')
            ->with('success', 'Performance review deleted successfully.');
    }
}
