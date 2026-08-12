@extends('layouts.app')

@section('title', 'System Settings & Health')

@section('content')

<div class="container-fluid px-0">

    {{-- Breadcrumbs --}}
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'System Settings & Health']
    ]" />

    <div id="alertContainer">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Header Banner Card --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">System Settings & Health Dashboard</h3>
                <p class="text-secondary small mb-0">Configure company organization profiles, SMTP mail delivery parameters, and monitor infrastructure health diagnostics.</p>
            </div>
        </div>
    </div>

    {{-- 2-Column SaaS Grid Layout --}}
    <div class="row g-4">
        
        {{-- Left Column: Company Profile & SMTP Settings Form --}}
        <div class="col-lg-7">
            <div class="card border border-light-subtle shadow-sm rounded-4 bg-white h-100">
                <div class="card-header bg-white border-bottom border-light-subtle px-4 py-3.5 d-flex align-items-center gap-2">
                    <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-sliders fs-6"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-0">Company Profile & System Settings</h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('settings.store') }}" method="POST">
                        @csrf

                        {{-- Section 1: Organization Details --}}
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary border-bottom border-primary-subtle pb-2 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <i class="bi bi-building-fill me-1"></i> Section 1: Organization Profile
                            </h6>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label fw-bold text-dark small">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" id="company_name" class="form-control rounded-3 border-light-subtle py-2 px-3" value="{{ $companyName }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="company_email" class="form-label fw-bold text-dark small">Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" name="company_email" id="company_email" class="form-control rounded-3 border-light-subtle py-2 px-3" value="{{ $companyEmail }}" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="company_phone" class="form-label fw-bold text-dark small">Contact Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="company_phone" id="company_phone" class="form-control rounded-3 border-light-subtle py-2 px-3" value="{{ $companyPhone }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="company_address" class="form-label fw-bold text-dark small">Office Address <span class="text-danger">*</span></label>
                                    <input type="text" name="company_address" id="company_address" class="form-control rounded-3 border-light-subtle py-2 px-3" value="{{ $companyAddress }}" required>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="company_timezone" class="form-label fw-bold text-dark small">System Timezone <span class="text-danger">*</span></label>
                                    <select name="company_timezone" id="company_timezone" class="form-select rounded-3 border-light-subtle py-2 px-3" required>
                                        @foreach(timezone_identifiers_list() as $tz)
                                            <option value="{{ $tz }}" {{ $companyTimezone === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="company_currency" class="form-label fw-bold text-dark small">System Currency Symbol <span class="text-danger">*</span></label>
                                    <input type="text" name="company_currency" id="company_currency" class="form-control rounded-3 border-light-subtle py-2 px-3" value="{{ $companyCurrency }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: SMTP Parameters --}}
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary border-bottom border-primary-subtle pb-2 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <i class="bi bi-envelope-check-fill me-1"></i> Section 2: SMTP Mail Delivery Settings
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="smtp_host" class="form-label fw-bold text-dark small">SMTP Server Host</label>
                                    <input type="text" name="smtp_host" id="smtp_host" class="form-control rounded-3 border-light-subtle py-2 px-3" value="{{ $smtpHost }}" placeholder="e.g. smtp.mailtrap.io">
                                </div>
                                <div class="col-md-4">
                                    <label for="smtp_port" class="form-label fw-bold text-dark small">SMTP Port</label>
                                    <input type="text" name="smtp_port" id="smtp_port" class="form-control rounded-3 border-light-subtle py-2 px-3" value="{{ $smtpPort }}" placeholder="e.g. 587 or 2525">
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-3 border-top border-light-subtle d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary rounded-3 px-4 py-2.5 fw-bold text-white shadow-sm">
                                <i class="bi bi-floppy-fill me-1.5"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: System Health Panel & Environment Details --}}
        <div class="col-lg-5">
            <div class="d-flex flex-column gap-4">

                {{-- System Health Panel Card --}}
                <div class="card border border-light-subtle shadow-sm rounded-4 bg-white">
                    <div class="card-header bg-white border-bottom border-light-subtle px-4 py-3.5 d-flex align-items-center gap-2">
                        <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-heart-pulse-fill fs-6"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">System Health Panel</h5>
                    </div>

                    <div class="card-body p-4 d-flex flex-column gap-3">

                        {{-- 1. Database Connection --}}
                        <div class="p-3 border border-light-subtle rounded-3 bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-database-fill-check text-primary fs-5"></i>
                                    <span class="fw-bold text-dark small">Database Connection</span>
                                </div>
                                @if($health['db_status'] === 'Connected')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold fs-7">
                                        <i class="bi bi-check-circle-fill me-1"></i> Connected
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fw-bold fs-7">
                                        <i class="bi bi-x-circle-fill me-1"></i> Disconnected
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-secondary small">
                                <span>Query Execution Latency:</span>
                                <span class="bg-white border border-light-subtle rounded-3 px-2 py-0.5 font-monospace fw-bold text-dark fs-7">
                                    {{ $health['db_latency'] }}
                                </span>
                            </div>
                        </div>

                        {{-- 2. Storage Disk Space with Thinner Progress Bar & Color Logic --}}
                        @php
                            $storageVal = 0;
                            if (str_contains($health['storage'], '(')) {
                                $parts = explode('(', str_replace('%)', '', $health['storage']));
                                if (isset($parts[1])) {
                                    $storageVal = floatval($parts[1]);
                                }
                            }
                            $progressBarClass = 'bg-primary';
                            if ($storageVal > 95) {
                                $progressBarClass = 'bg-danger';
                            } elseif ($storageVal > 80) {
                                $progressBarClass = 'bg-warning';
                            }
                        @endphp
                        <div class="p-3 border border-light-subtle rounded-3 bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-hdd-network-fill text-primary fs-5"></i>
                                    <span class="fw-bold text-dark small">Storage Disk Space</span>
                                </div>
                                <span class="bg-white border border-light-subtle text-dark rounded-pill px-3 py-1 font-monospace fw-bold fs-7">
                                    {{ $health['storage'] }}
                                </span>
                            </div>
                            <div class="progress rounded-pill bg-secondary-subtle mb-1" style="height: 8px;">
                                <div class="progress-bar {{ $progressBarClass }} rounded-pill" role="progressbar" style="width: {{ $storageVal }}%" aria-valuenow="{{ $storageVal }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        {{-- 3. SMTP Mail Configuration --}}
                        <div class="p-3 border border-light-subtle rounded-3 bg-light-subtle d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-cloud-arrow-up-fill text-primary fs-5"></i>
                                <span class="fw-bold text-dark small">SMTP Mail Configuration</span>
                            </div>
                            @if($health['mail_status'] === 'Configured')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold fs-7">
                                    <i class="bi bi-check-circle-fill me-1"></i> Configured
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-bold fs-7">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $health['mail_status'] }}
                                </span>
                            @endif
                        </div>

                        {{-- 4. Backup Directory --}}
                        <div class="p-3 border border-light-subtle rounded-3 bg-light-subtle d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-folder-symlink-fill text-primary fs-5"></i>
                                <span class="fw-bold text-dark small">Backup Directory</span>
                            </div>
                            @if(str_contains($health['backup_status'], 'Active'))
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold fs-7">
                                    <i class="bi bi-check-circle-fill me-1"></i> {{ $health['backup_status'] }}
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1 fw-bold fs-7">
                                    {{ $health['backup_status'] }}
                                </span>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- Environment Details Card --}}
                <div class="card border border-light-subtle shadow-sm rounded-4 bg-white">
                    <div class="card-header bg-white border-bottom border-light-subtle px-4 py-3.5 d-flex align-items-center gap-2">
                        <div class="bg-info-subtle text-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-cpu-fill fs-6"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Environment Details</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-2.5">
                            {{-- PHP Version --}}
                            <div class="d-flex justify-content-between align-items-center p-2.5 border border-light-subtle rounded-3 bg-light-subtle">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-code-slash text-primary fs-6 me-2.5"></i>
                                    <span class="text-secondary small fw-semibold">PHP Version</span>
                                </div>
                                <span class="bg-light border border-light-subtle rounded-3 px-2.5 py-1 font-monospace fs-7 fw-bold text-dark">
                                    v{{ $health['php_version'] }}
                                </span>
                            </div>

                            {{-- Laravel Version --}}
                            <div class="d-flex justify-content-between align-items-center p-2.5 border border-light-subtle rounded-3 bg-light-subtle">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-layers-fill text-primary fs-6 me-2.5"></i>
                                    <span class="text-secondary small fw-semibold">Laravel Framework</span>
                                </div>
                                <span class="bg-light border border-light-subtle rounded-3 px-2.5 py-1 font-monospace fs-7 fw-bold text-dark">
                                    v{{ $health['laravel_version'] }}
                                </span>
                            </div>

                            {{-- Active System Users --}}
                            <div class="d-flex justify-content-between align-items-center p-2.5 border border-light-subtle rounded-3 bg-light-subtle">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-people-fill text-primary fs-6 me-2.5"></i>
                                    <span class="text-secondary small fw-semibold">Active System Users</span>
                                </div>
                                <span class="bg-light border border-light-subtle rounded-3 px-2.5 py-1 font-monospace fs-7 fw-bold text-dark">
                                    {{ $health['active_users'] }} registered
                                </span>
                            </div>

                            {{-- Server OS --}}
                            <div class="d-flex justify-content-between align-items-center p-2.5 border border-light-subtle rounded-3 bg-light-subtle">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-pc-display text-primary fs-6 me-2.5"></i>
                                    <span class="text-secondary small fw-semibold">Server Architecture</span>
                                </div>
                                <span class="bg-light border border-light-subtle rounded-3 px-2.5 py-1 font-monospace fs-7 fw-bold text-dark">
                                    {{ PHP_OS_FAMILY }} ({{ PHP_INT_SIZE * 8 }}-bit)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection
