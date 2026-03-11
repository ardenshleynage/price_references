<div class="table-data">
    <div class="order">
        <table>
            <thead>
                <tr>
                    <th>Nom de la brance</th>
                    <th>Créer le</th>
                    <th>Modifier le</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($branches as $branche)
                    <tr onclick="openUserModal({{ $branche->id }}, '{{ $branche->branche_name }}', {{ $branche->status }}, '{{ $branche->created_at }}', '{{ $branche->updated_at }}')"
                        style="cursor: pointer;">
                        <td>
                            <p>{{ $branche->branche_name }}</p>
                        </td>
                        <td>{{ $branche->created_at }}</td>

                        <td>{{ $branche->updated_at }}</td>
                        <td>
                            @switch($branche->status)
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
    @if ($branches->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
            {{ $branches->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>
{{ $slot }}
