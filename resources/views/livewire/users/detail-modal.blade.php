            <div class="modal-overlay active" wire:click.self="$parent.closeUserModal()">
                <div class="login modal-content" onclick="event.stopPropagation()">
                    <button class="modal-close" wire:click="$parent.closeUserModal()" aria-label="Fermer">&times;</button>
                    <div class="login-triangle"></div>
                    <h2 class="login-header">Détails Utilisateur</h2>
                    <div class="login-container">
                        <p><strong>Nom d'utilisateur :</strong> {{ $selectedUser->username }}</p>
                        <p><strong>E-mail :</strong> {{ $selectedUser->email }}</p>
                        <p><strong>Dernière connexion :</strong> {{ $selectedUser->last_time_connect_formatted }}</p>
                        <p><strong>Rôle :</strong>
                            @switch($selectedUser->role)
                                @case(1)
                                    Super Admin
                                @break

                                @case(2)
                                    Admin
                                @break

                                @case(3)
                                    Utilisateur
                                @break
                            @endswitch
                        </p>
                        <p><strong>Status :</strong>
                            @switch($selectedUser->status)
                                @case(1)
                                    Actif
                                @break

                                @case(2)
                                    Bloqué
                                @break

                                @case(0)
                                    Supprimé
                                @break
                            @endswitch
                        </p>
                        <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                        <p><strong>Crée le :</strong> {{ $selectedUser->created_at_formatted }}</p>
                        <p><strong>Modifié le :</strong> {{ $selectedUser->updated_at_formatted }}</p>
                        <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if ($selectedUser->status === 1)
                                <button class="action-btn"
                                    style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.block({{ $selectedUser->id }})">
                                    <i class='bx bx-lock'></i> Bloqué
                                </button>
                                <button class="action-btn"
                                    style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.delete({{ $selectedUser->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                            @elseif ($selectedUser->status === 2)
                                <button class="action-btn"
                                    style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.unblock({{ $selectedUser->id }})">
                                    <i class='bx bx-lock-open'></i> Débloqué
                                </button>
                                <button class="action-btn"
                                    style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.delete({{ $selectedUser->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                                <button class="action-btn"
                                    style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.confirmErase({{ $selectedUser->id }})">
                                    <i class='bx bx-x-circle'></i> Supprimer définitivement
                                </button>
                            @elseif ($selectedUser->status === 0)
                                <button class="action-btn"
                                    style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.restore({{ $selectedUser->id }})">
                                    <i class='bx bx-revision'></i> Restaurer
                                </button>
                                <button class="action-btn"
                                    style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="$parent.confirmErase({{ $selectedUser->id }})">
                                    <i class='bx bx-x-circle'></i> Supprimer définitivement
                                </button>
                            @endif
                            <button class="action-btn"
                                style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="$parent.openEditModal({{ $selectedUser->id }})">
                                <i class='bx bx-edit'></i> Modifier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
