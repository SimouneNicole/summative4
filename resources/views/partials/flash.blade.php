@if (session('success'))
    <div class="glass-flash glass-flash-success" role="status" aria-live="polite">
        <span class="flash-dot" aria-hidden="true"></span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="glass-flash glass-flash-error" role="alert">
        <span class="flash-dot" aria-hidden="true"></span>
        <span>{{ session('error') }}</span>
    </div>
@endif
