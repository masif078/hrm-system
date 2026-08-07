@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')

<div class="container">

    <h2 class="mb-4">Add Employee</h2>

    <form action="{{ route('employees.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label>Employee ID</label>
            <input type="text" name="employee_id" class="form-control">
        </div>

        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label>Department</label>
            <select
                id="department"
                name="department_id"
                class="form-select">
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">

            <label class="form-label">

                Designation

            </label>

            <select
                id="designation"
                name="designation_id"
                class="form-select">

                <option value="">

                    Select Designation

                </option>

            </select>

        </div>

        <div class="mb-3">
            <label>Joining Date</label>
            <input type="date" name="joining_date" class="form-control">
        </div>

        <div class="mb-3">
            <label>Salary</label>
            <input type="number" name="salary" class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Link User Account</label>
            <select name="user_id" class="form-select">
                <option value="">-- Select User (optional) --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">
            Save Employee
        </button>

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
