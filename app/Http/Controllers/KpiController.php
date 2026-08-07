<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kpi;
use App\Models\KpiAssignment;
use App\Models\KpiScore;
use App\Models\Employee;

class KpiController extends Controller
{
    public function index()
    {
        $kpis = Kpi::latest()->paginate(10);
        $assignments = KpiAssignment::with('employee', 'kpi')->latest()->paginate(10, ['*'], 'assignments_page');
        $scores = KpiScore::with('employee', 'kpi')->latest()->paginate(10, ['*'], 'scores_page');
        
        return view('kpis.index', compact('kpis', 'assignments', 'scores'));
    }

    public function create()
    {
        return view('kpis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'required|integer',
            'unit' => 'required|string|max:10',
        ]);

        Kpi::create($validated);

        return redirect()->route('kpis.index')->with('success', 'KPI created successfully.');
    }

    public function edit(Kpi $kpi)
    {
        return view('kpis.edit', compact('kpi'));
    }

    public function update(Request $request, Kpi $kpi)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'required|integer',
            'unit' => 'required|string|max:10',
        ]);

        $kpi->update($validated);

        return redirect()->route('kpis.index')->with('success', 'KPI updated successfully.');
    }

    public function destroy(Kpi $kpi)
    {
        $kpi->delete();
        return redirect()->route('kpis.index')->with('success', 'KPI deleted successfully.');
    }

    public function showAssign()
    {
        $employees = Employee::all();
        $kpis = Kpi::all();
        return view('kpis.assign', compact('employees', 'kpis'));
    }

    public function storeAssign(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'kpi_id' => 'required|exists:kpis,id',
            'assigned_date' => 'required|date',
        ]);

        // check duplicate
        $exists = KpiAssignment::where('employee_id', $validated['employee_id'])
            ->where('kpi_id', $validated['kpi_id'])
            ->where('status', 'Active')
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['kpi_id' => 'This KPI is already assigned and active for this employee.']);
        }

        KpiAssignment::create($validated);

        return redirect()->route('kpis.index')->with('success', 'KPI assigned successfully.');
    }

    public function showScore()
    {
        $employees = Employee::all();
        $kpis = Kpi::all();
        return view('kpis.score', compact('employees', 'kpis'));
    }

    public function storeScore(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'kpi_id' => 'required|exists:kpis,id',
            'score' => 'required|numeric|min:0',
            'period_month' => 'required|integer|min:1|max:12',
            'period_year' => 'required|integer',
            'comments' => 'nullable|string',
        ]);

        // check if KPI is assigned to this employee first
        $isAssigned = KpiAssignment::where('employee_id', $validated['employee_id'])
            ->where('kpi_id', $validated['kpi_id'])
            ->exists();

        if (!$isAssigned) {
            return redirect()->back()->withErrors(['kpi_id' => 'This KPI has not been assigned to the selected employee.']);
        }

        KpiScore::create($validated);

        return redirect()->route('kpis.index')->with('success', 'KPI score recorded successfully.');
    }
}
