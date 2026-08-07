<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::orderBy('date')->paginate(10);
        return view('holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('holidays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date',
            'type' => 'required|string|max:255',
        ]);

        Holiday::create($validated);

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $holiday = Holiday::findOrFail($id);
        return view('holidays.show', compact('holiday'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $holiday = Holiday::findOrFail($id);
        return view('holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $holiday = Holiday::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date,' . $id,
            'type' => 'required|string|max:255',
        ]);

        $holiday->update($validated);

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday deleted successfully.');
    }
}
