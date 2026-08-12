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
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'        => 'required|string|max:255|unique:asset_categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = AssetCategory::create($validator->validated());
        ActivityLog::log('Created Asset Category', "Category: {$category->name}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Asset category created successfully.',
                'category' => [
                    'id'          => $category->id,
                    'name'        => $category->name,
                    'description' => $category->description ?: 'No description',
                    'created_at'  => $category->created_at ? $category->created_at->format('M d, Y') : date('M d, Y'),
                    'edit_url'    => route('asset-categories.edit', $category->id),
                    'destroy_url' => route('asset-categories.destroy', $category->id),
                ]
            ]);
        }

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
