<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetMaintenanceLog;
use App\Models\ActivityLog;
use App\Helpers\QrCodeGenerator;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with('category')->latest()->get();
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        $categories = AssetCategory::all();
        return view('assets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_category_id' => 'required|exists:asset_categories,id',
            'serial_number' => 'required|string|max:255|unique:assets,serial_number',
            'cost' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'warranty_expiry' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'required|in:Available,Assigned,Maintenance,Lost',
        ]);

        $asset = Asset::create($validated);
        ActivityLog::log('Created Asset', "Asset: {$asset->name} (SN: {$asset->serial_number})");

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'assignments.employee', 'maintenanceLogs']);
        
        // Generate offline QR Code SVG
        $qrPayload = route('assets.show', $asset->id);
        $qrCodeSvg = QrCodeGenerator::generateSvg($qrPayload);

        return view('assets.show', compact('asset', 'qrCodeSvg'));
    }

    public function edit(Asset $asset)
    {
        $categories = AssetCategory::all();
        return view('assets.edit', compact('asset', 'categories'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_category_id' => 'required|exists:asset_categories,id',
            'serial_number' => 'required|string|max:255|unique:assets,serial_number,' . $asset->id,
            'cost' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'warranty_expiry' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'required|in:Available,Assigned,Maintenance,Lost',
        ]);

        $asset->update($validated);
        ActivityLog::log('Updated Asset', "Asset: {$asset->name}");

        return redirect()->route('assets.index')->with('success', 'Asset details updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        ActivityLog::log('Deleted Asset', "Asset: {$asset->name}");
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }

    public function addMaintenanceLog(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'repair_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $log = $asset->maintenanceLogs()->create($validated);
        $asset->update(['status' => 'Maintenance']);

        ActivityLog::log('Logged Asset Maintenance', "Asset: {$asset->name}, Cost: {$request->cost}");

        return redirect()->route('assets.show', $asset->id)->with('success', 'Asset maintenance log added and asset status set to Maintenance.');
    }
}
