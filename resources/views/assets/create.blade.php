@extends('layouts.app')

@section('title', 'Add Asset')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Add New Asset</h3>
        <p class="text-muted">Register a company asset with serial number and warranty tracking details.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 750px;">
        <div class="card-body p-4">
            <form action="{{ route('assets.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Asset Name (e.g. MacBook Pro M3)</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="asset_category_id" class="form-label fw-semibold">Asset Category</label>
                        <select name="asset_category_id" id="asset_category_id" class="form-select @error('asset_category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('asset_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('asset_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="serial_number" class="form-label fw-semibold">Serial / Asset ID</label>
                        <input type="text" name="serial_number" id="serial_number" class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number') }}" required>
                        @error('serial_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="cost" class="form-label fw-semibold">Purchase Cost (PKR)</label>
                        <input type="number" name="cost" id="cost" min="0" step="0.01" class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost', 0) }}" required>
                        @error('cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="purchase_date" class="form-label fw-semibold">Purchase Date</label>
                        <input type="date" name="purchase_date" id="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                        @error('purchase_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="warranty_expiry" class="form-label fw-semibold">Warranty Expiry</label>
                        <input type="date" name="warranty_expiry" id="warranty_expiry" class="form-control @error('warranty_expiry') is-invalid @enderror" value="{{ old('warranty_expiry') }}">
                        @error('warranty_expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Initial Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Assigned" {{ old('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="Lost" {{ old('status') == 'Lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Asset</button>
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
