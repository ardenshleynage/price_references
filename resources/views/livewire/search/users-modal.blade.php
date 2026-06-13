<div class="modal-overlay active" wire:click="closeUserModal">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" type="button" wire:click="closeUserModal">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Détails utilisateur</h2>
        <div class="login-container">
            <p><strong>Nom d'utilisateur :</strong> {{ $selectedUser['username'] }}</p>
            <p><strong>Email :</strong> {{ $selectedUser['email'] ?? '—' }}</p>
            <p><strong>Rôle :</strong>
                {{ $selectedUser['role'] === 2 ? 'Admin' : ($selectedUser['role'] === 3 ? 'Lecteur' : 'Super Admin') }}
            </p>
            <p><strong>Crée le :</strong> {{ $selectedUser['created_at_formatted'] }}</p>
            <p><strong>Status :</strong>
                @switch($selectedUser['status'])
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
            @if ($userRole === 1)
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @if ($selectedUser['status'] === 1)
                        <button class="action-btn"
                            style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="blockUser({{ $selectedUser['id'] }})">
                            <i class='bx bx-lock'></i> Bloqué
                        </button>
                        <button class="action-btn"
                            style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="deleteUser({{ $selectedUser['id'] }})">
                            <i class='bx bx-trash'></i> Supprimer
                        </button>
                    @elseif ($selectedUser['status'] === 2)
                        <button class="action-btn"
                            style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="unblockUser({{ $selectedUser['id'] }})">
                            <i class='bx bx-lock-open'></i> Débloqué
                        </button>
                        <button class="action-btn"
                            style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="deleteUser({{ $selectedUser['id'] }})">
                            <i class='bx bx-trash'></i> Supprimer
                        </button>
                    @elseif ($selectedUser['status'] === 0)
                        <button class="action-btn"
                            style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="restoreUser({{ $selectedUser['id'] }})">
                            <i class='bx bx-revision'></i> Restaurer
                        </button>
                        <button class="action-btn"
                            style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="confirmEraseUser({{ $selectedUser['id'] }})">
                            <i class='bx bx-x-circle'></i> Supprimer définitivement
                        </button>
                    @endif
                    <button class="action-btn"
                        style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                        wire:click="openEditUserModal({{ $selectedUser['id'] }})">
                        <i class='bx bx-edit'></i> Modifier
                    </button>
                </div>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
            @endif
            <p style="text-align: center;">
                <a href="{{ route('super_admin_users') }}"
                    style="display: inline-block; padding: 10px 20px; background: #28d; color: #fff; text-decoration: none; border-radius: 4px; font-size: 14px;">Voir
                    tous les utilisateurs</a>
            </p>
        </div>
    </div>
</div>
