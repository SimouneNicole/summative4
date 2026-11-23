@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'glass-flash glass-flash-success']) }}>
        <span class="flash-dot" aria-hidden="true"></span>
        <span>{{ $status }}</span>
    </div>
@endif
