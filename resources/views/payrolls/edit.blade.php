@extends('layouts.app')

@section('title', 'Process Payment')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Process Payroll Payment</h3>
                    <p class="text-muted mb-0">Update payment status, payment date, and remarks for employee payroll.</p>
                </div>
                <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
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
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark fw-bold">
                    Update Payment Information
                </div>
                <div class="card-body">
                    <div class="mb-4 text-center">
                        <h5>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</h5>
                        <p class="text-secondary mb-1">Payroll for: <strong>{{ date('F', mktime(0, 0, 0, $payroll->month, 10)) }} {{ $payroll->year }}</strong></p>
                        <h4 class="text-primary mt-2">Net Salary: PKR {{ number_format($payroll->net_salary, 2) }}</h4>
                    </div>

                    <hr>

                    <form action="{{ route('payrolls.update', $payroll->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                            <select name="payment_status" id="payment_status" class="form-select" required>
                                <option value="pending" {{ old('payment_status', $payroll->payment_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('payment_status', $payroll->payment_status) === 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>

                        <div class="mb-3" id="paymentDateGroup">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date', $payroll->payment_date ?? date('Y-m-d')) }}">
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control" placeholder="E.g., Bank transfer complete.">{{ old('remarks', $payroll->remarks) }}</textarea>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Update Payment Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('payment_status');
        const dateGroup = document.getElementById('paymentDateGroup');

        function toggleDateGroup() {
            if (statusSelect.value === 'paid') {
                dateGroup.style.display = 'block';
            } else {
                dateGroup.style.display = 'none';
            }
        }

        statusSelect.addEventListener('change', toggleDateGroup);
        toggleDateGroup(); // Initial run
    });
</script>
@endsection
