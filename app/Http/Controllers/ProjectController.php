<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function clientIndex()
    {
        $client = auth()->user()->client;
        $projects = Project::with(['client', 'manager'])
            ->where('client_id', $client->id)
            ->latest()->paginate(10);
        $search = null;
        return view('projects.index', compact('projects', 'search'));
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $projects = Project::with(['client', 'manager'])

            ->when($search, function ($query) use ($search) {

                $query->where('project_name', 'like', "%{$search}%")
                      ->orWhere('project_code', 'like', "%{$search}%");

            })

            ->latest()

            ->paginate(10)

            ->withQueryString();

        return view('projects.index', compact(
            'projects',
            'search'
        ));
    }

    public function create()
    {
        $clients = Client::all();
        $employees = Employee::all();

        return view('projects.create', compact('clients', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_code'       => 'required|unique:projects',
            'project_name'       => 'required|max:100|unique:projects,project_name|regex:/^[a-zA-Z0-9\s]+$/',
            'client_id'          => 'required|exists:clients,id',
            'project_manager_id' => 'required|exists:employees,id',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date|after_or_equal:today',
            'budget'             => 'required|numeric',
            'status'             => 'required',
            'description'        => 'nullable',
        ], [
            'project_name.regex' => 'The project name may only contain letters, numbers, and spaces.',
            'project_name.unique' => 'The project name has already been taken.',
            'end_date.after_or_equal' => 'The deadline date cannot be in the past.',
        ]);

        Project::create($request->all());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $clients = Client::all();
        $employees = Employee::all();

        return view('projects.edit', compact(
            'project',
            'clients',
            'employees'
        ));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'project_code'       => 'required|unique:projects,project_code,' . $project->id,
            'project_name'       => 'required|max:100|unique:projects,project_name,' . $project->id . '|regex:/^[a-zA-Z0-9\s]+$/',
            'client_id'          => 'required|exists:clients,id',
            'project_manager_id' => 'required|exists:employees,id',
            'start_date'         => 'required|date',
            'end_date'           => 'nullable|date|after_or_equal:start_date|after_or_equal:today',
            'budget'             => 'required|numeric',
            'status'             => 'required',
            'description'        => 'nullable',
        ], [
            'project_name.regex' => 'The project name may only contain letters, numbers, and spaces.',
            'project_name.unique' => 'The project name has already been taken.',
            'end_date.after_or_equal' => 'The deadline date cannot be in the past.',
        ]);

        $project->update($request->all());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'manager', 'tasks.employee']);
        return view('projects.show', compact('project'));
    }
}