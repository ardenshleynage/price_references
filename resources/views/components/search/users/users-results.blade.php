            @if (count($results['users']) > 0)
                <div class="search-section">
                    <h3>Utilisateurs ({{ count($results['users']) }})</h3>
                    <div class="table-data">
                        <div class="order">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom d'utilisateur</th>
                                        <th>Rôle</th>
                                        <th>Dernière connexion</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $lastConnect = isset($user->last_time_connect) ? addslashes($user->last_time_connect) : 'Jamais';
                                    @endphp
                                    @foreach ($results['users'] as $user)
                                        <tr onclick="openUserModal('{{ $user->id }}', '{{ addslashes($user->username) }}', '{{ $user->role }}', '{{ $user->status }}', '{{ $lastConnect }}', '{{ $user->created_at }}', '{{ $user->updated_at }}')"
                                            style="cursor: pointer;">
                                            <td>{{ $user->username }}</td>
                                            <td>
                                                @if ($user->role == 1)
                                                    Super Admin
                                                @elseif($user->role == 2)
                                                    Admin
                                                @else
                                                    Utilisateur
                                                @endif
                                            </td>
                                            <td>{{ $user->last_time_connect ?? 'Jamais' }}</td>
                                            <td>
                                                @if ($user->role == 1)
                                                    Super Admin
                                                @elseif($user->role == 2)
                                                    Admin
                                                @else
                                                    Utilisateur
                                                @endif
                                            </td>
                                            <td>{{ $user->last_time_connect ?? 'Jamais' }}</td>
                                            <td>
                                                @if ($user->status == 1)
                                                    <span class="status completed">Actif</span>
                                                @elseif($user->status == 2)
                                                    <span class="status pending">Bloqué</span>
                                                @else
                                                    <span class="status process">Supprimé</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            {{ $slot }}
