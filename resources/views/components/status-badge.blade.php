@props([
    'status' => 'Active',
])

@php
    $normalized = strtolower(trim($status));
    
    $badgeStyle = match($normalized) {
        'present', 'approved', 'active', 'completed', 'hired' => [
            'bg' => '#DCFCE7',
            'text' => '#15803D',
        ],
        'absent', 'rejected', 'inactive', 'cancelled' => [
            'bg' => '#FEE2E2',
            'text' => '#B91C1C',
        ],
        'late', 'pending', 'on hold' => [
            'bg' => '#FEF3C7',
            'text' => '#B45309',
        ],
        'in progress', 'doing', 'applied', 'shortlisted' => [
            'bg' => '#E0F2FE',
            'text' => '#0369A1',
        ],
        'leave' => [
            'bg' => '#E0E7FF',
            'text' => '#4338CA',
        ],
        default => [
            'bg' => '#F1F5F9',
            'text' => '#475569',
        ],
    };
@endphp

<span class="badge rounded-pill fw-bold px-3 py-1.5 align-middle" style="background-color: {{ $badgeStyle['bg'] }}; color: {{ $badgeStyle['text'] }}; font-size: 0.76rem; letter-spacing: 0.2px;">
    {{ $status }}
</span>
