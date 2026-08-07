@extends('layouts.app')

@section('title', 'Return Asset (Check-in)')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Return Company Asset</h3>
        <p class="text-muted">Record return check-in parameters for **{{ $assetAssignment->asset->name }}** assigned to **{{ $assetAssignment->employee->first_name }} {{ $assetAssignment->employee->last_name }}**.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="{{ route('asset-assignments.update', $assetAssignment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Checked Out On</label>
                    <input type="text" class="form-control bg-light" value="{{ date('M d, Y', strtotime($assetAssignment->assign_date)) }} (Condition: {{ $assetAssignment->condition_upon_assign }})" readonly>
                </div>

                <div class="mb-3">
                    <label for="return_date" class="form-label fw-semibold">Return Date</label>
                    <input type="date" name="return_date" id="return_date" class="form-control @error('return_date') is-invalid @enderror" value="{{ old('return_date', date('Y-m-d')) }}" required>
                    @error('return_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="condition_upon_return" class="form-label fw-semibold">Device Condition Upon Return</label>
                    <input type="text" name="condition_upon_return" id="condition_upon_return" class="form-control @error('condition_upon_return') is-invalid @enderror" value="{{ old('condition_upon_return', 'Good / No damage') }}" required>
                    <small class="text-muted d-block mt-1">If condition contains `damaged`, `broken`, `repair`, or `faulty`, the asset status will automatically move to **Maintenance**.</small>
                    @error('condition_upon_return')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Return Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Returned">Returned</option>
                        <option value="Lost">Lost</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Record Return</button>
                    <a href="{{ route('asset-assignments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
