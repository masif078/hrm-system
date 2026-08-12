<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of leaves with search, filters and statistics.
     */
    public function employeeIndex()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('employee.dashboard')
                ->with('error', 'No employee profile linked to your account. Please contact admin.');
        }

        $leaves = Leave::with('employee')
            ->where('employee_id', $employee->id)
            ->latest()->paginate(10);
        $totalLeaves = $leaves->total();
        $pendingLeaves = Leave::where('employee_id', $employee->id)->where('status', 'Pending')->count();
        $approvedLeaves = Leave::where('employee_id', $employee->id)->where('status', 'Approved')->count();
        $rejectedLeaves = Leave::where('employee_id', $employee->id)->where('status', 'Rejected')->count();
        $monthlyLeaves = Leave::where('employee_id', $employee->id)->whereMonth('start_date', now()->month)->count();
        $recentLeaves = $leaves->take(5);
        return view('leaves.index', compact('leaves', 'totalLeaves', 'pendingLeaves', 'approvedLeaves', 'rejectedLeaves', 'monthlyLeaves', 'recentLeaves'));
    }

    public function employeeCreate()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('employee.dashboard')
                ->with('error', 'No employee profile linked to your account. Please contact admin.');
        }

        $employees = collect([$employee]);
        return view('leaves.create', compact('employees'));
    }

    public function employeeStore(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('employee.dashboard')
                ->with('error', 'No employee profile linked to your account. Please contact admin.');
        }

        $validated = $request->validate([
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string',
        ]);
        $validated['employee_id'] = $employee->id;
        $validated['status'] = 'Pending';

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $requestedDays = $startDate->diffInDays($endDate) + 1;

        // Auto allocate 14 days default leave balance if not initialized
        $balance = LeaveBalance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'leave_type'  => $validated['leave_type'],
            ],
            [
                'allocated'   => 14,
                'used'        => 0,
            ]
        );

        $remaining = $balance->allocated - $balance->used;
        if ($requestedDays > $remaining) {
            return redirect()->back()->withInput()->with('error', 'Insufficient leave balance. You requested ' . $requestedDays . ' days but only have ' . $remaining . ' days remaining.');
        }

        $leave = Leave::create($validated);
        
        // 1. Notify Admin users via email
        $admins = \App\Models\User::where('role', 'admin')->get();
        if ($admins->count() > 0) {
            try {
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\LeaveAppliedNotification($leave));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mail error notifying admin on leave apply: ' . $e->getMessage());
            }
        }

        // 2. Notify Employee via confirmation email
        $employeeUser = auth()->user();
        if ($employeeUser && $employeeUser->email) {
            try {
                $employeeUser->notify(new \App\Notifications\LeaveAppliedNotification($leave));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mail error notifying employee on leave apply: ' . $e->getMessage());
            }
        }

        return redirect()->route('employee.leaves.index')->with('success', 'Leave request submitted successfully and notification email sent.');
    }

    public function index(Request $request)
    {
        // Main leaves query
        $query = Leave::with('employee');

        // Search by employee first name or last name
        if ($request->filled('search')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        // Filter by start date
        if ($request->filled('date')) {
            $query->whereDate('start_date', $request->date);
        }

        // Get filtered leaves with pagination
        $leaves = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Dashboard Statistics
        $totalLeaves = Leave::count();

        $pendingLeaves = Leave::where('status', 'Pending')->count();

        $approvedLeaves = Leave::where('status', 'Approved')->count();

        $rejectedLeaves = Leave::where('status', 'Rejected')->count();

        // Current month's leaves
        $monthlyLeaves = Leave::whereMonth(
            'start_date',
            now()->month
        )->count();

        // Recent 5 leaves
        $recentLeaves = Leave::with('employee')
            ->latest()
            ->take(5)
            ->get();

        $employees = Employee::orderBy('first_name')->get();

        // Return view with all data
        return view('leaves.index', compact(
            'leaves',
            'totalLeaves',
            'pendingLeaves',
            'approvedLeaves',
            'rejectedLeaves',
            'monthlyLeaves',
            'recentLeaves',
            'employees'
        ));
    }

    /**
     * Show the form for creating a new leave.
     */
    public function create()
    {
        $employees = Employee::all();

        return view('leaves.create', compact('employees'));
    }

    /**
     * Store a newly created leave.
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'leave_type'  => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'nullable|string',
            'status'      => 'required|in:Pending,Approved,Rejected',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $leave = Leave::create($validator->validated());
        $leave->load('employee');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Leave request created successfully.',
                'leave'   => [
                    'id'            => $leave->id,
                    'employee_name' => ($leave->employee->first_name ?? '') . ' ' . ($leave->employee->last_name ?? ''),
                    'leave_type'    => $leave->leave_type,
                    'start_date'    => $leave->start_date,
                    'end_date'      => $leave->end_date,
                    'reason'        => $leave->reason,
                    'status'        => $leave->status,
                    'show_url'      => route('leaves.show', $leave),
                    'edit_url'      => route('leaves.edit', $leave),
                    'destroy_url'   => route('leaves.destroy', $leave),
                    'approve_url'   => route('leaves.approve', $leave),
                    'reject_url'    => route('leaves.reject', $leave),
                ]
            ]);
        }

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave created successfully.');
    }

    /**
     * Display the specified leave.
     */
    public function show(Leave $leave)
    {
        $leave->load('employee');

        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified leave.
     */
    public function edit(Leave $leave)
    {
        $employees = Employee::all();

        return view('leaves.edit', compact(
            'leave',
            'employees'
        ));
    }

    /**
     * Update the specified leave.
     */
    public function update(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type'  => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'nullable|string',
            'status'      => 'required|in:Pending,Approved,Rejected',
        ]);

        $oldStatus = $leave->status;
        $leave->update($validated);

        if ($oldStatus !== $leave->status && in_array($leave->status, ['Approved', 'Rejected'])) {
            $employeeUser = $leave->employee->user;
            if ($employeeUser) {
                $employeeUser->notify(new \App\Notifications\LeaveStatusNotification($leave));
            }
        }

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave updated successfully.');
    }

    /**
     * Remove the specified leave.
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave deleted successfully.');
    }

    /**
     * Approve the specified leave.
     */
    public function approve(Leave $leave)
    {
        $startDate = \Carbon\Carbon::parse($leave->start_date);
        $endDate = \Carbon\Carbon::parse($leave->end_date);
        $leaveDays = $startDate->diffInDays($endDate) + 1;

        $balance = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type', $leave->leave_type)
            ->first();

        if ($balance) {
            $balance->increment('used', $leaveDays);
        }

        $leave->update([
            'status' => 'Approved'
        ]);

        $employeeUser = $leave->employee->user;
        if ($employeeUser) {
            $employeeUser->notify(new \App\Notifications\LeaveStatusNotification($leave));
        }

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave approved successfully.');
    }

    /**
     * Reject the specified leave.
     */
    public function reject(Leave $leave)
    {
        $leave->update([
            'status' => 'Rejected'
        ]);

        $employeeUser = $leave->employee->user;
        if ($employeeUser) {
            $employeeUser->notify(new \App\Notifications\LeaveStatusNotification($leave));
        }

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave rejected successfully.');
    }
}