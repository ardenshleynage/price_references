    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th><a href="#" wire:click.prevent="sortBy('category_name')"
                                style="color: inherit; text-decoration: none;">Nom {!! $sortField === 'category_name' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
                        <th><a href="#" wire:click.prevent="sortBy('created_at')"
                                style="color: inherit; text-decoration: none;">Créer le {!! $sortField === 'created_at' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
                        <th><a href="#" wire:click.prevent="sortBy('updated_at')"
                                style="color: inherit; text-decoration: none;">Modifier le {!! $sortField === 'updated_at' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a>
                        </th>
                        @if ($userRole !== 3)
                            <th><a href="#" wire:click.prevent="sortBy('status')"
                                    style="color: inherit; text-decoration: none;">Status {!! $sortField === 'status' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a>
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr wire:click="viewDetail({{ $cat['id'] }})" style="cursor: pointer;">
                            <td>
                                <p>{{ $cat['category_name'] }}</p>
                            </td>
                            <td>{{ $cat['created_at_formatted'] }}</td>
                            <td>{{ $cat['updated_at_formatted'] }}</td>
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

                                        @default
                                            Inconnu
                                    @endswitch
                                </td>
                            @endif
                        </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $userRole === 3 ? 3 : 4 }}" style="text-align: center; padding: 20px;">
                                    <p style="color: #888; font-style: italic;">
                                        @if ($statusFilter === 'active')
                                            Aucune catégorie active
                                        @elseif ($statusFilter === 'blocked')
                                            Aucune catégorie bloquée
                                        @elseif ($statusFilter === 'deleted')
                                            Corbeille vide
                                        @elseif ($statusFilter === 'deleted_by_admin')
                                            Aucune catégorie supprimée par l'admin
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
        </div>
