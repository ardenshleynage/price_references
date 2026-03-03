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
                    <tr onclick="openUserModal({{ $prod->id }}, '{{ $prod->product_name }}', '{{ $prod->post_scriptum ?: 'Aucun' }}', {{ $prod->single_price }}, '{{ $prod->detailed_price ?: 'Aucun' }}', {{ $prod->status }}, '{{ $prod->created_at_formatted }}', '{{ $prod->updated_at_formatted }}', '{{ $prod->branch_name }}', '{{ $prod->category_name }}', '{{ $prod->branch->id }}', '{{ $prod->category->id }}')"
                        style="cursor: pointer;">
                        <td>
                            <p>{{ $prod->product_name }}</p>
                        </td>
                        <td>{{ $prod->single_price }}</td>

                        <td>{{ $prod->created_at }}</td>

                        <td>{{ $prod->updated_at }}</td>
                        <td>
                            @switch($prod->status)
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
