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
        <a href="#" class="btn-download" wire:click.prevent="openAddModal"
            style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px;">
            <i class='bx bxs-cloud-download bx-fade-down-hover'></i>
            <span class="text">Ajoutez une catégorie</span>
        </a>
    @endif

    {{-- TABS --}}
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

    {{-- TABLE --}}
    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th>Nom de la catégorie</th>
                        <th>Créer le</th>
                        <th>Modifier le</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr wire:click="viewDetail({{ $cat->id }})" style="cursor: pointer;">
                            <td>
                                <p>{{ $cat->category_name }}</p>
                            </td>
                            <td>{{ $cat->created_at_formatted }}</td>
                            <td>{{ $cat->updated_at_formatted }}</td>
                            <td>
                                @switch($cat->status)
                                    @case(1)
                                        <span class="status completed">Actif</span>
                                    @break

                                    @case(2)
                                        <span class="status pending">Bloqué</span>
                                    @break

                                    @case(0)
                                        <span class="status process">Supprimé</span>
                                    @break

                                    @default
                                        Inconnu
                                @endswitch
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">
                                    <p style="color: #888; font-style: italic;">
                                        @if ($statusFilter === 'active')
                                            Aucune catégorie active
                                        @elseif ($statusFilter === 'blocked')
                                            Aucune catégorie bloquée
                                        @elseif ($statusFilter === 'deleted')
                                            Corbeille vide
                                        @else
                                            Aucune catégorie enregistrée
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($categories->hasPages())
                <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
                    {{ $categories->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>

        {{-- DETAIL MODAL --}}
        @if ($showDetailModal && $selectedCategory)
            <div class="modal-overlay active" wire:click.self="closeDetailModal">
                <div class="login modal-content" onclick="event.stopPropagation()">
                    <button class="modal-close" wire:click="closeDetailModal" aria-label="Fermer">&times;</button>
                    <div class="login-triangle"></div>
                    <h2 class="login-header">Détails catégorie</h2>
                    <div class="login-container">
                        <p><strong>Nom :</strong> {{ $selectedCategory->category_name }}</p>
                        <p><strong>Crée le :</strong> {{ $selectedCategory->created_at_formatted }}</p>
                        <p><strong>Modifié le :</strong> {{ $selectedCategory->updated_at_formatted }}</p>
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
                            @endswitch
                        </p>
                        <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                        @if ($userRole <= 2)
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @if ($selectedCategory->status === 1)
                                    @if ($userRole === 1)
                                        <button class="action-btn"
                                            style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                            wire:click="block({{ $selectedCategory->id }})">
                                            <i class='bx bx-lock'></i> Bloquer
                                        </button>
                                    @endif
                                    <button class="action-btn"
                                        style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                        wire:click="delete({{ $selectedCategory->id }})">
                                        <i class='bx bx-trash'></i> Supprimer
                                    </button>
                                @elseif ($selectedCategory->status === 2)
                                    @if ($userRole === 1)
                                        <button class="action-btn"
                                            style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                            wire:click="unblock({{ $selectedCategory->id }})">
                                            <i class='bx bx-lock-open'></i> Débloquer
                                        </button>
                                    @endif
                                    <button class="action-btn"
                                        style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                        wire:click="delete({{ $selectedCategory->id }})">
                                        <i class='bx bx-trash'></i> Supprimer
                                    </button>
                                @elseif ($selectedCategory->status === 0)
                                    <button class="action-btn"
                                        style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                        wire:click="restore({{ $selectedCategory->id }})">
                                        <i class='bx bx-revision'></i> Restaurer
                                    </button>
                                    @if ($userRole === 1)
                                        <button class="action-btn"
                                            style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                            wire:click="confirmErase({{ $selectedCategory->id }})">
                                            <i class='bx bx-x-circle'></i> Supprimer définitivement
                                        </button>
                                    @endif
                                @endif
                                <button class="action-btn"
                                    style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                    wire:click="openEditModal({{ $selectedCategory->id }})">
                                    <i class='bx bx-edit'></i> Modifier
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- CONFIRM ERASE MODAL --}}
        @if ($showConfirmErase)
            <div class="modal-overlay active" style="z-index: 3000; backdrop-filter: blur(3px);"
                wire:click.self="cancelErase">
                <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                    <button class="modal-close" wire:click="cancelErase" aria-label="Fermer">&times;</button>
                    <div class="login-triangle"></div>
                    <h2 class="login-header">Confirmation</h2>
                    <div class="login-container">
                        <p style="text-align: center;">Êtes-vous sûr de vouloir supprimer définitivement cette catégorie ?
                        </p>
                        <div style="display: flex; gap: 10px; margin-top: 20px;">
                            <button type="button" wire:click="cancelErase"
                                style="flex: 1; background: #ebebeb; color: #555; border: 1px solid #bbb; padding: 12px; border-radius: 4px; cursor: pointer;">Non</button>
                            <button type="button" wire:click="erase"
                                style="flex: 1; background: #c0392b; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer;">Oui</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ADD MODAL --}}
        @if ($showAddModal)
            <div class="modal-overlay active" wire:click.self="$set('showAddModal', false)">
                <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                    <button class="modal-close" wire:click="$set('showAddModal', false)"
                        aria-label="Fermer">&times;</button>
                    <div class="login-triangle"></div>
                    <h2 class="login-header">Nouvelle catégorie</h2>
                    <livewire:categories.form :key="'create'" />
                </div>
            </div>
        @endif

        {{-- EDIT MODAL --}}
        @if ($showEditModal && $selectedCategory)
            <div class="modal-overlay active" wire:click.self="$set('showEditModal', false)">
                <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                    <button class="modal-close" wire:click="$set('showEditModal', false)"
                        aria-label="Fermer">&times;</button>
                    <div class="login-triangle"></div>
                    <h2 class="login-header">Modifier la catégorie</h2>
                    <livewire:categories.form :category-id="$selectedCategory->id" :key="'edit-' . $selectedCategory->id" />
                </div>
            </div>
        @endif
    </div>
