<div class="table-data">
    <div class="order">
        <table>
            <thead>
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>Dernière connexion</th>
                    <th>Rôle</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr onclick="openUserModal({{ $user->id }}, '{{ $user->username }}', {{ $user->role }}, {{ $user->status }}, '{{ $user->last_time_connect ?? 'Jamais' }}', '{{ $user->created_at }}', '{{ $user->updated_at }}')"
                        style="cursor: pointer;">
                        <td>
                            <p>{{ $user->username }}</p>
                        </td>
                        <td>{{ $user->last_time_connect ?? 'Jamais' }}</td>
                        <td>
                            @switch($user->role)
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
                        <td>
                            @switch($user->status)
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
                                <p style="color: #888; font-style: italic;">{{ $emptyMessage }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $slot }}
