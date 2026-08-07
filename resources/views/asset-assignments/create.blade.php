@extends('layouts.app')

@section('title', 'Check-out Asset')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Check-out Company Asset</h3>
        <p class="text-muted">Allocate an available device to an employee profile.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="{{ route('asset-assignments.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="asset_id" class="form-label fw-semibold">Available Asset</label>
                    <select name="asset_id" id="asset_id" class="form-select @error('asset_id') is-invalid @enderror" required>
                        <option value="">Select Asset</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->name }} (SN: {{ $asset->serial_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="employee_id" class="form-label fw-semibold">Assign To Employee</label>
                    <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->department->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="assign_date" class="form-label fw-semibold">Assignment Date</label>
                    <input type="date" name="assign_date" id="assign_date" class="form-control @error('assign_date') is-invalid @enderror" value="{{ old('assign_date', date('Y-m-d')) }}" required>
                    @error('assign_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="condition_upon_assign" class="form-label fw-semibold">Device Condition Upon Assignment</label>
                    <input type="text" name="condition_upon_assign" id="condition_upon_assign" class="form-control @error('condition_upon_assign') is-invalid @enderror" value="{{ old('condition_upon_assign', 'Excellent / Brand New') }}" required>
                    @error('condition_upon_assign')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Checkout Device</button>
                    <a href="{{ route('asset-assignments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
