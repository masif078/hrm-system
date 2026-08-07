@extends('layouts.app')

@section('title', 'Interviews Schedule')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Interviews Schedule</h3>
            <p class="text-muted mb-0">List of scheduled applicant calls, meetings, and evaluations.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Candidate</th>
                            <th>Job Post</th>
                            <th>Interview Date</th>
                            <th>Time</th>
                            <th>Interviewer</th>
                            <th>Meeting Link</th>
                            <th class="pe-4" width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interviews as $interview)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $interview->application->candidate->full_name }}</span>
                                    <small class="text-muted d-block">{{ $interview->application->candidate->email }}</small>
                                </td>
                                <td>{{ $interview->application->jobOpening->title }}</td>
                                <td>{{ date('M d, Y', strtotime($interview->date)) }}</td>
                                <td>{{ $interview->time }}</td>
                                <td>{{ $interview->interviewer->first_name ?? '' }} {{ $interview->interviewer->last_name ?? 'N/A' }}</td>
                                <td>
                                    @if($interview->meeting_link)
                                        <a href="{{ $interview->meeting_link }}" target="_blank" class="text-primary text-decoration-none">Join Meeting</a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <a href="{{ route('applications.show', $interview->application_id) }}" class="btn btn-outline-primary btn-sm">Process Application</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No scheduled interviews registered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
