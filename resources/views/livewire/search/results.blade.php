<div>
    @if ($query === '')
        <div style="text-align: center; padding: 60px 20px; color: #888;">
            <i class='bx bx-search-alt' style="font-size: 64px; margin-bottom: 16px;"></i>
            <p style="font-size: 18px;">Entrez un terme de recherche pour trouver des produits, catégories ou branches.
            </p>
        </div>
    @else
        @php
            $hasResults = collect($results)->flatten()->isNotEmpty();
        @endphp

        @if (!$hasResults)
            <div style="text-align: center; padding: 60px 20px; color: #888;">
                <i class='bx bx-info-circle' style="font-size: 64px; margin-bottom: 16px;"></i>
                <p style="font-size: 18px;">Aucun résultat trouvé pour "<strong>{{ $query }}</strong>".</p>
            </div>
        @else
            <p class="search-query-text" style="margin-bottom: 20px; font-size: 14px;">
                Résultats pour : <strong>"{{ $query }}"</strong>
            </p>

            @if (!empty($results['products']))
                <div style="margin-bottom: 30px;">
                    <h3 class="search-section" style="display: flex; align-items: center; gap: 8px;">
                        <i class='bx bxs-shopping-bag-alt' style="color: var(--blue);"></i>
                        Produits ({{ count($results['products']) }})
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
                                        @if ($userRole !== 3)
                                            <th>Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['products'] as $prod)
                                        <tr wire:key="prod-{{ $prod['id'] }}" style="cursor: pointer;"
                                            wire:click="showProduct({{ $prod['id'] }})">
                                            <td>
                                                <p>{{ $prod['product_name'] }}</p>
                                            </td>
                                            <td>{{ $prod['single_price'] }} $HT</td>
                                            <td>{{ $prod['branch_name'] }}</td>
                                            <td>{{ $prod['category_name'] }}</td>
                                            @if ($userRole !== 3)
                                                <td>
                                                    @switch($prod['status'])
                                                        @case(1)
                                                            <span class="status completed">Actif</span>
                                                        @break

                                                        @case(2)
                                                            <span class="status pending">Bloqué</span>
                                                        @break

                                                        @case(0)
                                                            <span class="status process">Supprimé</span>
                                                        @break

                                                        @case(3)
                                                            <span class="status process">Supprimé par l'admin
                                                                ({{ $prod['deleted_by_username'] ?? 'Inconnu' }})
                                                            </span>
                                                        @break
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

            @if (!empty($results['categories']))
                <div style="margin-bottom: 30px;">
                    <h3 class="search-section" style="display: flex; align-items: center; gap: 8px;">
                        <i class='bx bxs-doughnut-chart' style="color: var(--blue);"></i>
                        Catégories ({{ count($results['categories']) }})
                    </h3>
                    <div class="table-data">
                        <div class="order">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom de la catégorie</th>
                                        @if ($userRole !== 3)
                                            <th>Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['categories'] as $cat)
                                        <tr wire:key="cat-{{ $cat['id'] }}" style="cursor: pointer;"
                                            wire:click="showCategory({{ $cat['id'] }})">
                                            <td>
                                                <p>{{ $cat['category_name'] }}</p>
                                            </td>
                                            @if ($userRole !== 3)
                                                <td>
                                                    @switch($cat['status'])
                                                        @case(1)
                                                            <span class="status completed">Actif</span>
                                                        @break

                                                        @case(2)
                                                            <span class="status pending">Bloqué</span>
                                                        @break

                                                        @case(0)
                                                            <span class="status process">Supprimé</span>
                                                        @break

                                                        @case(3)
                                                            <span class="status process">Supprimé par l'admin
                                                                ({{ $cat['deleted_by_username'] ?? 'Inconnu' }})
                                                            </span>
                                                        @break
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

            @if (!empty($results['branches']))
                <div style="margin-bottom: 30px;">
                    <h3 class="search-section" style="display: flex; align-items: center; gap: 8px;">
                        <i class='bx bxs-business' style="color: var(--blue);"></i>
                        Branches ({{ count($results['branches']) }})
                    </h3>
                    <div class="table-data">
                        <div class="order">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom de la branche</th>
                                        @if ($userRole !== 3)
                                            <th>Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['branches'] as $branch)
                                        <tr wire:key="branch-{{ $branch['id'] }}" style="cursor: pointer;"
                                            wire:click="showBranch({{ $branch['id'] }})">
                                            <td>
                                                <p>{{ $branch['branche_name'] }}</p>
                                            </td>
                                            @if ($userRole !== 3)
                                                <td>
                                                    @switch($branch['status'])
                                                        @case(1)
                                                            <span class="status completed">Actif</span>
                                                        @break

                                                        @case(2)
                                                            <span class="status pending">Bloqué</span>
                                                        @break

                                                        @case(0)
                                                            <span class="status process">Supprimé</span>
                                                        @break

                                                        @case(3)
                                                            <span class="status process">Supprimé par l'admin
                                                                ({{ $branch['deleted_by_username'] ?? 'Inconnu' }})
                                                            </span>
                                                        @break
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

            @if (!empty($results['users']))
                <div style="margin-bottom: 30px;">
                    <h3 class="search-section" style="display: flex; align-items: center; gap: 8px;">
                        <i class='bx bxs-group' style="color: var(--blue);"></i>
                        Utilisateurs ({{ count($results['users']) }})
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
                                        <tr wire:key="user-{{ $user['id'] }}" style="cursor: pointer;"
                                            wire:click="showUser({{ $user['id'] }})">
                                            <td>
                                                <p>{{ $user['username'] }}</p>
                                            </td>
                                            <td>{{ $user['email'] ?? '—' }}</td>
                                            <td>{{ $user['role'] === 2 ? 'Admin' : ($user['role'] === 3 ? 'Lecteur' : '—') }}
                                            </td>
                                            <td>
                                                @switch($user['status'])
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
</div>
