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
        $categories = AssetCategory::orderBy('name')->get();
        return view('assets.index', compact('assets', 'categories'));
    }

    public function create()
    {
        $categories = AssetCategory::all();
        return view('assets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if ($request->has('warranty_expiry') && empty($request->warranty_expiry)) {
            $request->merge(['warranty_expiry' => null]);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'asset_category_id' => 'required|exists:asset_categories,id',
            'serial_number'     => 'required|string|max:255|unique:assets,serial_number',
            'cost'              => 'required|numeric|min:0',
            'purchase_date'     => 'required|date',
            'warranty_expiry'   => 'nullable|date|after_or_equal:purchase_date',
            'status'            => 'required|in:Available,Assigned,Maintenance,Lost',
        ], [
            'serial_number.unique'        => 'The Serial / Asset ID has already been registered in the system.',
            'warranty_expiry.after_or_equal' => 'The Warranty Expiry date must be equal to or after the Purchase Date.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $asset = Asset::create($validator->validated());
        $asset->load('category');
        ActivityLog::log('Created Asset', "Asset: {$asset->name} (SN: {$asset->serial_number})");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Asset created successfully.',
                'asset'   => [
                    'id'              => $asset->id,
                    'name'            => $asset->name,
                    'category_name'   => $asset->category->name ?? 'N/A',
                    'serial_number'   => $asset->serial_number,
                    'purchase_date'   => date('M d, Y', strtotime($asset->purchase_date)),
                    'warranty_expiry' => $asset->warranty_expiry ? date('M d, Y', strtotime($asset->warranty_expiry)) : null,
                    'is_expired'      => $asset->warranty_expiry ? (strtotime($asset->warranty_expiry) < time()) : false,
                    'cost'            => number_format($asset->cost, 2),
                    'status'          => $asset->status,
                    'show_url'        => route('assets.show', $asset->id),
                    'edit_url'        => route('assets.edit', $asset->id),
                    'destroy_url'     => route('assets.destroy', $asset->id),
                ]
            ]);
        }

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
