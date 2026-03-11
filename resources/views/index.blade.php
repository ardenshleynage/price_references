<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price References</title>
    <link rel="icon" type="image/png" href="{{ asset('images/bx--bxs-smile.png') }}">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/home.css', 'resources/css/tailwind.css'])
</head>

<body>
    <div class="card">
        <div id="location-selection-screen">

            <h2 class="text-xl font-semibold mb-6 text-gray-700">Choisisez votre rôle</h2>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <button id="btn-admin" class="location-btn" data-role="admin">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    <span class="font-medium text-gray-600">Admin</span>
                </button>
                <button id="btn-user" class="location-btn" data-role="user">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span class="font-medium text-gray-600">Utilisateur</span>
                </button>
            </div>

            <button id="start-btn" class="start-btn" disabled>Continuer</button>
        </div>


    </div>

    <script>
        // --- DOM Elements ---
        const btnAdmin = document.getElementById('btn-admin');
        const btnUser = document.getElementById('btn-user');
        const startBtn = document.getElementById('start-btn');

        // --- State ---
        let selectedRole = null; // 'admin' or 'user'

        // --- Event Listeners ---

        // Role Selection
        btnAdmin.addEventListener('click', () => selectRole('admin'));
        btnUser.addEventListener('click', () => selectRole('user'));

        // Continue Button
        startBtn.addEventListener('click', redirectToRole);


        // --- Functions ---

        function selectRole(role) {
            selectedRole = role;
            // Update button styles
            btnAdmin.classList.toggle('selected', role === 'admin');
            btnUser.classList.toggle('selected', role === 'user');
            // Enable start button
            startBtn.disabled = false;
            console.log("Role selected:", selectedRole);
        }

        function redirectToRole() {
            if (!selectedRole) return;

            const urls = {
                'admin': '{{ route('login') }}',
                {{--                 'user': '{{ route("user_dashboard") }}'
 --}}
            };

            window.location.href = urls[selectedRole];
        }
    </script>
</body>

</html>
