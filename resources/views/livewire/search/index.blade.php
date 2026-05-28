<div>
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.500ms
             style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ session('success') }}</span>
            <button @click="show = false" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #155724;">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.500ms
             style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ session('error') }}</span>
            <button @click="show = false" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #721c24;">&times;</button>
        </div>
    @endif
    <div style="margin-bottom: 24px;">
        <div style="display: flex; gap: 12px; align-items: center;">
            <input wire:model.live.debounce.300ms="query" type="search" placeholder="Rechercher un produit, une catégorie, une branche..."
                style="flex: 1; padding: 14px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; background: #fff; color: #333; outline: none;">
            <i class='bx bx-search' style="font-size: 24px; color: #888;"></i>
        </div>
    </div>

    @if ($query === '')
        <div style="text-align: center; padding: 60px 20px; color: #888;">
            <i class='bx bx-search-alt' style="font-size: 64px; margin-bottom: 16px;"></i>
            <p style="font-size: 18px;">Entrez un terme de recherche pour trouver des produits, catégories ou branches.</p>
        </div>
    @else
        @php
            $hasResults = collect($results)->flatten()->isNotEmpty();
        @endphp

        @if (! $hasResults)
            <div style="text-align: center; padding: 60px 20px; color: #888;">
                <i class='bx bx-info-circle' style="font-size: 64px; margin-bottom: 16px;"></i>
                <p style="font-size: 18px;">Aucun résultat trouvé pour "<strong>{{ $query }}</strong>".</p>
            </div>
        @else
            <p class="search-query-text" style="margin-bottom: 20px; font-size: 14px;">
                Résultats pour : <strong>"{{ $query }}"</strong>
            </p>

            @if ($results['products']?->isNotEmpty())
                <div style="margin-bottom: 30px;">
                    <h3 class="search-section" style="display: flex; align-items: center; gap: 8px;">
                        <i class='bx bxs-shopping-bag-alt' style="color: var(--blue);"></i>
                        Produits ({{ $results['products']->count() }})
                    </h3>
                    <div class="table-data">
                        <div class="order">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom du produit</th>
                                        <th>Prix unitaire</th>
                                        <th>Branche</th>
                                        <th>Catégorie</th>
                                        @if ($userRole !== 3)<th>Status</th>@endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['products'] as $prod)
                                        <tr wire:key="prod-{{ $prod->id }}" style="cursor: pointer;" wire:click="showProduct({{ $prod->id }})">
                                            <td><p>{{ $prod->product_name }}</p></td>
                                            <td>{{ $prod->single_price }} $HT</td>
                                            <td>{{ $prod->branch_name }}</td>
                                            <td>{{ $prod->category_name }}</td>
                                            @if ($userRole !== 3)
                                                <td>
                                                    @switch($prod->status)
                                                        @case(1) <span class="status completed">Actif</span> @break
                                                        @case(2) <span class="status pending">Bloqué</span> @break
                                                        @case(0) <span class="status process">Supprimé</span> @break
                                                    @endswitch
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if ($results['categories']?->isNotEmpty())
                <div style="margin-bottom: 30px;">
                    <h3 class="search-section" style="display: flex; align-items: center; gap: 8px;">
                        <i class='bx bxs-doughnut-chart' style="color: var(--blue);"></i>
                        Catégories ({{ $results['categories']->count() }})
                    </h3>
                    <div class="table-data">
                        <div class="order">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom de la catégorie</th>
                                        @if ($userRole !== 3)<th>Status</th>@endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['categories'] as $cat)
                                        <tr wire:key="cat-{{ $cat->id }}" style="cursor: pointer;" wire:click="showCategory({{ $cat->id }})">
                                            <td><p>{{ $cat->category_name }}</p></td>
                                            @if ($userRole !== 3)
                                                <td>
                                                    @switch($cat->status)
                                                        @case(1) <span class="status completed">Actif</span> @break
                                                        @case(2) <span class="status pending">Bloqué</span> @break
                                                        @case(0) <span class="status process">Supprimé</span> @break
                                                    @endswitch
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if ($results['branches']?->isNotEmpty())
                <div style="margin-bottom: 30px;">
                    <h3 class="search-section" style="display: flex; align-items: center; gap: 8px;">
                        <i class='bx bxs-business' style="color: var(--blue);"></i>
                        Branches ({{ $results['branches']->count() }})
                    </h3>
                    <div class="table-data">
                        <div class="order">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom de la branche</th>
                                        @if ($userRole !== 3)<th>Status</th>@endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['branches'] as $branch)
                                        <tr wire:key="branch-{{ $branch->id }}" style="cursor: pointer;" wire:click="showBranch({{ $branch->id }})">
                                            <td><p>{{ $branch->branche_name }}</p></td>
                                            @if ($userRole !== 3)
                                                <td>
                                                    @switch($branch->status)
                                                        @case(1) <span class="status completed">Actif</span> @break
                                                        @case(2) <span class="status pending">Bloqué</span> @break
                                                        @case(0) <span class="status process">Supprimé</span> @break
                                                    @endswitch
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if ($results['users']?->isNotEmpty())
                <div style="margin-bottom: 30px;">
                    <h3 class="search-section" style="display: flex; align-items: center; gap: 8px;">
                        <i class='bx bxs-group' style="color: var(--blue);"></i>
                        Utilisateurs ({{ $results['users']->count() }})
                    </h3>
                    <div class="table-data">
                        <div class="order">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom d'utilisateur</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['users'] as $user)
                                        <tr wire:key="user-{{ $user->id }}" style="cursor: pointer;" wire:click="showUser({{ $user->id }})">
                                            <td><p>{{ $user->username }}</p></td>
                                            <td>{{ $user->email ?? '—' }}</td>
                                            <td>{{ $user->role === 2 ? 'Admin' : ($user->role === 3 ? 'Lecteur' : '—') }}</td>
                                            <td>
                                                @switch($user->status)
                                                    @case(1) <span class="status completed">Actif</span> @break
                                                    @case(2) <span class="status pending">Bloqué</span> @break
                                                    @case(0) <span class="status process">Supprimé</span> @break
                                                @endswitch
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif

    {{-- Product Modal --}}
    @if ($showProductModal && $selectedProduct)
        <div class="modal-overlay active" wire:click="closeProductModal">
            <div class="login modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" type="button" wire:click="closeProductModal">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Détails produit</h2>
                <div class="login-container">
                    <p><strong>Nom :</strong> {{ $selectedProduct->product_name }}</p>
                    <p><strong>Prix unitaire :</strong> {{ $selectedProduct->single_price }} $HT</p>
                    @if ($selectedProduct->detailed_price)
                        <p><strong>Prix détaillé :</strong> {{ $selectedProduct->detailed_price }}</p>
                    @endif
                    @if ($selectedProduct->post_scriptum)
                        <p><strong>Description :</strong> {{ $selectedProduct->post_scriptum }}</p>
                    @endif
                    <p><strong>Branche :</strong> {{ $selectedProduct->branch_name }}</p>
                    <p><strong>Catégorie :</strong> {{ $selectedProduct->category_name }}</p>
                    <p><strong>Crée le :</strong> {{ $selectedProduct->created_at_formatted }}</p>
                    <p><strong>Status :</strong>
                        @switch($selectedProduct->status)
                            @case(1) <span class="status completed">Actif</span> @break
                            @case(2) <span class="status pending">Bloqué</span> @break
                            @case(0) <span class="status process">Supprimé</span> @break
                        @endswitch
                    </p>
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    @if ($userRole <= 2)
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if ($selectedProduct->status === 1)
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="blockProduct({{ $selectedProduct->id }})">
                                        <i class='bx bx-lock'></i> Bloquer
                                    </button>
                                @endif
                                <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="deleteProduct({{ $selectedProduct->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                            @elseif ($selectedProduct->status === 2)
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="unblockProduct({{ $selectedProduct->id }})">
                                        <i class='bx bx-lock-open'></i> Débloquer
                                    </button>
                                    <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="deleteProduct({{ $selectedProduct->id }})">
                                        <i class='bx bx-trash'></i> Supprimer
                                    </button>
                                @elseif ($userRole === 2)
                                    <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="restoreProduct({{ $selectedProduct->id }})">
                                        <i class='bx bx-revision'></i> Restaurer
                                    </button>
                                    <button class="action-btn" style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="confirmEraseProduct({{ $selectedProduct->id }})">
                                        <i class='bx bx-x-circle'></i> Supprimer définitivement
                                    </button>
                                @endif
                            @elseif ($selectedProduct->status === 0)
                                <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="restoreProduct({{ $selectedProduct->id }})">
                                    <i class='bx bx-revision'></i> Restaurer
                                </button>
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="confirmEraseProduct({{ $selectedProduct->id }})">
                                        <i class='bx bx-x-circle'></i> Supprimer définitivement
                                    </button>
                                @endif
                            @endif
                            <button class="action-btn" style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" onclick="window.location.href='{{ route($productRoute) }}'">
                                <i class='bx bx-edit'></i> Modifier
                            </button>
                        </div>
                    @endif
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    <p style="text-align: center;">
                        <a href="{{ route($productRoute) }}" style="display: inline-block; padding: 10px 20px; background: #28d; color: #fff; text-decoration: none; border-radius: 4px; font-size: 14px;">Voir tous les produits</a>
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Category Modal --}}
    @if ($showCategoryModal && $selectedCategory)
        <div class="modal-overlay active" wire:click="closeCategoryModal">
            <div class="login modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" type="button" wire:click="closeCategoryModal">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Détails catégorie</h2>
                <div class="login-container">
                    <p><strong>Nom :</strong> {{ $selectedCategory->category_name }}</p>
                    <p><strong>Crée le :</strong> {{ $selectedCategory->created_at_formatted }}</p>
                    <p><strong>Status :</strong>
                        @switch($selectedCategory->status)
                            @case(1) <span class="status completed">Actif</span> @break
                            @case(2) <span class="status pending">Bloqué</span> @break
                            @case(0) <span class="status process">Supprimé</span> @break
                        @endswitch
                    </p>
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    @if ($userRole <= 2)
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if ($selectedCategory->status === 1)
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="blockCategory({{ $selectedCategory->id }})">
                                        <i class='bx bx-lock'></i> Bloquer
                                    </button>
                                @endif
                                <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="deleteCategory({{ $selectedCategory->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                            @elseif ($selectedCategory->status === 2)
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="unblockCategory({{ $selectedCategory->id }})">
                                        <i class='bx bx-lock-open'></i> Débloquer
                                    </button>
                                    <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="deleteCategory({{ $selectedCategory->id }})">
                                        <i class='bx bx-trash'></i> Supprimer
                                    </button>
                                @elseif ($userRole === 2)
                                    <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="restoreCategory({{ $selectedCategory->id }})">
                                        <i class='bx bx-revision'></i> Restaurer
                                    </button>
                                    <button class="action-btn" style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="confirmEraseCategory({{ $selectedCategory->id }})">
                                        <i class='bx bx-x-circle'></i> Supprimer définitivement
                                    </button>
                                @endif
                            @elseif ($selectedCategory->status === 0)
                                <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="restoreCategory({{ $selectedCategory->id }})">
                                    <i class='bx bx-revision'></i> Restaurer
                                </button>
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="confirmEraseCategory({{ $selectedCategory->id }})">
                                        <i class='bx bx-x-circle'></i> Supprimer définitivement
                                    </button>
                                @endif
                            @endif
                            <button class="action-btn" style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" onclick="window.location.href='{{ route($categoryRoute) }}'">
                                <i class='bx bx-edit'></i> Modifier
                            </button>
                        </div>
                    @endif
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    <p style="text-align: center;">
                        <a href="{{ route($categoryRoute) }}" style="display: inline-block; padding: 10px 20px; background: #28d; color: #fff; text-decoration: none; border-radius: 4px; font-size: 14px;">Voir toutes les catégories</a>
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Branch Modal --}}
    @if ($showBranchModal && $selectedBranch)
        <div class="modal-overlay active" wire:click="closeBranchModal">
            <div class="login modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" type="button" wire:click="closeBranchModal">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Détails branche</h2>
                <div class="login-container">
                    <p><strong>Nom :</strong> {{ $selectedBranch->branche_name }}</p>
                    <p><strong>Crée le :</strong> {{ $selectedBranch->created_at_formatted }}</p>
                    <p><strong>Status :</strong>
                        @switch($selectedBranch->status)
                            @case(1) <span class="status completed">Actif</span> @break
                            @case(2) <span class="status pending">Bloqué</span> @break
                            @case(0) <span class="status process">Supprimé</span> @break
                        @endswitch
                    </p>
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    @if ($userRole <= 2)
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if ($selectedBranch->status === 1)
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="blockBranch({{ $selectedBranch->id }})">
                                        <i class='bx bx-lock'></i> Bloquer
                                    </button>
                                @endif
                                <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="deleteBranch({{ $selectedBranch->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                            @elseif ($selectedBranch->status === 2)
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="unblockBranch({{ $selectedBranch->id }})">
                                        <i class='bx bx-lock-open'></i> Débloquer
                                    </button>
                                    <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="deleteBranch({{ $selectedBranch->id }})">
                                        <i class='bx bx-trash'></i> Supprimer
                                    </button>
                                @elseif ($userRole === 2)
                                    <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="restoreBranch({{ $selectedBranch->id }})">
                                        <i class='bx bx-revision'></i> Restaurer
                                    </button>
                                    <button class="action-btn" style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="confirmEraseBranch({{ $selectedBranch->id }})">
                                        <i class='bx bx-x-circle'></i> Supprimer définitivement
                                    </button>
                                @endif
                            @elseif ($selectedBranch->status === 0)
                                <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="restoreBranch({{ $selectedBranch->id }})">
                                    <i class='bx bx-revision'></i> Restaurer
                                </button>
                                @if ($userRole === 1)
                                    <button class="action-btn" style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="confirmEraseBranch({{ $selectedBranch->id }})">
                                        <i class='bx bx-x-circle'></i> Supprimer définitivement
                                    </button>
                                @endif
                            @endif
                            <button class="action-btn" style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" onclick="window.location.href='{{ route($branchRoute) }}'">
                                <i class='bx bx-edit'></i> Modifier
                            </button>
                        </div>
                    @endif
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    <p style="text-align: center;">
                        <a href="{{ route($branchRoute) }}" style="display: inline-block; padding: 10px 20px; background: #28d; color: #fff; text-decoration: none; border-radius: 4px; font-size: 14px;">Voir toutes les branches</a>
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- User Modal --}}
    @if ($showUserModal && $selectedUser)
        <div class="modal-overlay active" wire:click="closeUserModal">
            <div class="login modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" type="button" wire:click="closeUserModal">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Détails utilisateur</h2>
                <div class="login-container">
                    <p><strong>Nom d'utilisateur :</strong> {{ $selectedUser->username }}</p>
                    <p><strong>Email :</strong> {{ $selectedUser->email ?? '—' }}</p>
                    <p><strong>Rôle :</strong> {{ $selectedUser->role === 2 ? 'Admin' : ($selectedUser->role === 3 ? 'Lecteur' : 'Super Admin') }}</p>
                    <p><strong>Crée le :</strong> {{ $selectedUser->created_at_formatted }}</p>
                    <p><strong>Status :</strong>
                        @switch($selectedUser->status)
                            @case(1) <span class="status completed">Actif</span> @break
                            @case(2) <span class="status pending">Bloqué</span> @break
                            @case(0) <span class="status process">Supprimé</span> @break
                        @endswitch
                    </p>
                    <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    @if ($userRole === 1)
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @if ($selectedUser->status === 1)
                                <button class="action-btn" style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="blockUser({{ $selectedUser->id }})">
                                    <i class='bx bx-lock'></i> Bloqué
                                </button>
                                <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="deleteUser({{ $selectedUser->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                            @elseif ($selectedUser->status === 2)
                                <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="unblockUser({{ $selectedUser->id }})">
                                    <i class='bx bx-lock-open'></i> Débloqué
                                </button>
                                <button class="action-btn" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="deleteUser({{ $selectedUser->id }})">
                                    <i class='bx bx-trash'></i> Supprimer
                                </button>
                            @elseif ($selectedUser->status === 0)
                                <button class="action-btn" style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="restoreUser({{ $selectedUser->id }})">
                                    <i class='bx bx-revision'></i> Restaurer
                                </button>
                                <button class="action-btn" style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" wire:click="confirmEraseUser({{ $selectedUser->id }})">
                                    <i class='bx bx-x-circle'></i> Supprimer définitivement
                                </button>
                            @endif
                            <button class="action-btn" style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;" onclick="window.location.href='{{ route('super_admin_users') }}'">
                                <i class='bx bx-edit'></i> Modifier
                            </button>
                        </div>
                        <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                    @endif
                    <p style="text-align: center;">
                        <a href="{{ route('super_admin_users') }}" style="display: inline-block; padding: 10px 20px; background: #28d; color: #fff; text-decoration: none; border-radius: 4px; font-size: 14px;">Voir tous les utilisateurs</a>
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Erase Confirmation Modal --}}
    @if ($showConfirmErase)
        <div class="modal-overlay active" style="z-index: 3000; backdrop-filter: blur(3px);" wire:click.self="cancelErase">
            <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                <button class="modal-close" wire:click="cancelErase" aria-label="Fermer">&times;</button>
                <div class="login-triangle"></div>
                <h2 class="login-header">Confirmation</h2>
                <div class="login-container">
                    <p style="text-align: center;">
                        @if ($eraseProductId)
                            Êtes-vous sûr de vouloir supprimer définitivement ce produit ?
                        @elseif ($eraseCategoryId)
                            Êtes-vous sûr de vouloir supprimer définitivement cette catégorie ?
                        @elseif ($eraseBranchId)
                            Êtes-vous sûr de vouloir supprimer définitivement cette branche ?
                        @elseif ($eraseUserId)
                            Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ?
                        @endif
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
</div>
