@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4">Edit Employee</h2>

    <form action="{{ route('employees.update', $employee) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Employee ID</label>
                <input
                    type="text"
                    name="employee_id"
                    class="form-control"
                    value="{{ old('employee_id', $employee->employee_id) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Department</label>

                <select id="department" name="department_id" class="form-control">

                    @foreach($departments as $department)

                        <option
                            value="{{ $department->id }}"
                            {{ $employee->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>

                    @endforeach

                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>First Name</label>

                <input
                    type="text"
                    name="first_name"
                    class="form-control"
                    value="{{ old('first_name', $employee->first_name) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Last Name</label>

                <input
                    type="text"
                    name="last_name"
                    class="form-control"
                    value="{{ old('last_name', $employee->last_name) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $employee->email) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Phone</label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone', $employee->phone) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Designation</label>

                <select id="designation" name="designation_id" class="form-control">

                    <option value="">Select Designation</option>

                    @foreach($designations as $designation)

                        <option
                            value="{{ $designation->id }}"
                            {{ $employee->designation_id == $designation->id ? 'selected' : '' }}>

                            {{ $designation->title }}

                        </option>

                    @endforeach

                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Joining Date</label>

                <input
                    type="date"
                    name="joining_date"
                    class="form-control"
                    value="{{ old('joining_date', $employee->joining_date) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Salary</label>

                <input
                    type="number"
                    name="salary"
                    class="form-control"
                    value="{{ old('salary', $employee->salary) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Status</label>

                <select name="status" class="form-control">

                    <option value="Active"
                        {{ $employee->status == 'Active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="Inactive"
                        {{ $employee->status == 'Inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

            </div>

            <div class="col-md-6 mb-3">
                <label>Link User Account</label>
                <select name="user_id" class="form-select">
                    <option value="">-- Select User (optional) --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ $employee->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <button class="btn btn-primary">
            Update Employee
        </button>

        <a href="{{ route('employees.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

<script>

document.getElementById('department').addEventListener('change', function () {

    let departmentId = this.value;

    let designation = document.getElementById('designation');

    designation.innerHTML =
        '<option value="">Loading...</option>';

    fetch('/departments/' + departmentId + '/designations')

        .then(response => response.json())

        .then(data => {

            designation.innerHTML =
                '<option value="">Select Designation</option>';

            data.forEach(function(item){

                designation.innerHTML +=

                `<option value="${item.id}">

                    ${item.title}

                </option>`;

            });

        });

});

</script>

@endsection
