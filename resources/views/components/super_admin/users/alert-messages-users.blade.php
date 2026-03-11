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
    @if ($errors->has('username'))
        <div class="alert-error-message">
            {{ $errors->first('username') }}
        </div>
    @endif
    @if ($errors->has('email'))
        <div class="alert-error-message">
            {{ $errors->first('email') }}
        </div>
    @endif

    @if ($errors->has('password'))
        <div class="alert-error-message">
            {{ $errors->first('password') }}
        </div>
    @endif
    @if ($errors->has('role'))
        <div class="alert-error-message">
            {{ $errors->first('role') }}
        </div>
    @endif
</div>
{{ $slot }}
