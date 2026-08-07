@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Account Profile Settings</h2>
        <p class="text-muted">Manage your personal details, profile picture, and account security</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            Password updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any() || $errors->updatePassword->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Please correct the errors in the form.</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Profile Sidebar --}}
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    {{-- Avatar --}}
                    <div class="position-relative d-inline-block mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle shadow" style="width: 130px; height: 130px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow mx-auto" style="width: 130px; height: 130px; font-size: 3.5rem;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="fw-bold mb-1 text-dark">{{ $user->name }}</h4>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 mb-3 text-uppercase">
                        {{ $user->role }}
                    </span>
                    
                    <p class="text-muted mb-0 small"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</p>
                    <p class="text-muted mb-0 small"><i class="bi bi-clock me-1"></i> Registered {{ $user->created_at->format('M d, Y') }}</p>
                    
                    <hr class="my-4 text-muted opacity-25">
                    
                    <div class="text-start">
                        <h6 class="fw-bold text-dark mb-3">System Information</h6>
                        @if($user->role === 'employee' && $user->employee)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary small">Employee ID:</span>
                                <span class="fw-bold small text-dark">{{ $user->employee->employee_id }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary small">Department:</span>
                                <span class="fw-bold small text-dark">{{ $user->employee->department->name ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary small">Designation:</span>
                                <span class="fw-bold small text-dark">{{ $user->employee->designation->name ?? 'N/A' }}</span>
                            </div>
                        @elseif($user->role === 'client' && $user->client)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary small">Company:</span>
                                <span class="fw-bold small text-dark">{{ $user->client->company_name }}</span>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary small">Role Level:</span>
                                <span class="fw-bold small text-dark text-capitalize">{{ $user->role }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Fields --}}
        <div class="col-xl-8 col-lg-7">
            {{-- Card 1: Details Update --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-person-gear me-2 text-primary"></i>Personal Information</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            {{-- Avatar File Input --}}
                            <div class="col-12 mb-4">
                                <label for="avatar" class="form-label fw-semibold text-secondary">Change Profile Photo</label>
                                <input class="form-control @error('avatar') is-invalid @enderror" type="file" id="avatar" name="avatar">
                                <small class="text-muted d-block mt-1">Acceptable formats: jpeg, png, jpg, gif, svg. Max size: 2MB.</small>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($user->role === 'employee' && $user->employee)
                                {{-- Employee fields --}}
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label fw-semibold text-secondary">First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->employee->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label fw-semibold text-secondary">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->employee->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- System name synchronization --}}
                                <input type="hidden" name="name" value="{{ $user->name }}">
                            @elseif($user->role === 'client' && $user->client)
                                {{-- Client fields --}}
                                <div class="col-md-6 mb-3">
                                    <label for="contact_person" class="form-label fw-semibold text-secondary">Contact Person Name</label>
                                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', $user->client->contact_person) }}" required>
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label fw-semibold text-secondary">Company Name</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $user->client->company_name) }}" required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- System name synchronization --}}
                                <input type="hidden" name="name" value="{{ $user->name }}">
                            @else
                                {{-- General Admin --}}
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label fw-semibold text-secondary">Display Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            {{-- Email (common) --}}
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold text-secondary">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phone (Employee or Client) --}}
                            @if(($user->role === 'employee' && $user->employee) || ($user->role === 'client' && $user->client))
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-semibold text-secondary">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->role === 'employee' ? $user->employee->phone : $user->client->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            {{-- Address (Client) --}}
                            @if($user->role === 'client' && $user->client)
                                <div class="col-12 mb-3">
                                    <label for="address" class="form-label fw-semibold text-secondary">Billing Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->client->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card 2: Security Change Password --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-shield-lock me-2 text-primary"></i>Change Password</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="current_password" class="form-label fw-semibold text-secondary">Current Password</label>
                                <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" autocomplete="current-password" required>
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold text-secondary">New Password</label>
                                <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" autocomplete="new-password" required>
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold text-secondary">Confirm New Password</label>
                                <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                                @error('password_confirmation', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-key me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
