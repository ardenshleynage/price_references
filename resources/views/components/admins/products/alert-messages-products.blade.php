<div class="error-messages">
    {{-- Message de succès --}}
    @if (session('success'))
        <div class="alert-success-message">
            {{ session('success') }}
        </div>
    @endif
    {{-- Message d'erreur --}}
    @if (session('error'))
        <div class="alert-error-message">
            {{ session('error') }}
        </div>
    @endif
    {{-- Erreurs générales --}}
    @if ($errors->has('error'))
        <div class="alert-error-message">
            {{ $errors->first('error') }}
        </div>
    @endif
    {{-- Erreur sur le nom de la catgorie --}}
    @if ($errors->has('product_name'))
        <div class="alert-error-message">
            {{ $errors->first('product_name') }}
        </div>
    @endif
</div>
{{ $slot }}
