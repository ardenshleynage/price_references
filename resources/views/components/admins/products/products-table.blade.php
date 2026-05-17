<div class="table-data">
    <div class="order">
        <table>
            <thead>
                <tr>
                    <th>Nom du produit</th>
                    <th>Prix unitaire</th>
                    <th>Créer le</th>
                    <th>Modifier le</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $prod)
                    <tr onclick="openUserModal({{ $prod->id }}, '{{ addslashes($prod->product_name) }}', '{{ addslashes($prod->post_scriptum ?? 'Aucun') }}', {{ $prod->single_price }}, '{{ addslashes($prod->detailed_price ?? 'Aucun') }}', {{ $prod->status }}, '{{ $prod->created_at->format('d/m/Y H:i') }}', '{{ $prod->updated_at->format('d/m/Y H:i') }}', '{{ addslashes($prod->branch->branche_name ?? 'Aucun') }}', '{{ addslashes($prod->category->category_name ?? 'Aucun') }}', '{{ $prod->branch_id ?? '' }}', '{{ $prod->category_id ?? '' }}')"
                        style="cursor: pointer;">
                        <td>
                            <p>{{ $prod->product_name }}</p>
                        </td>
                        <td>{{ $prod->single_price }} $HT</td>

                        <td>{{ $prod->created_at->format('d/m/Y H:i') }}</td>

                        <td>{{ $prod->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @switch($prod->status)
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
                            <td colspan="5" style="text-align: center; padding: 20px;">
                                <p style="color: #888; font-style: italic;">{{ $emptyMessage }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
                {{ $products->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
    {{ $slot }}
