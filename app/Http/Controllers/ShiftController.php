<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::latest()->paginate(10);
        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shifts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'start_time'            => 'required',
            'end_time'              => 'required',
            'late_mark_after'       => 'required',
            'early_checkout_before' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $shift = Shift::create($validator->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Shift created successfully.',
                'shift'   => [
                    'id'                              => $shift->id,
                    'name'                            => $shift->name,
                    'formatted_start_time'            => date('h:i A', strtotime($shift->start_time)),
                    'formatted_end_time'              => date('h:i A', strtotime($shift->end_time)),
                    'formatted_late_mark_after'       => date('h:i A', strtotime($shift->late_mark_after)),
                    'formatted_early_checkout_before' => date('h:i A', strtotime($shift->early_checkout_before)),
                    'edit_url'                        => route('shifts.edit', $shift->id),
                    'destroy_url'                     => route('shifts.destroy', $shift->id),
                ]
            ]);
        }

        return redirect()->route('shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shift = Shift::with('employees')->findOrFail($id);
        return view('shifts.show', compact('shift'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shift = Shift::findOrFail($id);
        return view('shifts.edit', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shift = Shift::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_mark_after' => 'required',
            'early_checkout_before' => 'required',
        ]);

        $shift->update($validated);

        return redirect()->route('shifts.index')
            ->with('success', 'Shift updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return redirect()->route('shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }
}
