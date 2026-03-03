@if (count($results['branches']) > 0)
    <div class="search-section">
        <h3>Branches ({{ count($results['branches']) }})</h3>
        <div class="table-data">
            <div class="order">
                <table>
                    <thead>
                        <tr>
                            <th>Nom de la branche</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results['branches'] as $branch)
                            <tr onclick="openBranchModal('{{ $branch->id }}', '{{ addslashes($branch->branche_name) }}', '{{ $branch->status }}', '{{ $branch->created_at }}', '{{ $branch->updated_at }}')"
                                style="cursor: pointer;">
                                <td>{{ $branch->branche_name }}</td>
                                <td>
                                    @if ($branch->status == 1)
                                        <span class="status completed">Actif</span>
                                    @elseif($branch->status == 2)
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
