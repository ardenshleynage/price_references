<div>
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

    @if ($userRole <= 2)
        <a href="#" class="btn-download" wire:click.prevent="openAddModal" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px;">
            <i class='bx bxs-cloud-download bx-fade-down-hover'></i>
            <span class="text">Ajoutez une branche</span>
        </a>
    @endif

    <div class="container">
        <div class="tabs-wrapper">
            <div class="tabs">
                <a href="#" wire:click.prevent="setFilter(null)"
                    class="tab {{ $statusFilter === null ? 'active' : '' }}">Tous</a>
                <a href="#" wire:click.prevent="setFilter('active')"
                    class="tab {{ $statusFilter === 'active' ? 'active' : '' }}">Actif</a>
                @if ($userRole === 1)
                    <a href="#" wire:click.prevent="setFilter('blocked')"
                        class="tab {{ $statusFilter === 'blocked' ? 'active' : '' }}">Bloqué</a>
                @endif
                @if ($userRole <= 2)
                    <a href="#" wire:click.prevent="setFilter('deleted')"
                        class="tab {{ $statusFilter === 'deleted' ? 'active' : '' }}">Corbeille</a>
                @endif
                <span class="glider"></span>
            </div>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th>Nom de la branche</th>
                        <th>Créer le</th>
                        <th>Modifier le</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($branches as $branche)
                        <tr wire:click="viewDetail({{ $branche->id }})" style="cursor: pointer;">
                            <td><p>{{ $branche->branche_name }}</p></td>
                            <td>{{ $branche->created_at_formatted }}</td>
                            <td>{{ $branche->updated_at_formatted }}</td>
                            <td>
                                @switch($branche->status)
                                    @case(1) <span class="status completed">Actif</span> @break
                                    @case(2) <span class="status pending">Bloqué</span> @break
                                    @case(0) <span class="status process">Supprimé</span> @break
                                    @default Inconnu
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px;">
                                <p style="color: #888; font-style: italic;">
                                    @if ($statusFilter === 'active') Aucune branche active
                                    @elseif ($statusFilter === 'blocked') Aucune branche bloquée
                                    @elseif ($statusFilter === 'deleted') Corbeille vide
                                    @else Aucune branche enregistrée
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($branches->hasPages())
            <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
                {{ $branches->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

    @if ($showDetailModal && $selectedBranch)
        <div class="modal-overlay active" wire:click.self="closeDetailModal">
            <div class="login modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" wire:click="closeDetailModal" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Détails branche</h2>
                <div class="login-container">
                    <p><strong>Nom :</strong> {{ $selectedBranch->branche_name }}</p>
                    <p><strong>Crée le :</strong> {{ $selectedBranch->created_at_formatted }}</p>
                    <p><strong>Modifié le :</strong> {{ $selectedBranch->updated_at_formatted }}</p>
                    <p><strong>Status :</strong>
                        @switch($selectedBranch->status)
                            @case(1) Actif @break
                            @case(2) Bloqué @break
                            @case(0) Supprimé @break
                        @endswitch
                    </p>
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    @if ($userRole <= 2)
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if ($selectedBranch->status === 1)
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="block({{ $selectedBranch->id }})">
                                        <i class='bx bx-lock'></i> Bloquer
                                    </button>
                                @endif
                                <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="delete({{ $selectedBranch->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                            @elseif ($selectedBranch->status === 2)
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="unblock({{ $selectedBranch->id }})">
                                        <i class='bx bx-lock-open'></i> Débloquer
                                    </button>
                                @endif
                                <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="delete({{ $selectedBranch->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                            @elseif ($selectedBranch->status === 0)
                                <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="restore({{ $selectedBranch->id }})">
                                    <i class='bx bx-revision'></i> Restaurer
                                </button>
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="confirmErase({{ $selectedBranch->id }})">
                                        <i class='bx bx-x-circle'></i> Supprimer définitivement
                                    </button>
                                @endif
                            @endif
                            <button class="action-btn" style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="openEditModal({{ $selectedBranch->id }})">
                                <i class='bx bx-edit'></i> Modifier
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($showConfirmErase)
        <div class="modal-overlay active" style="z-index: 3000; backdrop-filter: blur(3px);" wire:click.self="cancelErase">
            <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                <button class="modal-close" wire:click="cancelErase" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Confirmation</h2>
                <div class="login-container">
                    <p style="text-align: center;">Êtes-vous sûr de vouloir supprimer définitivement cette branche ?</p>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="button" wire:click="cancelErase" style="flex: 1; background: #ebebeb; color: #555; border: 1px solid #bbb; padding: 12px; border-radius: 4px; cursor: pointer;">Non</button>
                        <button type="button" wire:click="erase" style="flex: 1; background: #c0392b; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer;">Oui</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showAddModal)
        <div class="modal-overlay active" wire:click.self="$set('showAddModal', false)">
            <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                <button class="modal-close" wire:click="$set('showAddModal', false)" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Nouvelle branche</h2>
                <livewire:branches.form :key="'create'" />
            </div>
        </div>
    @endif

    @if ($showEditModal && $selectedBranch)
        <div class="modal-overlay active" wire:click.self="$set('showEditModal', false)">
            <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                <button class="modal-close" wire:click="$set('showEditModal', false)" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Modifier la branche</h2>
                <livewire:branches.form :branch-id="$selectedBranch->id" :key="'edit-'.$selectedBranch->id" />
            </div>
        </div>
    @endif
</div>
