@extends('layouts.app')

@section('title', 'Edit Appraisal')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit Salary/Promotion Appraisal</h3>
                    <p class="text-muted mb-0">Modify details of appraisal record before final approval.</p>
                </div>
                <a href="{{ route('appraisals.index') }}" class="btn btn-secondary">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('appraisals.update', $appraisal->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                            data-salary="{{ $employee->salary }}" 
                                            data-designation="{{ $employee->designation_id }}"
                                            {{ old('employee_id', $appraisal->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="performance_review_id" class="form-label">Performance Review (Optional)</label>
                            <select name="performance_review_id" id="performance_review_id" class="form-select">
                                <option value="">Select Completed Review</option>
                                @foreach($reviews as $rev)
                                    <option value="{{ $rev->id }}" 
                                            data-employee="{{ $rev->employee_id }}"
                                            data-rating="{{ $rev->rating }}"
                                            {{ old('performance_review_id', $appraisal->performance_review_id) == $rev->id ? 'selected' : '' }}>
                                        {{ $rev->employee->first_name }} {{ $rev->employee->last_name }} - {{ $rev->review_type }} (Rating: {{ $rev->rating }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="rating_class" class="form-label">Rating Class (e.g. High Performer)</label>
                                <input type="text" name="rating_class" id="rating_class" class="form-control" value="{{ old('rating_class', $appraisal->rating_class) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="action_type" class="form-label">Action Taken</label>
                                <select name="action_type" id="action_type" class="form-select" required>
                                    <option value="None" {{ old('action_type', $appraisal->action_type) === 'None' ? 'selected' : '' }}>None</option>
                                    <option value="Increment" {{ old('action_type', $appraisal->action_type) === 'Increment' ? 'selected' : '' }}>Increment Only</option>
                                    <option value="Promotion" {{ old('action_type', $appraisal->action_type) === 'Promotion' ? 'selected' : '' }}>Promotion Only</option>
                                    <option value="Both" {{ old('action_type', $appraisal->action_type) === 'Both' ? 'selected' : '' }}>Both Promotion & Increment</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="previous_salary" class="form-label">Previous Salary</label>
                                <input type="number" name="previous_salary" id="previous_salary" class="form-control" value="{{ old('previous_salary', $appraisal->previous_salary) }}" required readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="new_salary" class="form-label">New Salary</label>
                                <input type="number" name="new_salary" id="new_salary" class="form-control" value="{{ old('new_salary', $appraisal->new_salary) }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="previous_designation_id" class="form-label">Previous Designation</label>
                                <select name="previous_designation_id" id="previous_designation_id" class="form-select" readonly>
                                    <option value="">No Designation</option>
                                    @foreach($designations as $desg)
                                        <option value="{{ $desg->id }}" {{ old('previous_designation_id', $appraisal->previous_designation_id) == $desg->id ? 'selected' : '' }}>
                                            {{ $desg->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="new_designation_id" class="form-label">New Designation</label>
                                <select name="new_designation_id" id="new_designation_id" class="form-select">
                                    <option value="">No Change / Select Designation</option>
                                    @foreach($designations as $desg)
                                        <option value="{{ $desg->id }}" {{ old('new_designation_id', $appraisal->new_designation_id) == $desg->id ? 'selected' : '' }}>
                                            {{ $desg->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="effective_date" class="form-label">Effective Date</label>
                                <input type="date" name="effective_date" id="effective_date" class="form-control" value="{{ old('effective_date', $appraisal->effective_date) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Approval Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Draft" {{ old('status', $appraisal->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Approved" {{ old('status', $appraisal->status) === 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Rejected" {{ old('status', $appraisal->status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Appraisal Action</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const employeeSelect = document.getElementById('employee_id');
        const prevSalary = document.getElementById('previous_salary');
        const newSalary = document.getElementById('new_salary');
        const prevDesg = document.getElementById('previous_designation_id');
        const reviewSelect = document.getElementById('performance_review_id');
        const ratingClassInput = document.getElementById('rating_class');

        function updateEmployeeDetails() {
            const selectedOpt = employeeSelect.options[employeeSelect.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                const salary = selectedOpt.getAttribute('data-salary');
                const designation = selectedOpt.getAttribute('data-designation');
                
                prevSalary.value = salary;
                prevDesg.value = designation || "";
            }
        }

        employeeSelect.addEventListener('change', updateEmployeeDetails);
        
        reviewSelect.addEventListener('change', function() {
            const selectedOpt = reviewSelect.options[reviewSelect.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                const empId = selectedOpt.getAttribute('data-employee');
                const rating = parseFloat(selectedOpt.getAttribute('data-rating'));
                
                employeeSelect.value = empId;
                updateEmployeeDetails();

                if (rating >= 4.5) {
                    ratingClassInput.value = "Outstanding Performer";
                } else if (rating >= 4.0) {
                    ratingClassInput.value = "High Performer";
                } else if (rating >= 3.0) {
                    ratingClassInput.value = "Good Performer";
                } else {
                    ratingClassInput.value = "Needs Improvement";
                }
            }
        });
    });
</script>
@endsection
