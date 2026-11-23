@if ($errors->any())
    <div id="form-error-summary" class="glass-error-summary" role="alert" aria-live="assertive" tabindex="-1">
        <p class="error-summary-title">
            Please resolve {{ $errors->count() === 1 ? 'the following error' : 'the following ' . $errors->count() . ' errors' }}:
        </p>
        <ul class="error-summary-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
