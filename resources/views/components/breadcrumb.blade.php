@props([
    'items' => [] // Array of ['label' => '...', 'url' => '...']
])

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0 small">
        @foreach($items as $item)
            @if(!$loop->last && isset($item['url']))
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] }}" class="text-decoration-none text-secondary hover-primary fw-medium">{{ $item['label'] }}</a>
                </li>
            @else
                <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
                    {{ $item['label'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
