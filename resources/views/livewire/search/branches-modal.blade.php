<div class="modal-overlay active" wire:click="closeBranchModal">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" type="button" wire:click="closeBranchModal">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Détails branche</h2>
        <div class="login-container">
            <p><strong>Nom :</strong> {{ $selectedBranch['branche_name'] }}</p>
            <p><strong>Crée le :</strong> {{ $selectedBranch['created_at_formatted'] }}</p>
            <p><strong>Status :</strong>
                @switch($selectedBranch['status'])
                    @case(1)
                        <span class="status completed">Actif</span>
                    @break

                    @case(2)
                        <span class="status pending">Bloqué</span>
                    @break

                    @case(0)
                        <span class="status process">Supprimé</span>
                    @break
                @endswitch
            </p>
            <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
            @if ($userRole <= 2)
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @if ($selectedBranch['status'] === 1)
                        @if ($userRole <= 2)
                            <button class="action-btn"
                                style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="blockBranch({{ $selectedBranch['id'] }})">
                                <i class='bx bx-lock'></i> Bloquer
                            </button>
                        @endif
                        <button class="action-btn"
                            style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="deleteBranch({{ $selectedBranch['id'] }})">
                            <i class='bx bx-trash'></i> Supprimer
                        </button>
                    @elseif ($selectedBranch['status'] === 2)
                        @if ($userRole <= 2)
                            <button class="action-btn"
                                style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="unblockBranch({{ $selectedBranch['id'] }})">
                                <i class='bx bx-lock-open'></i> Débloquer
                            </button>
                            <button class="action-btn"
                                style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="deleteBranch({{ $selectedBranch['id'] }})">
                                <i class='bx bx-trash'></i> Supprimer
                            </button>
                            <button class="action-btn"
                                style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="confirmEraseBranch({{ $selectedBranch['id'] }})">
                                <i class='bx bx-x-circle'></i> Supprimer définitivement
                            </button>
                        @endif
                    @elseif ($selectedBranch['status'] === 0)
                        <button class="action-btn"
                            style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="restoreBranch({{ $selectedBranch['id'] }})">
                            <i class='bx bx-revision'></i> Restaurer
                        </button>
                        <button class="action-btn"
                            style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="confirmEraseBranch({{ $selectedBranch['id'] }})">
                            <i class='bx bx-x-circle'></i> Supprimer définitivement
                        </button>
                    @endif
                    <button class="action-btn"
                        style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                        wire:click="openEditBranchModal({{ $selectedBranch['id'] }})">
                        <i class='bx bx-edit'></i> Modifier
                    </button>
                </div>
            @endif
            <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
            <p style="text-align: center;">
                <a href="{{ route($branchRoute) }}"
                    style="display: inline-block; padding: 10px 20px; background: #28d; color: #fff; text-decoration: none; border-radius: 4px; font-size: 14px;">Voir
                    toutes les branches</a>
            </p>
        </div>
    </div>
</div>
