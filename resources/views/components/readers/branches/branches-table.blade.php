<div class="table-data">
    <div class="order">
        <table>
            <thead>
                <tr>
                    <th>Nom de la branche</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($branches as $branche)
                    <tr onclick="openBranchModal('{{ $branche->id }}', '{{ addslashes($branche->branche_name) }}', '{{ $branche->status }}', '{{ $branche->created_at->format('d/m/Y H:i') }}', '{{ $branche->updated_at->format('d/m/Y H:i') }}')"
                        style="cursor: pointer;">
                        <td>
                            <p>{{ $branche->branche_name }}</p>
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
    @if ($branches->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
            {{ $branches->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>
{{ $slot }}
