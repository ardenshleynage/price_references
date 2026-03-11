<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit</title>
    @vite(['resources/css/login.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.userTheme = "{{ session('theme', 'light') }}";
        if (window.userTheme === "dark") {
            document.documentElement.classList.add("dark");
        }
    </script>
</head>

<body>
    <a class="back_button" href="{{ $q ? url('/admins_search?q=' . $q) : url('/admins_search') }}">Retour</a>
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Modifier un produit</h2>

        <form method="POST" action="{{ route('admins.products.update_from_search') }}" class="login-container">
            @csrf
            <input type="hidden" name="prod_id" value="{{ $product->id }}">
            <input type="hidden" name="q" value="{{ $q }}">

            <p>
                <label for="product_name">Nom du produit :</label>
                <input type="text" name="product_name" value="{{ $product->product_name }}" required>
            </p>

            <p>
                <label for="single_price">Prix unitaire</label>
                <input type="number" step="0.01" name="single_price" value="{{ $product->single_price }}" required>
            </p>

            <p>
                <label for="detailed_price">Prix détaillé (ex: 10 unités = 90 HTG)</label>
                <input type="text" name="detailed_price" value="{{ $product->detailed_price }}">
            </p>
            <label for="post_scriptum">Informations complémentaires (optionnel)</label>
            <textarea name="post_scriptum"
                style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555; resize: none; overflow-y: auto; min-height: 80px; max-height: 200px;">{{ $product->post_scriptum }}</textarea>

            <p>
                <label for="branch_id">Branche</label>
                <select name="branch_id" required
                    style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                    <option value="">-- Sélectionner une branche --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ $product->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->branche_name }}
                        </option>
                    @endforeach
                </select>
            </p>

            <p>
                <label for="category_id">Catégorie</label>
                <select name="category_id" required
                    style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                    <option value="">-- Sélectionner une catégorie --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}</option>
                    @endforeach
                </select>
            </p>

            <p><input type="submit" value="Enregistrer"></p>
        </form>

        <div class="error-messages">
            @if (session('success'))
                <div class="alert-success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('error'))
                <div class="alert-error-message">
                    {{ $errors->first('error') }}
                </div>
            @endif

            @if ($errors->has('product_name'))
                <div class="alert-error-message">
                    {{ $errors->first('product_name') }}
                </div>
            @endif
        </div>
    </div>
</body>

</html>
