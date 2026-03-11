@if (count($results['categories']) > 0)
    <div class="search-section">
        <h3>Catégories ({{ count($results['categories']) }})</h3>
        <div class="table-data">
            <div class="order">
                <table>
                    <thead>
                        <tr>
                            <th>Nom de la catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results['categories'] as $category)
                            <tr onclick="openCategoryModal('{{ $category->id }}', '{{ addslashes($category->category_name) }}', '{{ $category->status }}', '{{ $category->created_at }}', '{{ $category->updated_at }}')"
                                style="cursor: pointer;">
                                <td>{{ $category->category_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
{{ $slot }}
