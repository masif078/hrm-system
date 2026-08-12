@props([
    'name' => 'User Name',
    'size' => 'md', // sm, md, lg
    'src' => null,
])

@php
    $words = explode(' ', trim($name));
    $initials = '';
    if (count($words) >= 2) {
        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[count($words) - 1], 0, 1));
    } else {
        $initials = strtoupper(substr($name, 0, 2));
    }

    $dim = match($size) {
        'sm' => ['wh' => '32px', 'fs' => '0.75rem'],
        'lg' => ['wh' => '72px', 'fs' => '1.5rem'],
        default => ['wh' => '44px', 'fs' => '0.95rem'],
    };
@endphp

@if($src)
    <img src="{{ asset('storage/' . $src) }}" alt="{{ $name }}" class="rounded-circle shadow-sm border border-2 border-white" style="width: {{ $dim['wh'] }}; height: {{ $dim['wh'] }}; object-fit: cover;">
@else
    <div class="rounded-circle d-flex align-items-center justify-content-center fw-extrabold text-white shadow-sm border border-2 border-white" style="width: {{ $dim['wh'] }}; height: {{ $dim['wh'] }}; font-size: {{ $dim['fs'] }}; background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);">
        {{ $initials }}
    </div>
@endif
