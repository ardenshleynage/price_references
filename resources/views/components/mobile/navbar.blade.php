    <nav class="navbar">
        <div class="navbar-brand">
            <img src="{{ asset('images/bx--bxs-smile.png') }}" alt="Logo">
            <span>Price References</span>
        </div>
        <div class="navbar-actions">
            <a href="{{ route('mobile.profile') }}" class="navbar-btn">
                <i class='bx bxs-user'></i>
            </a>
            <a href="{{ route('mobile.search') }}" class="navbar-btn">
                <i class='bx bx-search'></i>
            </a>
            <button class="navbar-btn" id="themeToggleBtn" onclick="toggleTheme()">
                <i class='bx bxs-moon'></i>
            </button>
            <button class="navbar-btn" onclick="logout()">
                <i class='bx bx-log-out'></i>
            </button>
        </div>
    </nav>
    {{ $slot }}
