@extends('layouts.app')

@section('title', 'Edit KPI')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Edit KPI Definition</h3>
                    <p class="text-muted mb-0">Modify KPI targets and parameters.</p>
                </div>
                <a href="{{ route('kpis.index') }}" class="btn btn-secondary">
                    Back to Dashboard
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
                <div class="card-body">
                    <form action="{{ route('kpis.update', $kpi->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">KPI Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $kpi->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description', $kpi->description) }}</textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="target_value" class="form-label">Target Value</label>
                                <input type="number" name="target_value" id="target_value" class="form-control" value="{{ old('target_value', $kpi->target_value) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="unit" class="form-label">Unit of Measure</label>
                                <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $kpi->unit) }}" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update KPI Definition</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
