@extends('layouts.app')

@section('title', 'System Settings & Health')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">System Settings & Health</h3>
        <p class="text-muted">Configure profile rules, SMTP parameters, and check system environment health diagnostics.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- Left: Settings Forms --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Company Profile Settings</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('settings.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="company_name" class="form-label fw-semibold">Company Name</label>
                                <input type="text" name="company_name" id="company_name" class="form-control" value="{{ $companyName }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="company_email" class="form-label fw-semibold">Contact Email</label>
                                <input type="email" name="company_email" id="company_email" class="form-control" value="{{ $companyEmail }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="company_phone" class="form-label fw-semibold">Contact Phone</label>
                                <input type="text" name="company_phone" id="company_phone" class="form-control" value="{{ $companyPhone }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="company_address" class="form-label fw-semibold">Office Address</label>
                                <input type="text" name="company_address" id="company_address" class="form-control" value="{{ $companyAddress }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="company_timezone" class="form-label fw-semibold">System Timezone</label>
                                <select name="company_timezone" id="company_timezone" class="form-select" required>
                                    @foreach(timezone_identifiers_list() as $tz)
                                        <option value="{{ $tz }}" {{ $companyTimezone === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="company_currency" class="form-label fw-semibold">System Currency</label>
                                <input type="text" name="company_currency" id="company_currency" class="form-control" value="{{ $companyCurrency }}" required>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-3 border-top pt-3">SMTP Mail Settings</h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label for="smtp_host" class="form-label fw-semibold">SMTP Host</label>
                                <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="{{ $smtpHost }}">
                            </div>
                            <div class="col-md-4">
                                <label for="smtp_port" class="form-label fw-semibold">SMTP Port</label>
                                <input type="text" name="smtp_port" id="smtp_port" class="form-control" value="{{ $smtpPort }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right: System Health Diagnostics Panel --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">System Health Panel</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3 p-3 border rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-dark">Database Connection</span>
                            <span class="badge {{ $health['db_status'] === 'Connected' ? 'bg-success' : 'bg-danger' }}">
                                {{ $health['db_status'] }}
                            </span>
                        </div>
                        <small class="text-muted d-block">Query Execution Latency: <strong>{{ $health['db_latency'] }}</strong></small>
                    </div>

                    <div class="mb-3 p-3 border rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-dark">Storage Disk Space</span>
                            <span class="badge bg-primary text-white">{{ $health['storage'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ str_contains($health['storage'], '(') ? explode('(', str_replace('%)', '', $health['storage']))[1] : '0' }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3 p-3 border rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-dark">SMTP Configuration</span>
                            <span class="badge {{ $health['mail_status'] === 'Configured' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $health['mail_status'] }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3 p-3 border rounded bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-dark">Backup Directory</span>
                            <span class="badge bg-secondary text-white">{{ $health['backup_status'] }}</span>
                        </div>
                    </div>

                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold text-dark mb-2">Environment Details</h6>
                        <table class="table table-sm table-borderless mb-0 small text-dark">
                            <tr>
                                <td class="text-muted">PHP Version:</td>
                                <td><strong>{{ $health['php_version'] }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Laravel Version:</td>
                                <td><strong>{{ $health['laravel_version'] }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Active System Users:</td>
                                <td><strong>{{ $health['active_users'] }} registered</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
