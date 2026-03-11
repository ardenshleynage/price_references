<div class="table-data">
    <div class="order">
        <table>
            <thead>
                <tr>
                    <th>Nom de la catégorie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $cat)
                    <tr onclick="openCategoryModal('{{ $cat->id }}', '{{ addslashes($cat->category_name) }}', '{{ $cat->status }}', '{{ $cat->created_at->format('d/m/Y H:i') }}', '{{ $cat->updated_at->format('d/m/Y H:i') }}')"
                        style="cursor: pointer;">
                        <td>
                            <p>{{ $cat->category_name }}</p>
                        </td>
                    </tr>
                @empty
                        <tr>
                            <td style="text-align: center; padding: 20px;">
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
