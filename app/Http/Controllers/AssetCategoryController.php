<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AssetCategoryController extends Controller
{
    public function index()
    {
        $categories = AssetCategory::latest()->get();
        return view('asset-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('asset-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories,name',
            'description' => 'nullable|string',
        ]);

        $category = AssetCategory::create($validated);
        ActivityLog::log('Created Asset Category', "Category: {$category->name}");

        return redirect()->route('asset-categories.index')->with('success', 'Asset category created successfully.');
    }

    public function edit(AssetCategory $assetCategory)
    {
        return view('asset-categories.edit', compact('assetCategory'));
    }

    public function update(Request $request, AssetCategory $assetCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories,name,' . $assetCategory->id,
            'description' => 'nullable|string',
        ]);

        $assetCategory->update($validated);
        ActivityLog::log('Updated Asset Category', "Category: {$assetCategory->name}");

        return redirect()->route('asset-categories.index')->with('success', 'Asset category updated successfully.');
    }

    public function destroy(AssetCategory $assetCategory)
    {
        ActivityLog::log('Deleted Asset Category', "Category: {$assetCategory->name}");
        $assetCategory->delete();
        return redirect()->route('asset-categories.index')->with('success', 'Asset category deleted successfully.');
    }
}
