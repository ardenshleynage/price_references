    <nav class="navbar">
        <div class="navbar-brand">
            <img src="{{ asset('images/bx--bxs-smile.png') }}" alt="Logo">
            <span>Price References</span>
        </div>
        <div class="navbar-actions">
            <button class="navbar-btn" onclick="window.location.reload()">
                <i class='bx bx-refresh'></i>
            </button>
            <a href="{{ route('mobile.profile') }}" class="navbar-btn">
                <i class='bx bxs-user'></i>
            </a>
            <a href="{{ route('mobile.search') }}" class="navbar-btn">
                <i class='bx bx-search'></i>
            </a>
        </div>
    </nav>
    {{ $slot }}
