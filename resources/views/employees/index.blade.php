@extends('layouts.app')

@section('title', 'Employee Directory')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Employees']
    ]" />

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Employee Directory</h3>
                <p class="text-secondary small mb-0">Manage all staff members, department allocations, and employment profiles.</p>
            </div>
            <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#addEmployeeDrawer">
                + Add Employee
            </button>
        </div>
    </div>

    {{-- Single Horizontal Row Filters Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" action="{{ route('employees.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-xl-4 col-md-4">
                    <label class="form-label small fw-semibold text-secondary mb-1">Search Employee</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control rounded-3 border-light-subtle shadow-2xs"
                        placeholder="Search by employee name or email...">
                </div>

                <div class="col-xl-3 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Department</label>
                    <select name="department" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-3 col-md-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Status</label>
                    <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-xl-2 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-50 rounded-3 fw-bold text-white shadow-sm py-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary w-50 rounded-3 fw-semibold py-2 d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Employees Data Table Card (0 Overflow, Fixed Table Layout) --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive" style="overflow: hidden !important;">
            <table class="table align-middle mb-0 w-100" id="employeesTable" style="table-layout: fixed; width: 100% !important;">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3.5" style="width: 60px;">ID</th>
                        <th class="py-3.5" style="width: 22%;">Employee</th>
                        <th class="py-3.5" style="width: 26%;">Email Address</th>
                        <th class="py-3.5" style="width: 14%;">Department</th>
                        <th class="py-3.5" style="width: 14%;">Designation</th>
                        <th class="py-3.5" style="width: 10%;">Status</th>
                        <th class="pe-4 text-end py-3.5" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr class="hover-row">
                            <td class="ps-4 fw-bold text-secondary py-4" style="font-size: 0.85rem;">#{{ $employee->id }}</td>
                            <td class="py-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="$employee->first_name . ' ' . $employee->last_name" size="md" />
                                    <div class="lh-sm text-truncate">
                                        <a href="{{ route('employees.show', $employee) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block mb-0.5 text-truncate" style="font-size: 0.9rem;">
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </a>
                                        <small class="text-muted d-block text-truncate" style="font-size: 0.73rem; color: #64748B !important;">
                                            Emp ID: {{ $employee->employee_id ?? ('#' . $employee->id) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-secondary small py-4 text-truncate">{{ $employee->email }}</td>
                            <td class="py-4">
                                <span class="text-secondary small fw-semibold text-truncate d-block">{{ $employee->department?->name ?? 'N/A' }}</span>
                            </td>
                            <td class="py-4">
                                <span class="text-secondary small fw-semibold text-truncate d-block">{{ $employee->designation?->title ?? 'N/A' }}</span>
                            </td>
                            <td class="py-4">
                                @if(strtolower(trim($employee->status)) === 'active')
                                    <span class="badge rounded-pill fw-bold px-3 py-1.5 align-middle" style="background-color: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; font-size: 0.76rem;">Active</span>
                                @else
                                    <span class="badge rounded-pill fw-bold px-3 py-1.5 align-middle" style="background-color: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; font-size: 0.76rem;">{{ $employee->status }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end py-4">
                                <div class="d-flex align-items-center justify-content-end" style="gap: 10px;">
                                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-action-view flex-shrink-0" title="View Profile" aria-label="View Profile">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-action-edit flex-shrink-0" title="Edit Employee" aria-label="Edit Employee">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline flex-shrink-0 m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Employee" aria-label="Delete Employee" onclick="return confirm('Are you sure you want to delete this employee?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-0">
                                <x-empty-state title="No Employees Found" icon="bi-person-x" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Slide-over Drawer for Add Employee (Offcanvas Right Panel with Always Visible Sticky Save Button) --}}
<div class="offcanvas offcanvas-end border-0 shadow-lg" id="addEmployeeDrawer" tabindex="-1" aria-labelledby="addEmployeeDrawerLabel" style="width: 45%; min-width: 460px; max-width: 680px;">
    {{-- Fixed Header --}}
    <div class="offcanvas-header bg-white border-bottom p-4">
        <div>
            <h5 class="offcanvas-title fw-bold text-dark mb-0" id="addEmployeeDrawerLabel">Add New Employee</h5>
            <small class="text-secondary">Enter employee profile, company allocation, and payroll details.</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    {{-- Form Container --}}
    <form id="addEmployeeForm" action="{{ route('employees.store') }}" method="POST" class="d-flex flex-column" style="height: calc(100vh - 85px);">
        @csrf
        
        {{-- Scrollable Body --}}
        <div class="offcanvas-body p-4 flex-grow-1" style="overflow-y: auto;">
            <div id="addEmployeeAlert" class="alert alert-danger d-none rounded-3 py-2 px-3 small mb-4"></div>

            {{-- Section 1: Personal Details --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-person-fill"></i> Personal Details
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="e.g. John" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="e.g. Doe" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="john.doe@company.com" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Phone Number</label>
                        <input type="text" name="phone" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="+92 300 1234567">
                    </div>
                </div>
            </div>

            <hr class="my-4 border-light-subtle opacity-50">

            {{-- Section 2: Company & Position Info --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-building"></i> Company & Position Info
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Employee ID Code <span class="text-danger">*</span></label>
                        <input type="text" name="employee_id" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="e.g. EMP-101" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Joining Date <span class="text-danger">*</span></label>
                        <input type="date" name="joining_date" class="form-control rounded-3 border-light-subtle shadow-2xs" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Department <span class="text-danger">*</span></label>
                        <select name="department_id" class="form-select rounded-3 border-light-subtle shadow-2xs" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Designation <span class="text-danger">*</span></label>
                        <select name="designation_id" class="form-select rounded-3 border-light-subtle shadow-2xs" required>
                            <option value="">Select Designation</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig->id }}">{{ $desig->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-secondary mb-1">Linked User Account</label>
                        <select name="user_id" class="form-select rounded-3 border-light-subtle shadow-2xs">
                            <option value="">None (Standalone)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr class="my-4 border-light-subtle opacity-50">

            {{-- Section 3: Financials --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-cash-stack"></i> Financial Details
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold text-secondary mb-1">Monthly Salary (PKR) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light-subtle text-secondary fw-bold">PKR</span>
                            <input type="number" step="0.01" name="salary" class="form-control rounded-end-3 border-light-subtle shadow-2xs" placeholder="e.g. 75000" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Always Visible Sticky Footer Bar --}}
        <div class="p-3 px-4 bg-white border-top d-flex justify-content-end gap-2.5 flex-shrink-0" style="position: sticky; bottom: 0; z-index: 10;">
            <button type="button" class="btn btn-outline-secondary fw-semibold px-4 py-2 rounded-3" data-bs-dismiss="offcanvas">Cancel</button>
            <button type="submit" id="saveEmpBtn" class="btn btn-primary fw-bold px-4 py-2 rounded-3 text-white shadow-sm">
                <i class="bi bi-check-circle-fill me-1.5"></i> Save Employee
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addEmployeeForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveEmpBtn');
        const alertBox = document.getElementById('addEmployeeAlert');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Saving...';
        alertBox.classList.add('d-none');

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                let msg = data.message || 'Validation error occurred.';
                if (data.errors) {
                    msg = Object.values(data.errors).flat().join('<br>');
                }
                throw new Error(msg);
            }
            return data;
        })
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle-fill me-1.5"></i> Save Employee';

            if (data.success) {
                // Close Slide-over Drawer
                const drawerEl = document.getElementById('addEmployeeDrawer');
                const drawerInstance = bootstrap.Offcanvas.getInstance(drawerEl) || new bootstrap.Offcanvas(drawerEl);
                drawerInstance.hide();

                // Reset Form
                form.reset();

                // Dynamically prepend new employee row into table
                const tbody = document.querySelector('#employeesTable tbody');
                const emptyCell = tbody.querySelector('td[colspan]');
                if (emptyCell) {
                    emptyCell.closest('tr').remove();
                }

                const emp = data.employee;
                const deptName = emp.department ? emp.department.name : 'N/A';
                const desigTitle = emp.designation ? emp.designation.title : 'N/A';
                const isActive = (emp.status || '').toLowerCase() === 'active';

                const statusBadge = isActive 
                    ? `<span class="badge rounded-pill fw-bold px-3 py-1.5 align-middle" style="background-color: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; font-size: 0.76rem;">Active</span>`
                    : `<span class="badge rounded-pill fw-bold px-3 py-1.5 align-middle" style="background-color: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; font-size: 0.76rem;">${emp.status}</span>`;

                const newRow = document.createElement('tr');
                newRow.className = 'hover-row bg-success-subtle transition-all';
                newRow.innerHTML = `
                    <td class="ps-4 fw-bold text-secondary py-4" style="font-size: 0.85rem;">#${emp.id}</td>
                    <td class="py-4">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="avatar-md bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 0.85rem;">
                                ${(emp.first_name[0] || '').toUpperCase()}${(emp.last_name[0] || '').toUpperCase()}
                            </div>
                            <div class="lh-sm text-truncate">
                                <a href="/employees/${emp.id}" class="fw-bold text-dark text-decoration-none hover-primary d-block mb-0.5 text-truncate" style="font-size: 0.9rem;">
                                    ${emp.first_name} ${emp.last_name}
                                </a>
                                <small class="text-muted d-block text-truncate" style="font-size: 0.73rem; color: #64748B !important;">
                                    Emp ID: ${emp.employee_id ? emp.employee_id : '#' + emp.id}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td class="text-secondary small py-4 text-truncate">${emp.email}</td>
                    <td class="py-4">
                        <span class="text-secondary small fw-semibold text-truncate d-block">${deptName}</span>
                    </td>
                    <td class="py-4">
                        <span class="text-secondary small fw-semibold text-truncate d-block">${desigTitle}</span>
                    </td>
                    <td class="py-4">
                        ${statusBadge}
                    </td>
                    <td class="pe-4 text-end py-4">
                        <div class="d-flex align-items-center justify-content-end" style="gap: 10px;">
                            <a href="/employees/${emp.id}" class="btn btn-action-view flex-shrink-0" title="View Profile" aria-label="View Profile">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="/employees/${emp.id}/edit" class="btn btn-action-edit flex-shrink-0" title="Edit Employee" aria-label="Edit Employee">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="/employees/${emp.id}" method="POST" class="d-inline flex-shrink-0 m-0">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-action-delete" title="Delete Employee" aria-label="Delete Employee" onclick="return confirm('Are you sure you want to delete this employee?')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                `;
                tbody.prepend(newRow);

                // Smoothly remove flash effect after 2 seconds
                setTimeout(() => {
                    newRow.classList.remove('bg-success-subtle');
                }, 2000);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle-fill me-1.5"></i> Save Employee';
            alertBox.innerHTML = err.message || 'An error occurred while saving the employee.';
            alertBox.classList.remove('d-none');
        });
    });
});
</script>

@endsection
