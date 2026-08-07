<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::all();

        $employees = Employee::with(['department', 'designation'])

            ->when($request->search, function ($query) use ($request) {

                $query->where(function ($q) use ($request) {

                    $q->where('first_name', 'like', "%{$request->search}%")
                      ->orWhere('last_name', 'like', "%{$request->search}%")
                      ->orWhere('employee_id', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");

                });

            })

            ->when($request->department, function ($query) use ($request) {

                $query->where('department_id', $request->department);

            })

            ->when($request->status, function ($query) use ($request) {

                $query->where('status', $request->status);

            })

            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('employees.index', compact(
            'employees',
            'departments'
        ));
    }

    public function create()
    {
        $departments = Department::all();
        $designations = Designation::all();
        $users = User::where('role', 'employee')->get();

        return view('employees.create', compact('departments', 'designations', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'    => 'required|unique:employees',
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email'          => 'required|email|unique:employees',
            'phone'          => 'nullable',
            'department_id'  => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'joining_date'   => 'required|date',
            'salary'         => 'required|numeric',
            'status'         => 'required',
            'user_id'        => 'nullable|exists:users,id',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $designations = Designation::all();
        $users = User::where('role', 'employee')->get();

        return view('employees.edit', compact('employee', 'departments', 'designations', 'users'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_id'    => 'required|unique:employees,employee_id,' . $employee->id,
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email'          => 'required|email|unique:employees,email,' . $employee->id,
            'phone'          => 'nullable',
            'department_id'  => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'joining_date'   => 'required|date',
            'salary'         => 'required|numeric',
            'status'         => 'required',
            'user_id'        => 'nullable|exists:users,id',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('leaves');

        return view('employees.show', compact('employee'));
    }
}
