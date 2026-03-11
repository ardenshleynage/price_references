@if (count($results['products']) > 0)
    <div class="search-section">
        <h3>Produits ({{ count($results['products']) }})</h3>
        <div class="table-data">
            <div class="order">
                <table>
                    <thead>
                        <tr>
                            <th>Nom du produit</th>
                            <th>Prix unitaire</th>
                            <th>Informations complémentaires</th>
                            <th>Prix détaillé</th>
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
                                <td>{{ $product->detailed_price ?? 'Aucun' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
{{ $slot }}
