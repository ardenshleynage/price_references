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
                    <tr onclick="openUserModal({{ $cat->id }}, '{{ addslashes($cat->category_name) }}', {{ $cat->status }}, '{{ $cat->created_at->format('d/m/Y H:i') }}', '{{ $cat->updated_at->format('d/m/Y H:i') }}')"
                        style="cursor: pointer;">
                        <td>
                            <p>{{ $cat->category_name }}</p>
                        </td>
                        <td>{{ $cat->created_at->format('d/m/Y H:i') }}</td>

                        <td>{{ $cat->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @switch($cat->status)
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
    @if ($categories->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
            {{ $categories->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>
{{ $slot }}
