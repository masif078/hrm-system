@props([
    'title' => 'Total Salary Paid',
    'value' => 'PKR 0.00',
    'subtitle' => 'This Month',
    'icon' => 'bi-wallet2',
    'color' => 'blue', // blue, green, purple
])

@php
    $colorConfig = match($color) {
        'green' => [
            'bg' => '#ECFDF5', 
            'iconColor' => '#10B981',
            'stroke' => '#10B981',
            'points' => '0,25 12,18 24,22 36,10 48,15 60,8 72,12 84,4 96,8'
        ],
        'purple' => [
            'bg' => '#F5F3FF', 
            'iconColor' => '#8B5CF6',
            'stroke' => '#8B5CF6',
            'points' => '0,22 12,25 24,15 36,20 48,8 60,16 72,8 84,18 96,6'
        ],
        default => [
            'bg' => '#EFF6FF', 
            'iconColor' => '#3B82F6',
            'stroke' => '#3B82F6',
            'points' => '0,24 12,15 24,20 36,8 48,16 60,6 72,10 84,4 96,8'
        ],
    };
@endphp

<div class="card border-0 shadow-sm rounded-3 bg-white h-100 p-3 stat-card-hover transition-all">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2.5">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background-color: {{ $colorConfig['bg'] }}; color: {{ $colorConfig['iconColor'] }}; font-size: 1.15rem;">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div>
                <span class="text-secondary fw-semibold small d-block mb-0.5" style="font-size: 0.75rem;">{{ $title }}</span>
                <h4 class="fw-bold text-dark mb-0 tracking-tight" style="font-size: 1.25rem;">{{ $value }}</h4>
                <small class="text-muted" style="font-size: 0.68rem;">{{ $subtitle }}</small>
            </div>
        </div>

        {{-- Compact Sparkline SVG Chart --}}
        <div class="d-none d-sm-block flex-shrink-0 ms-2">
            <svg width="96" height="30" viewBox="0 0 96 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polyline
                    fill="none"
                    stroke="{{ $colorConfig['stroke'] }}"
                    stroke-width="2.2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    points="{{ $colorConfig['points'] }}"
                />
            </svg>
        </div>
    </div>
</div>
