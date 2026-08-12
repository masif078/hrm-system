@props([
    'title' => 'Stat Title',
    'value' => '0',
    'icon' => 'bi-people-fill',
    'color' => 'blue', // blue, green, amber, purple
    'trend' => null,
    'trendType' => 'up', // up, neutral, down
    'link' => null,
])

@php
    $colorClasses = match($color) {
        'green' => ['bg' => '#ECFDF5', 'text' => '#10B981'],
        'amber' => ['bg' => '#FFF7ED', 'text' => '#F97316'],
        'purple' => ['bg' => '#F5F3FF', 'text' => '#8B5CF6'],
        default => ['bg' => '#EFF6FF', 'text' => '#3B82F6'],
    };

    $trendBadgeClass = match($trendType) {
        'down' => 'bg-danger-subtle text-danger',
        'neutral' => 'bg-secondary-subtle text-secondary',
        default => 'bg-success-subtle text-success',
    };

    $trendIcon = match($trendType) {
        'down' => '↓',
        'neutral' => '—',
        default => '↑',
    };

    $tag = $link ? 'a' : 'div';
@endphp

<{{ $tag }} @if($link) href="{{ $link }}" @endif class="card border-0 shadow-sm rounded-3 bg-white h-100 p-3 text-decoration-none stat-card-interactive transition-all d-flex flex-column justify-content-between">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background-color: {{ $colorClasses['bg'] }}; color: {{ $colorClasses['text'] }}; font-size: 1.15rem;">
            <i class="bi {{ $icon }}"></i>
        </div>
        <div class="text-end">
            <span class="text-secondary fw-semibold d-block mb-0.5 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ $title }}</span>
            <h3 class="fw-extrabold text-dark mb-0 tracking-tight" style="font-size: 1.5rem;">{{ $value }}</h3>
        </div>
    </div>

    @if($trend)
        <div class="d-flex align-items-center gap-1 pt-2 mt-auto border-top border-light-subtle" style="font-size: 0.75rem;">
            <span class="badge {{ $trendBadgeClass }} px-2 py-0.5 rounded-pill fw-semibold" style="font-size: 0.7rem;">
                {{ $trendIcon }} {{ $trend }}
            </span>
            <span class="text-muted" style="font-size: 0.7rem;">vs last month</span>
        </div>
    @endif
</{{ $tag }}>

<style>
    .stat-card-interactive {
        border: 1px solid #F1F5F9 !important;
        box-shadow: 0 2px 10px -2px rgba(0, 0, 0, 0.03) !important;
        cursor: pointer !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    }
    .stat-card-interactive:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 24px -4px rgba(0, 0, 0, 0.08) !important;
    }
</style>
