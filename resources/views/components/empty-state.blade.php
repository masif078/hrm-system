@props([
    'title' => 'No Data Found',
    'icon' => 'bi-folder2-open',
])

<div class="text-center py-5 px-3 bg-white rounded-4 border border-light-subtle">
    <div class="d-inline-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mb-3" style="width: 54px; height: 54px; font-size: 1.25rem;">
        <i class="bi {{ $icon }}"></i>
    </div>
    <p class="text-muted small fw-medium mb-0">{{ $title }}</p>
</div>
