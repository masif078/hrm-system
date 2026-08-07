@extends('layouts.app')

@section('title', 'Add New Shift')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Add New Shift</h3>
                    <p class="text-muted mb-0">Set up work hours and grace thresholds for shift assignments.</p>
                </div>
                <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
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
                    <form action="{{ route('shifts.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Shift Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Day Shift, Evening Shift" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="late_mark_after" class="form-label">Late Threshold (Grace period end)</label>
                                <input type="time" name="late_mark_after" id="late_mark_after" class="form-control" value="{{ old('late_mark_after') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="early_checkout_before" class="form-label">Early Checkout Threshold</label>
                                <input type="time" name="early_checkout_before" id="early_checkout_before" class="form-control" value="{{ old('early_checkout_before') }}" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Save Shift</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
