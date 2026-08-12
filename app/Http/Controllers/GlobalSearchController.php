<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Department;
use App\Models\Leave;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([
                'employees'   => [],
                'projects'    => [],
                'departments' => [],
                'leaves'      => [],
            ]);
        }

        // Search Employees (by first_name, last_name, employee_id, or department name)
        $employees = Employee::with('department')
            ->where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('employee_id', 'LIKE', "%{$query}%")
            ->orWhereHas('department', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($emp) {
                return [
                    'id'       => $emp->id,
                    'title'    => $emp->first_name . ' ' . $emp->last_name,
                    'subtitle' => ($emp->employee_id ? '#' . $emp->employee_id . ' • ' : '') . ($emp->department?->name ?? 'No Dept'),
                    'url'      => route('employees.show', $emp->id),
                    'icon'     => 'bi-person-badge-fill',
                ];
            });

        // Search Projects (by project_name, project_code)
        $projects = Project::where('project_name', 'LIKE', "%{$query}%")
            ->orWhere('project_code', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($p) {
                return [
                    'id'       => $p->id,
                    'title'    => $p->project_name,
                    'subtitle' => $p->project_code ? 'Code: ' . $p->project_code : 'Project',
                    'url'      => route('projects.show', $p->id),
                    'icon'     => 'bi-briefcase-fill',
                ];
            });

        // Search Departments (by name, code)
        $departments = Department::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($d) {
                return [
                    'id'       => $d->id,
                    'title'    => $d->name,
                    'subtitle' => $d->code ? 'Code: ' . $d->code : 'Department',
                    'url'      => route('departments.index') . '?search=' . urlencode($d->name),
                    'icon'     => 'bi-building-fill',
                ];
            });

        // Search Leaves (by leave_type or employee name)
        $leaves = Leave::with('employee')
            ->where('leave_type', 'LIKE', "%{$query}%")
            ->orWhereHas('employee', function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($l) {
                $empName = $l->employee ? ($l->employee->first_name . ' ' . $l->employee->last_name) : 'Employee';
                return [
                    'id'       => $l->id,
                    'title'    => $empName . ' - ' . $l->leave_type,
                    'subtitle' => 'Status: ' . $l->status . ' (' . $l->start_date . ')',
                    'url'      => route('leaves.show', $l->id),
                    'icon'     => 'bi-calendar-event-fill',
                ];
            });

        return response()->json([
            'employees'   => $employees,
            'projects'    => $projects,
            'departments' => $departments,
            'leaves'      => $leaves,
        ]);
    }
}
