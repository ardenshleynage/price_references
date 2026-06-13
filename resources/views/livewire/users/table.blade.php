    <div class="table-data">
        <div class="order">
            <table>
                <thead>
                    <tr>
                        <th><a href="#" wire:click.prevent="sortBy('username')"
                                style="color: inherit; text-decoration: none;">Nom d'utilisateur {!! $sortField === 'username' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
                        <th><a href="#" wire:click.prevent="sortBy('email')"
                                style="color: inherit; text-decoration: none;">E-mail {!! $sortField === 'email' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
                        <th><a href="#" wire:click.prevent="sortBy('last_time_connect')"
                                style="color: inherit; text-decoration: none;">Dernière connexion {!! $sortField === 'last_time_connect' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
                        <th><a href="#" wire:click.prevent="sortBy('role')"
                                style="color: inherit; text-decoration: none;">Rôle {!! $sortField === 'role' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
                        @if ($userRole !== 3)
                            <th><a href="#" wire:click.prevent="sortBy('status')"
                                    style="color: inherit; text-decoration: none;">Status {!! $sortField === 'status' ? ($sortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}</a></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr wire:click="viewDetail({{ $user['id'] }})" style="cursor: pointer;">
                            <td>
                                <p>{{ $user['username'] }}</p>
                            </td>
                            <td>
                                <p>{{ $user['email'] ?? 'N/A' }}</p>
                            </td>
                            <td>{{ $user['last_time_connect_formatted'] }}</td>
                            <td>
                                @switch($user['role'])
                                    @case(1)
                                        Super Admin
                                    @break

                                    @case(2)
                                        Admin
                                    @break

                                    @case(3)
                                        Utilisateur
                                    @break

                                    @default
                                        Inconnu
                                @endswitch
                            </td>
                            @if ($userRole !== 3)
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

                                        @default
                                            Inconnu
                                    @endswitch
                                </td>
                            @endif
                        </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $userRole === 3 ? 4 : 5 }}" style="text-align: center; padding: 20px;">
                                    <p style="color: #888; font-style: italic;">
                                        @if ($statusFilter === 'active')
                                            Aucun utilisateur actif
                                        @elseif ($statusFilter === 'blocked')
                                            Aucun utilisateur bloqué
                                        @elseif ($statusFilter === 'deleted')
                                            Corbeille vide
                                        @else
                                            Aucun utilisateur enregistré
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
