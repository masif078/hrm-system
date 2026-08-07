@extends('layouts.app')

@section('title', 'Add New Holiday')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">Add New Holiday</h3>
                    <p class="text-muted mb-0">Define public, national, or company-specific holidays.</p>
                </div>
                <a href="{{ route('holidays.index') }}" class="btn btn-secondary">
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
                <div class="card-body">
                    <form action="{{ route('holidays.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Holiday Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Independence Day, New Year" required>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="Public" {{ old('type') === 'Public' ? 'selected' : '' }}>Public Holiday</option>
                                <option value="Company" {{ old('type') === 'Company' ? 'selected' : '' }}>Company Holiday</option>
                                <option value="National" {{ old('type') === 'National' ? 'selected' : '' }}>National Holiday</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Holiday</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
