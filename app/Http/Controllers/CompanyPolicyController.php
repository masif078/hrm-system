<?php

namespace App\Http\Controllers;

use App\Models\CompanyPolicy;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CompanyPolicyController extends Controller
{
    public function index()
    {
        $policies = CompanyPolicy::latest()->get();
        return view('company-policies.index', compact('policies'));
    }

    public function create()
    {
        return view('company-policies.create');
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'type'    => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $policy = CompanyPolicy::create($validator->validated());
        ActivityLog::log('Created Company Policy', "Policy: {$policy->title}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Company policy created successfully.',
                'policy'  => [
                    'id'          => $policy->id,
                    'title'       => $policy->title,
                    'type'        => $policy->type,
                    'content'     => $policy->content,
                    'excerpt'     => Str::limit($policy->content, 200),
                    'edit_url'    => route('company-policies.edit', $policy->id),
                    'update_url'  => route('company-policies.update', $policy->id),
                    'destroy_url' => route('company-policies.destroy', $policy->id),
                ]
            ]);
        }

        return redirect()->route('company-policies.index')->with('success', 'Company policy created successfully.');
    }

    public function edit(CompanyPolicy $companyPolicy)
    {
        return view('company-policies.edit', compact('companyPolicy'));
    }

    public function update(Request $request, CompanyPolicy $companyPolicy)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'type'    => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $companyPolicy->update($validator->validated());
        ActivityLog::log('Updated Company Policy', "Policy: {$companyPolicy->title}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Company policy updated successfully.',
                'policy'  => [
                    'id'          => $companyPolicy->id,
                    'title'       => $companyPolicy->title,
                    'type'        => $companyPolicy->type,
                    'content'     => $companyPolicy->content,
                    'excerpt'     => Str::limit($companyPolicy->content, 200),
                    'edit_url'    => route('company-policies.edit', $companyPolicy->id),
                    'update_url'  => route('company-policies.update', $companyPolicy->id),
                    'destroy_url' => route('company-policies.destroy', $companyPolicy->id),
                ]
            ]);
        }

        return redirect()->route('company-policies.index')->with('success', 'Company policy updated successfully.');
    }

    public function destroy(CompanyPolicy $companyPolicy)
    {
        ActivityLog::log('Deleted Company Policy', "Policy: {$companyPolicy->title}");
        $companyPolicy->delete();
        return redirect()->route('company-policies.index')->with('success', 'Company policy deleted successfully.');
    }
}
