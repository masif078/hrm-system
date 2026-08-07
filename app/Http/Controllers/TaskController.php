<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function clientIndex()
    {
        $client = auth()->user()->client;

        if (!$client) {
            return redirect()->route('client.dashboard')
                ->with('error', 'No client profile linked to your account. Please contact admin.');
        }

        $projectIds = $client->projects()->pluck('id');
        $tasks = Task::with(['project', 'employee'])
            ->whereIn('project_id', $projectIds)
            ->latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    public function employeeIndex()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('employee.dashboard')
                ->with('error', 'No employee profile linked to your account. Please contact admin.');
        }

        $tasks = Task::with(['project', 'employee'])
            ->where('employee_id', $employee->id)
            ->latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    public function index(Request $request)
    {
        $query = Task::with(['project', 'employee']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('employee_id')) {
            if ($request->employee_id === 'unassigned') {
                $query->whereNull('employee_id');
            } else {
                $query->where('employee_id', $request->employee_id);
            }
        }

        $tasks = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $employees = Employee::all();

        return view('tasks.index', compact('tasks', 'employees'));
    }

    public function create()
    {
        $projects = Project::all();
        $employees = Employee::all();

        return view('tasks.create', compact('projects', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'employee_id' => 'nullable|exists:employees,id',
            'title'       => 'required|max:255',
            'description' => 'nullable',
            'due_date'    => 'required|date',
            'status'      => 'required',
            'priority'    => 'required',
        ]);

        $task = Task::create($request->all());

        if ($task->employee && $task->employee->user) {
            $task->employee->user->notify(new \App\Notifications\TaskAssignedNotification($task));
        }

        return redirect()
                ->route('tasks.index')
                ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $employees = Employee::all();

        return view('tasks.edit', compact('task', 'projects', 'employees'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'employee_id' => 'nullable|exists:employees,id',
            'title'       => 'required|max:255',
            'description' => 'nullable',
            'due_date'    => 'required|date',
            'status'      => 'required',
            'priority'    => 'required',
        ]);

        $oldEmployeeId = $task->employee_id;
        $task->update($request->all());

        if ($task->employee_id != $oldEmployeeId && $task->employee && $task->employee->user) {
            $task->employee->user->notify(new \App\Notifications\TaskAssignedNotification($task));
        }

        return redirect()
                ->route('tasks.index')
                ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
                ->route('tasks.index')
                ->with('success', 'Task deleted successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'employee']);

        return view('tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $employee = auth()->user()->employee;
        if (!$employee || $task->employee_id !== $employee->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:To Do,In Progress,Doing,Completed',
        ]);

        $task->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Task status updated successfully.');
    }
}
