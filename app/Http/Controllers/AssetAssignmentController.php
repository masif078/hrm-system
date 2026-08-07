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
        return view('asset-assignments.index', compact('assignments'));
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
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'employee_id' => 'required|exists:employees,id',
            'assign_date' => 'required|date',
            'condition_upon_assign' => 'required|string|max:255',
        ]);

        $assignment = AssetAssignment::create($validated + ['status' => 'Assigned']);

        // Update asset status to Assigned
        $asset = Asset::find($request->asset_id);
        $asset->update(['status' => 'Assigned']);

        ActivityLog::log('Assigned Asset', "Asset: {$asset->name} assigned to Employee ID: {$request->employee_id}");

        return redirect()->route('asset-assignments.index')->with('success', 'Asset checked out successfully.');
    }

    public function edit(AssetAssignment $assetAssignment)
    {
        return view('asset-assignments.edit', compact('assetAssignment'));
    }

    public function update(Request $request, AssetAssignment $assetAssignment)
    {
        $validated = $request->validate([
            'return_date' => 'required|date|after_or_equal:assign_date',
            'condition_upon_return' => 'required|string|max:255',
            'status' => 'required|in:Returned,Lost',
        ]);

        $assetAssignment->update($validated);

        // Update asset status based on assignment return status
        $asset = $assetAssignment->asset;
        if ($request->status === 'Returned') {
            // If returned damaged, move to maintenance
            if (Str::contains(strtolower($request->condition_upon_return), ['damage', 'broken', 'repair', 'faulty', ' खराब'])) {
                $asset->update(['status' => 'Maintenance']);
            } else {
                $asset->update(['status' => 'Available']);
            }
        } elseif ($request->status === 'Lost') {
            $asset->update(['status' => 'Lost']);
        }

        ActivityLog::log('Returned Asset', "Asset: {$asset->name} check-in recorded");

        return redirect()->route('asset-assignments.index')->with('success', 'Asset check-in registered successfully.');
    }
}
