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
        $employees = Employee::orderBy('first_name')->get();
        
        if ($user->role === 'admin') {
            $query = PerformanceReview::with(['employee', 'reviewer']);
            
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }
            if ($request->filled('review_type')) {
                $query->where('review_type', $request->review_type);
            }

            $reviews = $query->latest()->paginate(10);
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
            return view('performance-reviews.index', compact('reviews', 'employees'));
        }
    }

    public function create()
    {
        $employees = Employee::all();
        return view('performance-reviews.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'employee_id'  => 'required|exists:employees,id',
            'reviewer_id'  => 'nullable|exists:employees,id',
            'review_type'  => 'required|in:Monthly,Quarterly,Annual',
            'period'       => 'required|string|max:255',
            'rating'       => 'required|numeric|min:1|max:5',
            'strengths'    => 'nullable|string',
            'improvements' => 'nullable|string',
            'review_date'  => 'required|date',
            'status'       => 'required|in:Pending,Completed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (empty($validated['reviewer_id'])) {
            $reviewer = auth()->user()->employee;
            $validated['reviewer_id'] = $reviewer ? $reviewer->id : null;
        }

        $review = PerformanceReview::create($validated);
        $review->load(['employee', 'reviewer']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Performance review evaluation created successfully.',
                'review'  => [
                    'id'            => $review->id,
                    'employee_name' => $review->employee ? ($review->employee->first_name . ' ' . $review->employee->last_name) : 'N/A',
                    'employee_code' => $review->employee ? ($review->employee->employee_id ?? 'EMP-'.$review->employee->id) : '',
                    'reviewer_name' => $review->reviewer ? ($review->reviewer->first_name . ' ' . $review->reviewer->last_name) : 'N/A',
                    'review_type'   => $review->review_type,
                    'period'        => $review->period,
                    'rating'        => number_format($review->rating, 2),
                    'review_date'   => \Carbon\Carbon::parse($review->review_date)->format('M d, Y'),
                    'status'        => $review->status,
                    'show_url'      => route('performance-reviews.show', $review->id),
                    'edit_url'      => route('performance-reviews.edit', $review->id),
                    'destroy_url'   => route('performance-reviews.destroy', $review->id),
                ]
            ]);
        }

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
