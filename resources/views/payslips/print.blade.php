<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Payslip - {{ $payroll->employee->first_name }}</title>
    <!-- Include Bootstrap CSS for styling inside print -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            padding: 30px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <div class="d-flex justify-content-between mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary">Print Document</button>
            <button onclick="window.close()" class="btn btn-secondary">Close Window</button>
        </div>

        <div class="border p-5 rounded bg-white shadow-sm">
            <!-- Header -->
            <div class="row align-items-center mb-4">
                <div class="col-6">
                    <h2 class="text-primary fw-bold mb-0">HRM SYSTEM</h2>
                    <p class="text-secondary small mb-0">Premium Business Solutions</p>
                </div>
                <div class="col-6 text-end">
                    <h3 class="text-uppercase fw-bold text-dark mb-0">PAYSLIP</h3>
                    <p class="mb-0 text-muted">Period: {{ date('F Y', mktime(0,0,0, $payroll->month, 10, $payroll->year)) }}</p>
                </div>
            </div>

            <hr class="my-4">

            <!-- Employee Info -->
            <div class="row mb-4">
                <div class="col-6">
                    <h5 class="text-dark fw-bold mb-3">Employee Details</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-secondary" width="140">Employee Name:</td>
                            <td class="fw-bold text-dark">{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Employee ID:</td>
                            <td class="text-dark">{{ $payroll->employee->employee_id }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Department:</td>
                            <td class="text-dark">{{ $payroll->employee->department?->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Designation:</td>
                            <td class="text-dark">{{ $payroll->employee->designation?->title }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <h5 class="text-dark fw-bold mb-3">Payment Details</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-secondary" width="140">Payment Status:</td>
                            <td><span class="badge bg-success">Paid</span></td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Payment Date:</td>
                            <td class="text-dark">{{ $payroll->payment_date ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Net Salary:</td>
                            <td class="fw-bold text-primary">PKR {{ number_format($payroll->net_salary, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Table breakdown -->
            <div class="row mb-4">
                <!-- Earnings -->
                <div class="col-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-header bg-success text-white fw-bold">Earnings & Allowances</div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <td>Basic Salary</td>
                                        <td class="text-end">{{ number_format($payroll->salaryStructure->basic_salary ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>House Rent Allowance</td>
                                        <td class="text-end">{{ number_format($payroll->salaryStructure->house_allowance ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Medical Allowance</td>
                                        <td class="text-end">{{ number_format($payroll->salaryStructure->medical_allowance ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Transport Allowance</td>
                                        <td class="text-end">{{ number_format($payroll->salaryStructure->transport_allowance ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Other Allowance</td>
                                        <td class="text-end">{{ number_format($payroll->salaryStructure->other_allowance ?? 0, 2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Total Earnings (Gross)</td>
                                        <td class="text-end text-success">PKR {{ number_format($payroll->gross_salary, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Deductions -->
                <div class="col-6">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-header bg-danger text-white fw-bold">Deductions</div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <td>Income Tax</td>
                                        <td class="text-end text-danger">-{{ number_format($payroll->salaryStructure->tax ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Provident Fund (PF)</td>
                                        <td class="text-end text-danger">-{{ number_format($payroll->salaryStructure->provident_fund ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Other Deduction</td>
                                        <td class="text-end text-danger">-{{ number_format($payroll->salaryStructure->other_deduction ?? 0, 2) }}</td>
                                    </tr>
                                    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Total Deductions</td>
                                        <td class="text-end text-danger">PKR {{ number_format($payroll->total_deductions, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Summary -->
            <div class="card bg-dark text-white border-0 shadow-sm mb-4">
                <div class="card-body p-4 text-center">
                    <h5 class="text-info mb-1">NET DISBURSEMENT</h5>
                    <h2 class="fw-bold mb-0">PKR {{ number_format($payroll->net_salary, 2) }}</h2>
                    <p class="small text-secondary mb-0 mt-2">Certified that this payslip represents the exact salary breakdown computed and approved by the HRM Administration.</p>
                </div>
            </div>

            <!-- Signatures -->
            <div class="row pt-5 mt-5">
                <div class="col-6 text-center">
                    <hr class="w-50 mx-auto">
                    <p class="text-secondary small">Employee Signature</p>
                </div>
                <div class="col-6 text-center">
                    <hr class="w-50 mx-auto">
                    <p class="text-secondary small">Authorized Signature</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Automatically open browser print dialog on load -->
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
