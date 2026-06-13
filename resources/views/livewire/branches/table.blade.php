    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th><a href="#" wire:click.prevent="sortBy('branche_name')"
                                style="color: inherit; text-decoration: none;">Nom {!! $sortField === 'branche_name' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
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
                    @forelse ($branches as $branche)
                        <tr wire:click="viewDetail({{ $branche['id'] }})" style="cursor: pointer;">
                            <td>
                                <p>{{ $branche['branche_name'] }}</p>
                            </td>
                            <td>{{ $branche['created_at_formatted'] }}</td>
                            <td>{{ $branche['updated_at_formatted'] }}</td>
                            @if ($userRole !== 3)
                                <td>
                                    @switch($branche['status'])
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
                                            <span class="status process">Supprimé par l'admin</span>
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
                                            Aucune branche active
                                        @elseif ($statusFilter === 'blocked')
                                            Aucune branche bloquée
                                        @elseif ($statusFilter === 'deleted')
                                            Corbeille vide
                                        @elseif ($statusFilter === 'deleted_by_admin')
                                            Aucune branche supprimée par l'admin
                                        @else
                                            Aucune branche enregistrée
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
