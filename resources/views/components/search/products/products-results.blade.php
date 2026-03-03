@if (count($results['products']) > 0)
    <div class="search-section">
        <h3>Produits ({{ count($results['products']) }})</h3>
        <div class="table-data">
            <div class="order">
                <table>
                    <thead>
                        <tr>
                            <th>Nom du produit</th>
                            <th>Prix</th>
                            <th>Description</th>
                            <th>Branche</th>
                            <th>Catégorie</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results['products'] as $product)
                            @php
                                $productName = addslashes($product->product_name);
                                $postScriptum = isset($product->post_scriptum) ? addslashes($product->post_scriptum) : 'Aucun';
                                $detailedPrice = isset($product->detailed_price) ? addslashes($product->detailed_price) : 'Aucun';
                                $branchName = isset($product->branch->branche_name) ? addslashes($product->branch->branche_name) : 'Aucun';
                                $categoryName = isset($product->category->category_name) ? addslashes($product->category->category_name) : 'Aucun';
                            @endphp
                            <tr onclick="openProductModal('{{ $product->id }}', '{{ $productName }}', '{{ $postScriptum }}', '{{ $product->single_price }}', '{{ $detailedPrice }}', '{{ $product->status }}', '{{ $product->created_at_formatted }}', '{{ $product->updated_at_formatted }}', '{{ $branchName }}', '{{ $categoryName }}', '{{ $product->branch_id ?? '' }}', '{{ $product->category_id ?? '' }}')"
                                style="cursor: pointer;">
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->single_price }} HTG</td>
                                <td>{{ $product->post_scriptum ?? 'Aucun' }}</td>
                                <td>{{ $product->branch->branche_name ?? 'Aucun' }}</td>
                                <td>{{ $product->category->category_name ?? 'Aucun' }}</td>
                                <td>
                                    @if ($product->status == 1)
                                        <span class="status completed">Actif</span>
                                    @elseif($product->status == 2)
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
