@extends('layouts.app')

@section('title', 'Role-Permission Matrix')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Role-Permission Custom Matrix</h3>
        <p class="text-muted">Control dashboard module permissions dynamically for each user group role.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm bg-white" style="max-width: 800px;">
        <div class="card-body p-4">
            <form action="{{ route('settings.permissions.update') }}" method="POST">
                @csrf
                
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle text-dark">
                        <thead class="table-light">
                            <tr>
                                <th>Role Group</th>
                                <th class="text-center">Recruitment Module</th>
                                <th class="text-center">Asset Management</th>
                                <th class="text-center">System Settings</th>
                                <th class="text-center">Payroll Management</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['admin', 'employee'] as $role)
                                <tr>
                                    <td class="fw-bold text-uppercase bg-light small">{{ $role }}</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="permissions[{{ $role }}][Recruitment]" class="form-check-input" 
                                            {{ isset($permissions[$role]['Recruitment']) && $permissions[$role]['Recruitment'] ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="permissions[{{ $role }}][Assets]" class="form-check-input" 
                                            {{ isset($permissions[$role]['Assets']) && $permissions[$role]['Assets'] ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="permissions[{{ $role }}][Settings]" class="form-check-input" 
                                            {{ isset($permissions[$role]['Settings']) && $permissions[$role]['Settings'] ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="permissions[{{ $role }}][Payroll]" class="form-check-input" 
                                            {{ isset($permissions[$role]['Payroll']) && $permissions[$role]['Payroll'] ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Permission Matrix</button>
                    <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
