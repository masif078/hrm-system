<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appraisal;
use App\Models\Employee;
use App\Models\PerformanceReview;
use App\Models\Designation;

class AppraisalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            $query = Appraisal::with(['employee', 'performanceReview', 'previousDesignation', 'newDesignation']);
            
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $appraisals = $query->latest()->paginate(10);
            $employees = Employee::orderBy('first_name')->get();
            return view('appraisals.index', compact('appraisals', 'employees'));
        } else {
            $employee = $user->employee;
            if (!$employee) {
                return redirect()->back()->with('error', 'Employee profile not found.');
            }

            $appraisals = Appraisal::with(['performanceReview', 'previousDesignation', 'newDesignation'])
                ->where('employee_id', $employee->id)
                ->latest()
                ->paginate(10);
            return view('appraisals.index', compact('appraisals'));
        }
    }

    public function create()
    {
        $employees = Employee::all();
        $designations = Designation::all();
        $reviews = PerformanceReview::where('status', 'Completed')->latest()->get();
        return view('appraisals.create', compact('employees', 'designations', 'reviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'performance_review_id' => 'nullable|exists:performance_reviews,id',
            'rating_class' => 'required|string|max:255',
            'action_type' => 'required|in:Increment,Promotion,Both,None',
            'previous_salary' => 'required|numeric|min:0',
            'new_salary' => 'required|numeric|min:0',
            'previous_designation_id' => 'nullable|exists:designations,id',
            'new_designation_id' => 'nullable|exists:designations,id',
            'effective_date' => 'required|date',
            'status' => 'required|in:Draft,Approved,Rejected',
        ]);

        $appraisal = Appraisal::create($validated);

        if ($appraisal->status === 'Approved') {
            $this->applyAppraisalEffects($appraisal);
        }

        return redirect()->route('appraisals.index')
            ->with('success', 'Appraisal created successfully.');
    }

    public function edit(string $id)
    {
        $appraisal = Appraisal::findOrFail($id);
        $employees = Employee::all();
        $designations = Designation::all();
        $reviews = PerformanceReview::where('status', 'Completed')->latest()->get();
        return view('appraisals.edit', compact('appraisal', 'employees', 'designations', 'reviews'));
    }

    public function update(Request $request, string $id)
    {
        $appraisal = Appraisal::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'performance_review_id' => 'nullable|exists:performance_reviews,id',
            'rating_class' => 'required|string|max:255',
            'action_type' => 'required|in:Increment,Promotion,Both,None',
            'previous_salary' => 'required|numeric|min:0',
            'new_salary' => 'required|numeric|min:0',
            'previous_designation_id' => 'nullable|exists:designations,id',
            'new_designation_id' => 'nullable|exists:designations,id',
            'effective_date' => 'required|date',
            'status' => 'required|in:Draft,Approved,Rejected',
        ]);

        $oldStatus = $appraisal->status;
        $appraisal->update($validated);

        if ($oldStatus !== 'Approved' && $appraisal->status === 'Approved') {
            $this->applyAppraisalEffects($appraisal);
        }

        return redirect()->route('appraisals.index')
            ->with('success', 'Appraisal updated successfully.');
    }

    public function destroy(string $id)
    {
        $appraisal = Appraisal::findOrFail($id);
        $appraisal->delete();

        return redirect()->route('appraisals.index')
            ->with('success', 'Appraisal deleted successfully.');
    }

    private function applyAppraisalEffects(Appraisal $appraisal)
    {
        $employee = Employee::find($appraisal->employee_id);
        if (!$employee) return;

        if ($appraisal->action_type === 'Increment' || $appraisal->action_type === 'Both') {
            $employee->salary = $appraisal->new_salary;
        }

        if ($appraisal->action_type === 'Promotion' || $appraisal->action_type === 'Both') {
            if ($appraisal->new_designation_id) {
                $employee->designation_id = $appraisal->new_designation_id;
            }
        }

        $employee->save();
    }
}
