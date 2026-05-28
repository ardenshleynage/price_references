<div>
    <ul class="box-info">
        <li>
            <a href="{{ route($rolePrefix . '_products') }}">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">
                    <h3>{{ $totalProducts }}</h3>
                    <p>Produits</p>
                </span>
            </a>
        </li>
        @if ($rolePrefix === 'super_admin')
            <li>
                <a href="{{ route($rolePrefix . '_users') }}">
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3>{{ $totalUsers }}</h3>
                        <p>Utilisateurs</p>
                    </span>
                </a>
            </li>
        @endif
        <li>
            <a href="{{ route($rolePrefix . '_categories') }}">
                <i class='bx bxs-doughnut-chart'></i>
                <span class="text">
                    <h3>{{ $totalCategories }}</h3>
                    <p>Catégories</p>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ route($rolePrefix . '_branches') }}">
                <i class='bx bxs-business'></i>
                <span class="text">
                    <h3>{{ $totalBranches }}</h3>
                    <p>Branches</p>
                </span>
            </a>
        </li>
    </ul>
</div>
