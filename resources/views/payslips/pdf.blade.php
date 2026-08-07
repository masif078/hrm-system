<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payroll->employee->first_name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            width: 100%;
            margin-bottom: 30px;
        }
        .company-title {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
            margin: 0;
        }
        .company-subtitle {
            font-size: 12px;
            color: #6c757d;
            margin: 0;
        }
        .payslip-title {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin: 0;
            text-transform: uppercase;
        }
        .period {
            font-size: 12px;
            color: #6c757d;
            text-align: right;
            margin: 0;
        }
        hr {
            border: 0;
            border-top: 1px solid #dee2e6;
            margin: 20px 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-label {
            color: #6c757d;
            width: 30%;
        }
        .info-value {
            font-weight: bold;
            color: #212529;
            width: 70%;
        }
        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .breakdown-table th {
            background-color: #343a40;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 12px;
            border: 1px solid #343a40;
        }
        .breakdown-table td {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
        }
        .bg-light-green {
            background-color: #d1e7dd;
            color: #0f5132;
            font-weight: bold;
        }
        .bg-light-red {
            background-color: #f8d7da;
            color: #842029;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-danger {
            color: #dc3545;
        }
        .net-salary-card {
            background-color: #212529;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 40px;
        }
        .net-salary-card h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #0dcaf0;
            letter-spacing: 1px;
        }
        .net-salary-card h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .net-salary-card p {
            margin: 10px 0 0 0;
            font-size: 11px;
            color: #adb5bd;
        }
        .signatures {
            width: 100%;
            margin-top: 50px;
        }
        .signature-line {
            width: 40%;
            border-top: 1px solid #6c757d;
            text-align: center;
            padding-top: 5px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="header">
            <tr>
                <td style="width: 50%;">
                    <div class="company-title">HRM SYSTEM</div>
                    <div class="company-subtitle">Premium Business Solutions</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="payslip-title">PAYSLIP</div>
                    <div class="period">Period: {{ date('F Y', mktime(0, 0, 0, $payroll->month, 10, $payroll->year)) }}</div>
                </td>
            </tr>
        </table>

        <hr>

        <!-- Employee Info -->
        <table class="info-table">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="info-label">Employee Name:</td>
                            <td class="info-value">{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Employee ID:</td>
                            <td class="info-value">{{ $payroll->employee->employee_id }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Department:</td>
                            <td class="info-value">{{ $payroll->employee->department?->name }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Designation:</td>
                            <td class="info-value">{{ $payroll->employee->designation?->title }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="info-label">Payment Status:</td>
                            <td class="info-value" style="color: #198754;">Paid</td>
                        </tr>
                        <tr>
                            <td class="info-label">Payment Date:</td>
                            <td class="info-value">{{ $payroll->payment_date ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Net Salary:</td>
                            <td class="info-value" style="color: #0d6efd;">PKR {{ number_format($payroll->net_salary, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Earnings and Deductions Table -->
        <table class="breakdown-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Earnings & Allowances</th>
                    <th style="width: 50%;">Deductions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 0; vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse; border: none;">
                            <tr>
                                <td style="border: none; padding: 6px 12px;">Basic Salary</td>
                                <td style="border: none; padding: 6px 12px; text-align: right;">{{ number_format($payroll->salaryStructure->basic_salary ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 6px 12px;">House Rent Allowance</td>
                                <td style="border: none; padding: 6px 12px; text-align: right;">{{ number_format($payroll->salaryStructure->house_allowance ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 6px 12px;">Medical Allowance</td>
                                <td style="border: none; padding: 6px 12px; text-align: right;">{{ number_format($payroll->salaryStructure->medical_allowance ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 6px 12px;">Transport Allowance</td>
                                <td style="border: none; padding: 6px 12px; text-align: right;">{{ number_format($payroll->salaryStructure->transport_allowance ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 6px 12px;">Other Allowance</td>
                                <td style="border: none; padding: 6px 12px; text-align: right;">{{ number_format($payroll->salaryStructure->other_allowance ?? 0, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="padding: 0; vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse; border: none;">
                            <tr>
                                <td style="border: none; padding: 6px 12px;">Income Tax</td>
                                <td style="border: none; padding: 6px 12px; text-align: right;" class="text-danger">-{{ number_format($payroll->salaryStructure->tax ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 6px 12px;">Provident Fund (PF)</td>
                                <td style="border: none; padding: 6px 12px; text-align: right;" class="text-danger">-{{ number_format($payroll->salaryStructure->provident_fund ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="border: none; padding: 6px 12px;">Other Deduction</td>
                                <td style="border: none; padding: 6px 12px; text-align: right;" class="text-danger">-{{ number_format($payroll->salaryStructure->other_deduction ?? 0, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="bg-light-green">
                        Total Earnings: <span style="float: right;">PKR {{ number_format($payroll->gross_salary, 2) }}</span>
                    </td>
                    <td class="bg-light-red">
                        Total Deductions: <span style="float: right;">PKR {{ number_format($payroll->total_deductions, 2) }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Net Salary Display -->
        <div class="net-salary-card">
            <h3>NET DISBURSEMENT</h3>
            <h1>PKR {{ number_format($payroll->net_salary, 2) }}</h1>
            <p>Certified that this payslip represents the exact salary breakdown computed and approved by the HRM Administration.</p>
        </div>

        <!-- Signature lines -->
        <table class="signatures" style="width: 100%;">
            <tr>
                <td class="signature-line" style="width: 45%;">Employee Signature</td>
                <td style="width: 10%;">&nbsp;</td>
                <td class="signature-line" style="width: 45%;">Authorized Signature</td>
            </tr>
        </table>
    </div>
</body>
</html>
