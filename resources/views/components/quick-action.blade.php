@props([
    'title' => 'Action Title',
    'subtitle' => 'Action description',
    'icon' => 'bi-plus-lg',
    'color' => 'blue', // blue, green, orange, purple
    'link' => '#',
])

@php
    $colorConfig = match($color) {
        'green' => ['bg' => '#ECFDF5', 'iconBg' => '#10B981', 'text' => '#059669'],
        'orange' => ['bg' => '#FFF7ED', 'iconBg' => '#F97316', 'text' => '#C2410C'],
        'purple' => ['bg' => '#F5F3FF', 'iconBg' => '#8B5CF6', 'text' => '#6D28D9'],
        default => ['bg' => '#EFF6FF', 'iconBg' => '#3B82F6', 'text' => '#1D4ED8'],
    };

    $hrefVal = $link;
    if (str_contains($link, 'javascript') || $link === '#') {
        $hrefVal = '#';
    }
@endphp

<a href="{{ $hrefVal }}" {{ $attributes->merge(['class' => 'card border-0 shadow-sm rounded-3 p-2.5 text-decoration-none transition-all quick-action-card d-block h-100 text-start w-100']) }} style="background-color: {{ $colorConfig['bg'] }}; cursor: pointer;">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2.5">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-2xs flex-shrink-0" style="width: 38px; height: 38px; background-color: {{ $colorConfig['iconBg'] }}; font-size: 1rem;">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0" style="color: {{ $colorConfig['text'] }}; font-size: 0.88rem;">{{ $title }}</h6>
                <small class="text-muted d-block" style="font-size: 0.7rem;">{{ $subtitle }}</small>
            </div>
        </div>
        <span class="text-secondary small fw-bold px-1">&rarr;</span>
    </div>
</a>

<style>
    .quick-action-card {
        border: 1px solid rgba(0, 0, 0, 0.03) !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }
    .quick-action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px -3px rgba(0, 0, 0, 0.06) !important;
    }
</style>
