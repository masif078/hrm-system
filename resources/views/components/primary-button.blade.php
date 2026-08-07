<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-laravel-primary px-4 py-2 rounded-3 text-sm font-bold tracking-wide transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
