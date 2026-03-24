<nav class="bottom-nav">
    <a href="{{ route('mobile.dashboard') }}"
        class="nav-item {{ request()->routeIs('mobile.dashboard') ? 'active' : '' }}">
        <i class='bx bxs-home'></i>
        <span>Accueil</span>
    </a>
    <a href="{{ route('mobile.products') }}" class="nav-item {{ request()->routeIs('mobile.products') ? 'active' : '' }}">
        <i class='bx bxs-package'></i>
        <span>Produits</span>
    </a>
    <a href="{{ route('mobile.categories') }}"
        class="nav-item {{ request()->routeIs('mobile.categories') ? 'active' : '' }}">
        <i class='bx bxs-folder'></i>
        <span>Catégories</span>
    </a>
    <a href="{{ route('mobile.branches') }}"
        class="nav-item {{ request()->routeIs('mobile.branches') ? 'active' : '' }}">
        <i class='bx bxs-store'></i>
        <span>Branches</span>
    </a>
    <a href="#" class="nav-item nav-users" id="usersNavItem" style="display: none;">
        <i class='bx bxs-group'></i>
        <span>Utilisateurs</span>
    </a>
</nav>

<script>
    // Show users tab only for Super Admin (role 1)
    document.addEventListener('DOMContentLoaded', function() {
        const user = getUser();
        const usersNavItem = document.getElementById('usersNavItem');
        
        if (usersNavItem && user.role == 1) {
            usersNavItem.style.display = 'flex';
            usersNavItem.href = '{{ route("super_admin_users") }}';
        }
    });
</script>