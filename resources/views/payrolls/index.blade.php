@extends('layouts.app')

@section('title', 'Payroll Directory')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Payroll Directory']
    ]" />

    <div id="alertContainer">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Payroll Directory</h3>
                <p class="text-secondary small mb-0">View, generate, and manage monthly payroll disbursements for employees.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('payrolls.dashboard') }}" class="btn btn-outline-primary rounded-3 fw-bold px-3 py-2 shadow-2xs">
                    <i class="bi bi-speedometer2 me-1"></i> Payroll Dashboard
                </a>
                <button type="button" class="btn btn-success fw-bold px-4 py-2 rounded-3 text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                    <i class="bi bi-plus-lg me-1"></i> Generate Payroll
                </button>
            </div>
        </div>
    </div>

    {{-- Search & Filters Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-4">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Search Employee</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-3 border-light-subtle shadow-2xs" placeholder="Search by name...">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Month</label>
                <select name="month" class="form-select rounded-3 border-light-subtle shadow-2xs">
                    <option value="">All Months</option>
                    @for ($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">Year</label>
                <select name="year" class="form-select rounded-3 border-light-subtle shadow-2xs">
                    <option value="">All Years</option>
                    @for ($y=date('Y')-2; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">Payment Status</label>
                <select name="status" class="form-select rounded-3 border-light-subtle shadow-2xs">
                    <option value="">All Statuses</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button class="btn btn-primary w-100 rounded-3 fw-bold text-white shadow-sm py-2">Filter</button>
                <a href="{{ route('payrolls.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold py-2">Reset</a>
            </div>
        </form>
    </div>

    {{-- Payroll Data Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="payrollsTable">
                <thead class="table-dark" style="background-color: #0F172A;">
                    <tr>
                        <th class="ps-4 py-3">Employee</th>
                        <th class="py-3">Month & Year</th>
                        <th class="py-3">Gross Salary</th>
                        <th class="py-3">Deductions</th>
                        <th class="py-3">Net Salary</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 text-end py-3" width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr class="hover-row" id="payrollRow_{{ $payroll->id }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <x-avatar :name="($payroll->employee->first_name ?? '') . ' ' . ($payroll->employee->last_name ?? '')" size="sm" />
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $payroll->employee->first_name ?? '' }} {{ $payroll->employee->last_name ?? '' }}</span>
                                        <small class="text-secondary opacity-75 d-block">{{ $payroll->employee->department?->name ?? 'No Dept' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold text-secondary">
                                {{ date('F', mktime(0, 0, 0, $payroll->month, 10)) }} {{ $payroll->year }}
                            </td>
                            <td class="fw-bold text-dark">PKR {{ number_format($payroll->gross_salary, 2) }}</td>
                            <td class="text-danger fw-semibold">-PKR {{ number_format($payroll->total_deductions, 2) }}</td>
                            <td class="fw-bold text-primary">PKR {{ number_format($payroll->net_salary, 2) }}</td>
                            <td>
                                @if($payroll->payment_status === 'paid')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Paid</span>
                                    <small class="text-secondary opacity-75 d-block mt-0.5">{{ $payroll->payment_date }}</small>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">Pending</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex align-items-center justify-content-end" style="gap: 10px;">
                                    <a href="{{ route('payrolls.show', $payroll->id) }}" class="btn btn-action-view" title="View Payroll" aria-label="View Payroll">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('payrolls.edit', $payroll->id) }}" class="btn btn-action-edit" title="Pay / Edit Payroll" aria-label="Pay / Edit Payroll">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('payrolls.destroy', $payroll->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payroll record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action-delete" title="Delete Payroll" aria-label="Delete Payroll">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noPayrollsRow">
                            <td colspan="7" class="p-0">
                                <x-empty-state title="No Payroll Records Found" icon="bi-credit-card-2-front" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payrolls->hasPages())
            <div class="card-footer bg-white border-top border-light-subtle p-3">
                {{ $payrolls->appends(request()->input())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- Full Featured Large Modal Overlay (max-width: 5xl / 1140px) --}}
<div class="modal fade" id="generatePayrollModal" tabindex="-1" aria-labelledby="generatePayrollModalLabel" aria-hidden="true" style="backdrop-filter: blur(4px); background: rgba(15, 23, 42, 0.4);">
    <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 1140px;">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden d-flex flex-column" style="max-height: 88vh;">
            
            {{-- Fixed Modal Header --}}
            <div class="modal-header flex-shrink-0 border-bottom border-light-subtle px-4 py-3 bg-white">
                <div>
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 mb-0" id="generatePayrollModalLabel">
                        <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-cash-stack fs-5"></i>
                        </div>
                        Generate Monthly Payroll
                    </h5>
                    <p class="text-secondary small mb-0 ms-5">Generate salary payroll for active employees based on their active salary structures.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Form wrapping Scrollable Body & Sticky Footer --}}
            <form id="generatePayrollForm" action="{{ route('payrolls.store') }}" method="POST" class="d-flex flex-column flex-grow-1 overflow-hidden">
                @csrf
                
                {{-- Scrollable Modal Body --}}
                <div class="modal-body flex-grow-1 overflow-y-auto p-4 bg-light-subtle" style="max-height: calc(88vh - 140px);">
                    <div id="modalPayrollErrors" class="alert alert-danger d-none rounded-3 mb-4"></div>

                    <div class="row g-4 mb-4">
                        
                        {{-- LEFT COLUMN: Payroll Parameters & Options --}}
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 border-bottom border-light-subtle pb-2">
                                    <i class="bi bi-sliders text-primary"></i> 1. Payroll Parameters & Settings
                                </h6>

                                <div class="row g-3">
                                    {{-- Month --}}
                                    <div class="col-md-6">
                                        <label for="modal_month" class="form-label fw-bold text-dark small">Month <span class="text-danger">*</span></label>
                                        <select name="month" id="modal_month" class="form-select rounded-3 border-light-subtle" required>
                                            @for ($m=1; $m<=12; $m++)
                                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    {{-- Year --}}
                                    <div class="col-md-6">
                                        <label for="modal_year" class="form-label fw-bold text-dark small">Year <span class="text-danger">*</span></label>
                                        <select name="year" id="modal_year" class="form-select rounded-3 border-light-subtle" required>
                                            @for ($y=date('Y')-1; $y<=date('Y')+1; $y++)
                                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    {{-- Department Filter --}}
                                    <div class="col-12">
                                        <label for="modal_department_id" class="form-label fw-bold text-dark small">Filter Department</label>
                                        <select name="department_id" id="modal_department_id" class="form-select rounded-3 border-light-subtle">
                                            <option value="all" selected>All Departments</option>
                                            @foreach($departments ?? [] as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Target Employee Selection --}}
                                    <div class="col-12">
                                        <label for="modal_employee_id" class="form-label fw-bold text-dark small">Target Employee (Optional)</label>
                                        <select name="employee_id" id="modal_employee_id" class="form-select rounded-3 border-light-subtle">
                                            <option value="">Generate for ALL Active Employees</option>
                                            @foreach($employees ?? [] as $emp)
                                                <option value="{{ $emp->id }}" data-dept="{{ $emp->department_id }}">
                                                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id ?? 'EMP-' . $emp->id }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text small text-secondary">Leave blank to generate payroll in bulk for all eligible active employees.</div>
                                    </div>

                                    {{-- Remarks --}}
                                    <div class="col-12">
                                        <label for="modal_remarks" class="form-label fw-bold text-dark small">Remarks / Description</label>
                                        <textarea name="remarks" id="modal_remarks" rows="3" class="form-control rounded-3 border-light-subtle" placeholder="E.g., Monthly payroll run for active staff."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Eligible Employee List & Salary Structure Preview --}}
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-light-subtle pb-2">
                                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                        <i class="bi bi-people-fill text-success"></i> 2. Eligible Active Staff & Structure Preview
                                    </h6>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" id="eligibleBadge">
                                        {{ count($employees ?? []) }} Active Employees
                                    </span>
                                </div>

                                <div class="table-responsive overflow-y-auto mb-3" style="max-height: 280px;">
                                    <table class="table table-sm align-middle mb-0" id="eligibleEmployeesTable">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="ps-3 py-2">Employee</th>
                                                <th class="py-2">Department</th>
                                                <th class="py-2">Basic Salary</th>
                                                <th class="pe-3 text-end py-2">Net Salary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($employees ?? [] as $emp)
                                                @php $struct = $emp->activeSalaryStructure; @endphp
                                                <tr class="eligible-row" data-dept="{{ $emp->department_id }}" data-emp-id="{{ $emp->id }}">
                                                    <td class="ps-3 py-2">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <x-avatar :name="$emp->first_name . ' ' . $emp->last_name" size="xs" />
                                                            <div>
                                                                <span class="fw-bold text-dark small d-block">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                                                                <small class="text-secondary opacity-75" style="font-size: 0.7rem;">ID: #{{ $emp->employee_id ?? 'EMP-'.$emp->id }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-2">
                                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                                            {{ $emp->department?->name ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td class="py-2 small fw-semibold text-dark">
                                                        @if($struct)
                                                            PKR {{ number_format($struct->basic_salary, 2) }}
                                                        @else
                                                            <span class="text-danger small">No Structure</span>
                                                        @endif
                                                    </td>
                                                    <td class="pe-3 text-end py-2 small fw-bold text-primary">
                                                        @if($struct)
                                                            PKR {{ number_format($struct->net_salary, 2) }}
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5" style="font-size: 0.65rem;">Skipped</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-secondary py-4 small">No active employees found with active salary structures.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="bg-light rounded-3 p-3 border border-light-subtle d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2 text-secondary small">
                                        <i class="bi bi-info-circle-fill text-primary fs-6"></i>
                                        <span>Existing payroll records for the selected month/year will automatically be skipped.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Fixed Always-Visible Sticky Modal Footer --}}
                <div class="modal-footer flex-shrink-0 border-top border-light-subtle px-4 py-3 bg-white d-flex justify-content-between align-items-center shadow-lg" style="position: sticky; bottom: 0; z-index: 1055; background: #ffffff;">
                    <div class="text-secondary small d-none d-md-block">
                        <i class="bi bi-shield-check text-success me-1"></i> Net Salary = Gross Salary - (Tax + PF + Deductions)
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-auto">
                        <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="generatePayrollBtn" class="btn btn-success rounded-3 px-4 py-2 fw-bold text-white shadow-sm">
                            <i class="bi bi-play-circle-fill me-1"></i> Generate Payroll
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const generatePayrollForm = document.getElementById('generatePayrollForm');
    const generatePayrollBtn = document.getElementById('generatePayrollBtn');
    const modalPayrollErrors = document.getElementById('modalPayrollErrors');
    const alertContainer = document.getElementById('alertContainer');
    const deptSelect = document.getElementById('modal_department_id');
    const empSelect = document.getElementById('modal_employee_id');
    const eligibleRows = document.querySelectorAll('.eligible-row');
    const eligibleBadge = document.getElementById('eligibleBadge');

    // Live filtering preview by department
    if (deptSelect) {
        deptSelect.addEventListener('change', function () {
            const selectedDept = this.value;
            let visibleCount = 0;

            eligibleRows.forEach(row => {
                const rowDept = row.getAttribute('data-dept');
                if (selectedDept === 'all' || selectedDept === '' || rowDept === selectedDept) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (eligibleBadge) {
                eligibleBadge.textContent = visibleCount + ' Eligible Staff';
            }
        });
    }

    // Single employee filter
    if (empSelect) {
        empSelect.addEventListener('change', function () {
            const selectedEmpId = this.value;
            let visibleCount = 0;

            eligibleRows.forEach(row => {
                const rowEmpId = row.getAttribute('data-emp-id');
                if (!selectedEmpId || rowEmpId === selectedEmpId) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (eligibleBadge) {
                eligibleBadge.textContent = visibleCount + ' Selected';
            }
        });
    }

    // AJAX Form submission
    if (generatePayrollForm) {
        generatePayrollForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!confirm('Run payroll processing for the selected month and year? Existing payrolls for this period will automatically be skipped.')) {
                return;
            }

            generatePayrollBtn.disabled = true;
            generatePayrollBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
            modalPayrollErrors.classList.add('d-none');
            modalPayrollErrors.innerHTML = '';

            const formData = new FormData(generatePayrollForm);

            fetch("{{ route('payrolls.store') }}", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                generatePayrollBtn.disabled = false;
                generatePayrollBtn.innerHTML = '<i class="bi bi-play-circle-fill me-1"></i> Generate Payroll';

                if (data.success) {
                    // Close Modal
                    const modalEl = document.getElementById('generatePayrollModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // Reset Form
                    generatePayrollForm.reset();

                    // Show success alert and reload
                    alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i> ${data.message}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                } else {
                    let errHtml = '<ul class="mb-0 ps-3">';
                    if (data.errors) {
                        Object.values(data.errors).forEach(errArray => {
                            errArray.forEach(err => {
                                errHtml += `<li>${err}</li>`;
                            });
                        });
                    } else if (data.message) {
                        errHtml += `<li>${data.message}</li>`;
                    }
                    errHtml += '</ul>';
                    modalPayrollErrors.innerHTML = errHtml;
                    modalPayrollErrors.classList.remove('d-none');
                }
            })
            .catch(error => {
                generatePayrollBtn.disabled = false;
                generatePayrollBtn.innerHTML = '<i class="bi bi-play-circle-fill me-1"></i> Generate Payroll';
                modalPayrollErrors.innerHTML = 'An unexpected error occurred. Please try again.';
                modalPayrollErrors.classList.remove('d-none');
            });
        });
    }
});
</script>

@endsection
