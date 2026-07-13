@if ($selectedBranch)
    <div class="modal-overlay active" wire:click.self="$parent.closeDetailModal">
        <div class="login modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" wire:click="$parent.closeDetailModal" aria-label="Fermer">&times;</button>
            <div class="login-triangle"></div>
            <h2 class="login-header">Détails branche</h2>
            <div class="login-container">
                <p><strong>Nom :</strong> {{ $selectedBranch->branche_name }}</p>
                <p><strong>Crée le :</strong> {{ $selectedBranch->created_at_formatted }}</p>
                <p><strong>Modifié le :</strong> {{ $selectedBranch->updated_at_formatted }}</p>
                @if ($userRole !== 3)
                    <p><strong>Status :</strong>
                        @switch($selectedBranch->status)
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
                                Supprimé par un admin ({{ $selectedBranch->deletedBy->username ?? 'Inconnu' }} /
                                {{ $selectedBranch->updated_at_formatted }})
                            @break
                        @endswitch
                    </p>
                @endif
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                @if ($userRole <= 2)
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @if ($selectedBranch->status === 1)
                            @if ($userRole <= 2)
                                <button class="action-btn"
                                    style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.block({{ $selectedBranch->id }})">
                                    <i class='bx bx-lock'></i> Bloquer
                                </button>
                            @endif
                            <button class="action-btn"
                                style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="$parent.delete({{ $selectedBranch->id }})">
                                <i class='bx bx-trash'></i> Supprimer
                            </button>
                        @elseif ($selectedBranch->status === 2)
                            @if ($userRole <= 2)
                                <button class="action-btn"
                                    style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.unblock({{ $selectedBranch->id }})">
                                    <i class='bx bx-lock-open'></i> Débloquer
                                </button>
                            @endif
                            <button class="action-btn"
                                style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="$parent.delete({{ $selectedBranch->id }})">
                                <i class='bx bx-trash'></i> Supprimer
                            </button>
                            <button class="action-btn"
                                style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="$parent.confirmErase({{ $selectedBranch->id }})">
                                <i class='bx bx-x-circle'></i> Supprimer définitivement
                            </button>
                        @elseif ($selectedBranch->status === 0)
                            <button class="action-btn"
                                style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="$parent.restore({{ $selectedBranch->id }})">
                                <i class='bx bx-revision'></i> Restaurer
                            </button>
                            <button class="action-btn"
                                style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="$parent.confirmErase({{ $selectedBranch->id }})">
                                <i class='bx bx-x-circle'></i> Supprimer définitivement
                            </button>
                        @elseif ($selectedBranch->status === 3)
                            @if ($userRole === 1)
                                <button class="action-btn"
                                    style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.restore({{ $selectedBranch->id }})">
                                    <i class='bx bx-revision'></i> Restaurer
                                </button>
                                <button class="action-btn"
                                    style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.confirmErase({{ $selectedBranch->id }})">
                                    <i class='bx bx-x-circle'></i> Supprimer définitivement
                                </button>
                            @endif
                        @endif
                        <button class="action-btn"
                            style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="$parent.openEditModal({{ $selectedBranch->id }})">
                            <i class='bx bx-edit'></i> Modifier
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
