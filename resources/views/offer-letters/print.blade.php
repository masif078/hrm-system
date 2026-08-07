<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Offer Letter - {{ $offerLetter->application->candidate->full_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Georgia, serif; font-size: 14px; line-height: 1.6; padding: 40px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 20mm 15mm; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container" style="max-width: 800px;">
    <div class="text-end mb-4 no-print">
        <button onclick="window.print()" class="btn btn-primary">Print Now</button>
        <button onclick="window.close()" class="btn btn-secondary">Close Tab</button>
        <hr>
    </div>

    <div class="border p-5 rounded bg-white">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-0">COMPANY JOB OFFER</h2>
            <p class="text-muted small mb-0">Formal Employment Proposal Agreement</p>
        </div>

        <p class="text-dark small text-end mb-4">Date: {{ date('M d, Y', strtotime($offerLetter->sent_date)) }}</p>

        <div class="mb-4">
            <h6 class="fw-bold text-dark mb-1">To:</h6>
            <h5 class="fw-bold text-primary mb-1">{{ $offerLetter->application->candidate->full_name }}</h5>
            <p class="text-muted small mb-0">{{ $offerLetter->application->candidate->email }}</p>
        </div>

        <p class="text-dark mb-4">
            Dear {{ $offerLetter->application->candidate->full_name }},<br><br>
            We are pleased to offer you the position of <strong>{{ $offerLetter->application->jobOpening->title }}</strong> in the Department of <strong>{{ $offerLetter->application->jobOpening->department->name ?? 'N/A' }}</strong> at our organization.
        </p>

        <p class="text-dark mb-4">
            The terms and conditions of this offer are as follows:
        </p>

        <table class="table table-bordered mb-4">
            <tr>
                <th width="200" class="bg-light fw-bold text-dark">Position Title</th>
                <td>{{ $offerLetter->application->jobOpening->title }}</td>
            </tr>
            <tr>
                <th class="bg-light fw-bold text-dark">Offered Salary</th>
                <td>PKR {{ number_format($offerLetter->salary_offered, 2) }} per month (Gross)</td>
            </tr>
            <tr>
                <th class="bg-light fw-bold text-dark">Proposed Joining Date</th>
                <td>{{ date('F d, Y', strtotime($offerLetter->joining_date)) }}</td>
            </tr>
            <tr>
                <th class="bg-light fw-bold text-dark">Offer Status</th>
                <td>{{ $offerLetter->status }}</td>
            </tr>
        </table>

        <p class="text-dark mb-4">
            Please indicate your acceptance of this offer by signing below and returning it to the HR department on or before the joining date.
        </p>

        <div class="row mt-5 pt-4">
            <div class="col-6">
                <div style="border-top: 1px solid #000; width: 200px; padding-top: 5px;" class="small text-muted">
                    HR Representative Signature
                </div>
            </div>
            <div class="col-6 text-end">
                <div style="border-top: 1px solid #000; width: 200px; display: inline-block; padding-top: 5px;" class="small text-muted text-start">
                    Candidate Signature & Date
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
