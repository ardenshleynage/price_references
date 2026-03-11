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
                    <tr onclick="openUserModal({{ $branche->id }}, '{{ addslashes($branche->branche_name) }}', {{ $branche->status }}, '{{ $branche->created_at->format('d/m/Y H:i') }}', '{{ $branche->updated_at->format('d/m/Y H:i') }}')"
                        style="cursor: pointer;">
                        <td>
                            <p>{{ $branche->branche_name }}</p>
                        </td>
                        <td>{{ $branche->created_at->format('d/m/Y H:i') }}</td>

                        <td>{{ $branche->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @switch($branche->status)
                                @case(1)
                                    <span class="status completed">Actif</span>
                                @break
                                @case(2)
                                    <span class="status process">Corbeille</span>
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
