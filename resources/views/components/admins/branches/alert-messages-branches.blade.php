<div class="error-messages">
    @if (session('success'))
        <div class="alert-success-message">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert-error-message">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->has('error'))
        <div class="alert-error-message">
            {{ $errors->first('error') }}
        </div>
    @endif
    @if ($errors->has('branche_name'))
        <div class="alert-error-message">
            {{ $errors->first('branche_name') }}
        </div>
    @endif
</div>
{{ $slot }}
