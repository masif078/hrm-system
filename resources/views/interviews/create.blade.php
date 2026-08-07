@extends('layouts.app')

@section('title', 'Schedule Interview')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Schedule Interview</h3>
        <p class="text-muted">Set up meeting time and assign interviewer for **{{ $application->candidate->full_name }}**.</p>
    </div>

    <div class="card border-0 shadow-sm bg-white" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="{{ route('interviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="application_id" value="{{ $application->id }}">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="date" class="form-label fw-semibold">Interview Date</label>
                        <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="time" class="form-label fw-semibold">Meeting Time</label>
                        <input type="time" name="time" id="time" class="form-control @error('time') is-invalid @enderror" value="{{ old('time') }}" required>
                        @error('time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="interviewer_id" class="form-label fw-semibold">Assign Interviewer</label>
                    <select name="interviewer_id" id="interviewer_id" class="form-select @error('interviewer_id') is-invalid @enderror" required>
                        <option value="">Select Interviewer</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('interviewer_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->department->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('interviewer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="meeting_link" class="form-label fw-semibold">Online Meeting Link (Optional)</label>
                    <input type="url" name="meeting_link" id="meeting_link" class="form-control @error('meeting_link') is-invalid @enderror" value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/xxx">
                    @error('meeting_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-semibold">Interview Notes / Instructions</label>
                    <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') is-invalid @enderror" placeholder="Topics to cover, instructions for interviewer...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Schedule Meeting</button>
                    <a href="{{ route('applications.show', $application->id) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
