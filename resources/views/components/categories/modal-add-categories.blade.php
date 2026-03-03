<div id="modalOverlay" class="modal-overlay" onclick="closeModal(event)">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Nouvelle catégorie</h2>
        <form class="login-container" method="POST" action="{{ route('categories.create_category') }}">
            @csrf
            <p><input type="text" name="category_name" placeholder="Nom de la catégorie" required
                    value="{{ old('category_name') }}"></p>
            <p><input type="submit" value="Ajouter"></p>
        </form>
    </div>
</div>
{{ $slot }}
