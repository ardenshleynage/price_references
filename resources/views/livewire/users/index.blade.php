<div>
    {{-- ALERT MESSAGES --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="alert-success-message"
            style="margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="alert-error-message"
            style="margin-bottom: 15px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- ADD BUTTON --}}
    <a href="#" class="btn-download" wire:click.prevent="openAddModal" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px;">
        <i class='bx bxs-cloud-download bx-fade-down-hover'></i>
        <span class="text">Ajoutez un utilisateur/Admin</span>
    </a>

    {{-- TABS --}}
    <div class="container">
        <div class="tabs-wrapper">
            <div class="tabs">
                <a href="#" wire:click.prevent="setFilter(null)"
                    class="tab {{ $statusFilter === null ? 'active' : '' }}">
                    Tous
                </a>
                <a href="#" wire:click.prevent="setFilter('active')"
                    class="tab {{ $statusFilter === 'active' ? 'active' : '' }}">
                    Actif
                </a>
                <a href="#" wire:click.prevent="setFilter('blocked')"
                    class="tab {{ $statusFilter === 'blocked' ? 'active' : '' }}">
                    Bloqué
                </a>
                <a href="#" wire:click.prevent="setFilter('deleted')"
                    class="tab {{ $statusFilter === 'deleted' ? 'active' : '' }}">
                    Corbeille
                </a>
                <span class="glider"></span>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>E-mail</th>
                        <th>Dernière connexion</th>
                        <th>Rôle</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr wire:click="viewUser({{ $user->id }})" style="cursor: pointer;">
                            <td><p>{{ $user->username }}</p></td>
                            <td><p>{{ $user->email ?? 'N/A' }}</p></td>
                            <td>{{ $user->last_time_connect ? $user->last_time_connect->format('d/m/Y H:i') : 'Jamais' }}</td>
                            <td>
                                @switch($user->role)
                                    @case(1) Super Admin @break
                                    @case(2) Admin @break
                                    @case(3) Utilisateur @break
                                    @default Inconnu
                                @endswitch
                            </td>
                            <td>
                                @switch($user->status)
                                    @case(1) <span class="status completed">Actif</span> @break
                                    @case(2) <span class="status pending">Bloqué</span> @break
                                    @case(0) <span class="status process">Supprimé</span> @break
                                    @default Inconnu
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">
                                <p style="color: #888; font-style: italic;">
                                    @if ($statusFilter === 'active') Aucun utilisateur actif
                                    @elseif ($statusFilter === 'blocked') Aucun utilisateur bloqué
                                    @elseif ($statusFilter === 'deleted') Corbeille vide
                                    @else Aucun utilisateur enregistré
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
                {{ $users->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

    {{-- USER DETAIL MODAL --}}
    @if ($showUserModal && $selectedUser)
        <div class="modal-overlay active" wire:click.self="closeUserModal">
            <div class="login modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" wire:click="closeUserModal" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Détails Utilisateur</h2>
                <div class="login-container">
                    <p><strong>Nom d'utilisateur :</strong> {{ $selectedUser->username }}</p>
                    <p><strong>E-mail :</strong> {{ $selectedUser->email }}</p>
                    <p><strong>Dernière connexion :</strong> {{ $selectedUser->last_time_connect_formatted }}</p>
                    <p><strong>Rôle :</strong>
                        @switch($selectedUser->role)
                            @case(1) Super Admin @break
                            @case(2) Admin @break
                            @case(3) Utilisateur @break
                        @endswitch
                    </p>
                    <p><strong>Status :</strong>
                        @switch($selectedUser->status)
                            @case(1) Actif @break
                            @case(2) Bloqué @break
                            @case(0) Supprimé @break
                        @endswitch
                    </p>
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    <p><strong>Crée le :</strong> {{ $selectedUser->created_at_formatted }}</p>
                    <p><strong>Modifié le :</strong> {{ $selectedUser->updated_at_formatted }}</p>
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @if ($selectedUser->status === 1)
                            <button class="action-btn" style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="block({{ $selectedUser->id }})">
                                <i class='bx bx-lock'></i> Bloqué
                            </button>
                            <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="delete({{ $selectedUser->id }})">
                                <i class='bx bx-trash'></i> Supprimer
                            </button>
                        @elseif ($selectedUser->status === 2)
                            <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="unblock({{ $selectedUser->id }})">
                                <i class='bx bx-lock-open'></i> Débloqué
                            </button>
                            <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="delete({{ $selectedUser->id }})">
                                <i class='bx bx-trash'></i> Supprimer
                            </button>
                        @elseif ($selectedUser->status === 0)
                            <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="restore({{ $selectedUser->id }})">
                                <i class='bx bx-revision'></i> Restaurer
                            </button>
                            <button class="action-btn" style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="confirmErase({{ $selectedUser->id }})">
                                <i class='bx bx-x-circle'></i> Supprimer définitivement
                            </button>
                        @endif
                        <button class="action-btn" style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="openEditModal({{ $selectedUser->id }})">
                            <i class='bx bx-edit'></i> Modifier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- CONFIRM ERASE MODAL --}}
    @if ($showConfirmErase)
        <div class="modal-overlay active" style="z-index: 3000; backdrop-filter: blur(3px);" wire:click.self="cancelErase">
            <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                <button class="modal-close" wire:click="cancelErase" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Confirmation</h2>
                <div class="login-container">
                    <p style="text-align: center;">Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ?</p>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="button" wire:click="cancelErase" style="flex: 1; background: #ebebeb; color: #555; border: 1px solid #bbb; padding: 12px; border-radius: 4px; cursor: pointer;">Non</button>
                        <button type="button" wire:click="erase" style="flex: 1; background: #c0392b; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer;">Oui</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ADD USER MODAL --}}
    @if ($showAddModal)
        <div class="modal-overlay active" wire:click.self="openAddModal">
            <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                <button class="modal-close" wire:click="$set('showAddModal', false)" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Nouveau utilisateur/Admin</h2>
                <livewire:users.form :super-admin-exists="$superAdminExists" :key="'create'" />
            </div>
        </div>
    @endif

    {{-- EDIT USER MODAL --}}
    @if ($showEditModal && $selectedUser)
        <div class="modal-overlay active" wire:click.self="$set('showEditModal', false)">
            <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                <button class="modal-close" wire:click="$set('showEditModal', false)" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Modifier l'utilisateur</h2>
                <livewire:users.form :user-id="$selectedUser->id" :key="'edit-'.$selectedUser->id" />
            </div>
        </div>
    @endif
</div>