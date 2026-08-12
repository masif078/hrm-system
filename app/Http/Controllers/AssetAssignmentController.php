<?php

namespace App\Http\Controllers;

use App\Models\AssetAssignment;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssetAssignmentController extends Controller
{
    public function index()
    {
        $assignments = AssetAssignment::with(['asset', 'employee'])->latest()->get();
        $assets = Asset::where('status', 'Available')->orderBy('name')->get();
        $employees = Employee::orderBy('first_name')->get();
        return view('asset-assignments.index', compact('assignments', 'assets', 'employees'));
    }

    public function create()
    {
        // Select assets that are "Available"
        $assets = Asset::where('status', 'Available')->get();
        $employees = Employee::all();
        return view('asset-assignments.create', compact('assets', 'employees'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'asset_id'              => 'required|exists:assets,id',
            'employee_id'           => 'required|exists:employees,id',
            'assign_date'           => 'required|date',
            'condition_upon_assign' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $assignment = AssetAssignment::create($validator->validated() + ['status' => 'Assigned']);

        // Update asset status to Assigned
        $asset = Asset::find($request->asset_id);
        $asset->update(['status' => 'Assigned']);

        ActivityLog::log('Assigned Asset', "Asset: {$asset->name} assigned to Employee ID: {$request->employee_id}");

        $assignment->load(['asset', 'employee']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Asset checked out successfully.',
                'assignment' => [
                    'id'                    => $assignment->id,
                    'asset_name'            => $assignment->asset->name ?? 'N/A',
                    'serial_number'         => $assignment->asset->serial_number ?? 'N/A',
                    'employee_name'         => ($assignment->employee->first_name ?? '') . ' ' . ($assignment->employee->last_name ?? ''),
                    'assign_date'           => date('M d, Y', strtotime($assignment->assign_date)),
                    'condition_upon_assign' => $assignment->condition_upon_assign,
                    'return_date'           => null,
                    'condition_upon_return' => null,
                    'status'                => $assignment->status,
                    'update_url'            => route('asset-assignments.update', $assignment->id),
                ]
            ]);
        }

        return redirect()->route('asset-assignments.index')->with('success', 'Asset checked out successfully.');
    }

    public function edit(AssetAssignment $assetAssignment)
    {
        return view('asset-assignments.edit', compact('assetAssignment'));
    }

    public function update(Request $request, AssetAssignment $assetAssignment)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'return_date'           => 'required|date|after_or_equal:assign_date',
            'condition_upon_return' => 'required|string|max:255',
            'status'                => 'required|in:Returned,Lost',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $assetAssignment->update($validator->validated());

        // Update asset status based on assignment return status
        $asset = $assetAssignment->asset;
        if ($request->status === 'Returned') {
            // If returned damaged, move to maintenance
            if (Str::contains(strtolower($request->condition_upon_return), ['damage', 'broken', 'repair', 'faulty'])) {
                $asset->update(['status' => 'Maintenance']);
            } else {
                $asset->update(['status' => 'Available']);
            }
        } elseif ($request->status === 'Lost') {
            $asset->update(['status' => 'Lost']);
        }

        ActivityLog::log('Returned Asset', "Asset: {$asset->name} check-in recorded");

        $assetAssignment->load(['asset', 'employee']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Asset check-in registered successfully.',
                'assignment' => [
                    'id'                    => $assetAssignment->id,
                    'asset_name'            => $assetAssignment->asset->name ?? 'N/A',
                    'serial_number'         => $assetAssignment->asset->serial_number ?? 'N/A',
                    'employee_name'         => ($assetAssignment->employee->first_name ?? '') . ' ' . ($assetAssignment->employee->last_name ?? ''),
                    'assign_date'           => date('M d, Y', strtotime($assetAssignment->assign_date)),
                    'condition_upon_assign' => $assetAssignment->condition_upon_assign,
                    'return_date'           => date('M d, Y', strtotime($assetAssignment->return_date)),
                    'condition_upon_return' => $assetAssignment->condition_upon_return,
                    'status'                => $assetAssignment->status,
                ]
            ]);
        }

        return redirect()->route('asset-assignments.index')->with('success', 'Asset check-in registered successfully.');
    }
}
