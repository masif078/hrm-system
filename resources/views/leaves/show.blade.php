@extends('layouts.app')

@section('title', 'Leave Details')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <h4 class="mb-0">Leave Details</h4>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Employee:</strong>

                    <p class="mb-0">
                        {{ $leave->employee->first_name ?? '' }}
                        {{ $leave->employee->last_name ?? '' }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Leave Type:</strong>

                    <p class="mb-0">
                        {{ $leave->leave_type }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Start Date:</strong>

                    <p class="mb-0">
                        {{ $leave->start_date }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>End Date:</strong>

                    <p class="mb-0">
                        {{ $leave->end_date }}
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Status:</strong>

                    <p class="mb-0">

                        @if($leave->status == 'Approved')

                            <span class="badge bg-success">
                                Approved
                            </span>

                        @elseif($leave->status == 'Rejected')

                            <span class="badge bg-danger">
                                Rejected
                            </span>

                        @else

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        @endif

                    </p>
                </div>

                <div class="col-md-12 mb-3">
                    <strong>Reason:</strong>

                    <p class="mb-0">
                        {{ $leave->reason ?? 'No reason provided.' }}
                    </p>
                </div>

            </div>

            <a href="{{ route('leaves.edit', $leave) }}"
               class="btn btn-warning">
                Edit
            </a>

            <a href="{{ route('leaves.index') }}"
               class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>

</div>

@endsection
