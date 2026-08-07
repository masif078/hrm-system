@extends('layouts.app')

@section('title', 'Company Policies')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Company Policies</h3>
            <p class="text-muted mb-0">Manage standard office policies, rules, and procedures.</p>
        </div>
        <a href="{{ route('company-policies.create') }}" class="btn btn-primary">Create Policy</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($policies as $policy)
            <div class="col-md-6 col-xxl-4">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="badge bg-light text-dark border align-self-start mb-2">{{ $policy->type }}</span>
                        <h5 class="fw-bold text-dark mb-2">{{ $policy->title }}</h5>
                        <p class="text-muted small flex-grow-1" style="white-space: pre-line;">{{ Str::limit($policy->content, 200) }}</p>
                        
                        <div class="d-flex gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('company-policies.edit', $policy->id) }}" class="btn btn-outline-secondary btn-sm flex-fill">Edit Policy</a>
                            <form action="{{ route('company-policies.destroy', $policy->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this policy?')" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-white p-4 text-center text-muted">
                    No company policies recorded.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
