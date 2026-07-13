    @if ($selectedCategory)
        <div class="modal-overlay active" wire:click.self="$parent.closeDetailModal">
            <div class="login modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" wire:click="$parent.closeDetailModal" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Détails catégorie</h2>
                <div class="login-container">
                    <p><strong>Nom :</strong> {{ $selectedCategory->category_name }}</p>
                    <p><strong>Crée le :</strong> {{ $selectedCategory->created_at_formatted }}</p>
                    <p><strong>Modifié le :</strong> {{ $selectedCategory->updated_at_formatted }}</p>
                    @if ($userRole !== 3)
                        <p><strong>Status :</strong>
                            @switch($selectedCategory->status)
                                @case(1)
                                    Actif
                                @break

                                @case(2)
                                    Bloqué
                                @break

                                @case(0)
                                    Supprimé
                                @break

                                @case(3)
                                    Supprimé par l'admin ({{ $selectedCategory->deletedBy->username ?? 'Inconnu' }} /
                                    {{ $selectedCategory->updated_at_formatted }})
                                @break
                            @endswitch
                        </p>
                    @endif
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    @if ($userRole <= 2)
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if ($selectedCategory->status === 1)
                                <button class="action-btn"
                                    style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.block({{ $selectedCategory->id }})">
                                    <i class='bx bx-lock'></i> Bloquer
                                </button>
                                <button class="action-btn"
                                    style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.delete({{ $selectedCategory->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                            @elseif ($selectedCategory->status === 2)
                                <button class="action-btn"
                                    style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.unblock({{ $selectedCategory->id }})">
                                    <i class='bx bx-lock-open'></i> Débloquer
                                </button>
                                <button class="action-btn"
                                    style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.delete({{ $selectedCategory->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                                <button class="action-btn"
                                    style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.confirmErase({{ $selectedCategory->id }})">
                                    <i class='bx bx-x-circle'></i> Supprimer définitivement
                                </button>
                            @elseif ($selectedCategory->status === 0)
                                <button class="action-btn"
                                    style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.restore({{ $selectedCategory->id }})">
                                    <i class='bx bx-revision'></i> Restaurer
                                </button>
                                <button class="action-btn"
                                    style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.confirmErase({{ $selectedCategory->id }})">
                                    <i class='bx bx-x-circle'></i> Supprimer définitivement
                                </button>
                            @elseif ($selectedCategory->status === 3)
                                @if ($userRole === 1)
                                    <button class="action-btn"
                                        style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                        wire:click="$parent.restore({{ $selectedCategory->id }})">
                                        <i class='bx bx-revision'></i> Restaurer
                                    </button>
                                    <button class="action-btn"
                                        style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                        wire:click="$parent.confirmErase({{ $selectedCategory->id }})">
                                        <i class='bx bx-x-circle'></i> Supprimer définitivement
                                    </button>
                                @endif
                            @endif
                            <button class="action-btn"
                                style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="$parent.openEditModal({{ $selectedCategory->id }})">
                                <i class='bx bx-edit'></i> Modifier
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
