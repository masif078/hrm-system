@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Hiring Pipeline Process</h3>
            <p class="text-muted mb-0">Manage candidate assessment, schedule interviews, score feedback, and issue offers.</p>
        </div>
        <a href="{{ route('applications.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- Left Column: Candidate & Application Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Candidate Profile</h5>
                </div>
                <div class="card-body">
                    <h4 class="fw-bold text-primary mb-1">{{ $application->candidate->full_name }}</h4>
                    <p class="text-muted mb-3">{{ $application->candidate->email }}</p>

                    <table class="table table-sm table-borderless text-dark">
                        <tr>
                            <td class="fw-semibold text-muted" width="120">Phone:</td>
                            <td>{{ $application->candidate->phone ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Experience:</td>
                            <td>{{ $application->candidate->experience }} years</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Qualification:</td>
                            <td>{{ $application->candidate->qualification ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Skills:</td>
                            <td>{{ $application->candidate->skills ?: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Source:</td>
                            <td>{{ $application->candidate->source }}</td>
                        </tr>
                    </table>

                    @if($application->candidate->resume)
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $application->candidate->resume) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">View Uploaded Resume</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-white">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0">Hiring Stage Control</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted d-block small mb-1">Current Stage</span>
                        <span class="badge bg-light text-dark border border-secondary fw-semibold fs-6 py-2 px-3">
                            {{ $application->status }}
                        </span>
                    </div>

                    <form action="{{ route('applications.status', $application->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Transition Stage To:</label>
                            <select name="status" id="status" class="form-select" required>
                                @foreach(['Applied', 'Shortlisted', 'HR Interview', 'Technical Interview', 'Final Interview', 'Offer Sent', 'Accepted', 'Rejected', 'Hired'] as $stage)
                                    <option value="{{ $stage }}" {{ $application->status === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Stage</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Activity, Interviews, & Offer Letters --}}
        <div class="col-lg-8">
            {{-- Pipeline Progression indicator --}}
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Recruitment Progression Status</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(['Applied', 'Shortlisted', 'HR Interview', 'Technical Interview', 'Final Interview', 'Offer Sent', 'Accepted', 'Rejected', 'Hired'] as $index => $stage)
                            <div class="p-2 border rounded text-center small flex-fill {{ $application->status === $stage ? 'bg-primary text-white border-primary fw-bold' : 'bg-light text-muted' }}">
                                {{ $stage }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Interviews --}}
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Scheduled Interviews</h5>
                    <a href="{{ route('interviews.create') }}?application_id={{ $application->id }}" class="btn btn-outline-primary btn-sm">Schedule Interview</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Date/Time</th>
                                    <th>Interviewer</th>
                                    <th>Meeting Link / Notes</th>
                                    <th class="pe-4" width="180">Feedback Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($application->interviews as $interview)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-semibold">{{ date('M d, Y', strtotime($interview->date)) }}</span>
                                            <small class="text-muted d-block">{{ $interview->time }}</small>
                                        </td>
                                        <td>{{ $interview->interviewer->first_name ?? '' }} {{ $interview->interviewer->last_name ?? 'N/A' }}</td>
                                        <td>
                                            @if($interview->meeting_link)
                                                <a href="{{ $interview->meeting_link }}" target="_blank" class="text-primary text-decoration-none d-block">Join Call</a>
                                            @endif
                                            <small class="text-muted">{{ $interview->notes ?: 'No notes' }}</small>
                                        </td>
                                        <td class="pe-4">
                                            @if($interview->feedback)
                                                <div class="text-dark small">
                                                    Technical: <strong>{{ $interview->feedback->rating_technical }}/5</strong><br>
                                                    Communication: <strong>{{ $interview->feedback->rating_communication }}/5</strong><br>
                                                    Overall: <strong>{{ $interview->feedback->rating_overall }}/5</strong>
                                                </div>
                                            @else
                                                <a href="{{ route('interviews.feedback', $interview->id) }}" class="btn btn-outline-success btn-sm">Leave Feedback</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No interviews scheduled.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Offer Letter Section --}}
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Offer Letter</h5>
                    @if(!$application->offerLetter)
                        <a href="{{ route('offer-letters.create') }}?application_id={{ $application->id }}" class="btn btn-outline-primary btn-sm">Generate Offer Letter</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($application->offerLetter)
                        <div class="p-3 border rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold text-dark mb-1">Offer Generated - PKR {{ number_format($application->offerLetter->salary_offered, 2) }}</h6>
                                    <p class="text-muted small mb-0">Joining Date: {{ date('M d, Y', strtotime($application->offerLetter->joining_date)) }} | Sent: {{ date('M d, Y', strtotime($application->offerLetter->sent_date)) }}</p>
                                    <div class="mt-2">
                                        <span class="badge {{ $application->offerLetter->status === 'Accepted' ? 'bg-success' : ($application->offerLetter->status === 'Rejected' ? 'bg-danger' : 'bg-warning') }}">
                                            Offer Status: {{ $application->offerLetter->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('offer-letters.show', $application->offerLetter->id) }}" class="btn btn-primary btn-sm">View & Print Offer</a>
                                        @if($application->offerLetter->status === 'Pending')
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('offer-letters.status', $application->offerLetter->id) }}" method="POST" class="flex-fill">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Accepted">
                                                    <button type="submit" class="btn btn-success btn-sm w-100">Accept</button>
                                                </form>
                                                <form action="{{ route('offer-letters.status', $application->offerLetter->id) }}" method="POST" class="flex-fill">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Rejected">
                                                    <button type="submit" class="btn btn-danger btn-sm w-100">Reject</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">No offer letter generated for this applicant yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
