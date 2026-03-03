@if (count($results['categories']) > 0)
    <div class="search-section">
        <h3>Catégories ({{ count($results['categories']) }})</h3>
        <div class="table-data">
            <div class="order">
                <table>
                    <thead>
                        <tr>
                            <th>Nom de la catégorie</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results['categories'] as $category)
                            <tr onclick="openCategoryModal('{{ $category->id }}', '{{ addslashes($category->category_name) }}', '{{ $category->status }}', '{{ $category->created_at }}', '{{ $category->updated_at }}')"
                                style="cursor: pointer;">
                                <td>{{ $category->category_name }}</td>
                                <td>
                                    @if ($category->status == 1)
                                        <span class="status completed">Actif</span>
                                    @elseif($category->status == 2)
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
