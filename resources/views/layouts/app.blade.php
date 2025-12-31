<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookMe</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body class="bg-neutral-50 min-h-screen">

<!-- NAVBAR -->
<nav id="navbar"
     class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur border-b">

    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">

        <!-- LEFT -->
        <div class="flex items-center gap-10">

            <!-- LOGO -->
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-neutral-900">
                BookMe<span class="text-neutral-400">.</span>
            </a>

            <!-- DESKTOP MENU -->
            <div class="hidden md:flex gap-8 text-sm font-medium text-neutral-600">

                {{-- ================= GUEST ================= --}}
                @guest
                    <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                    <a href="{{ route('hotels.index') }}" class="nav-link">Hotels</a>
                    <a href="#" class="nav-link">About</a>
                @endguest

                {{-- ================= USER ================= --}}
                @auth
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                        <a href="{{ route('hotels.index') }}" class="nav-link">Hotels</a>
                        <a href="{{ route('bookings.history') }}" class="nav-link">My Bookings</a>
                        <a href="#" class="nav-link">Cart</a>
                    @endif
                @endauth

                {{-- ================= HOTEL ADMIN ================= --}}
                @auth
                    @if(auth()->user()->role === 'hotel_admin')
                        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                        <a href="{{ route('hotels.index') }}" class="nav-link">Hotels</a>
                        <a href="{{ route('rooms.index') }}" class="nav-link">Rooms</a>
                        <a href="{{ route('admin.bookings') }}" class="nav-link">Bookings</a>
                    @endif
                @endauth

                {{-- ================= SUPER ADMIN ================= --}}
                @auth
                    @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                        <a href="{{ route('hotels.index') }}" class="nav-link">Hotels</a>
                        <a href="#" class="nav-link">Users</a>
                        <a href="{{ route('settings.index') }}" class="nav-link">Settings</a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-4">

            {{-- GUEST --}}
            @guest
                <a href="{{ route('login') }}" class="text-sm text-neutral-600 hover:text-neutral-900">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-neutral-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-neutral-800 transition">
                    Get Started
                </a>
            @endguest

            {{-- AUTH --}}
            @auth
                <!-- PROFILE DROPDOWN -->
                <div class="relative">
                    <button id="profileBtn"
                            class="flex items-center gap-2 px-2 py-1.5 rounded-xl hover:bg-neutral-100 transition">
                        <img
                            src="{{ auth()->user()->avatar
                                ? asset('storage/' . auth()->user()->avatar)
                                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name)
                            }}"
                            class="w-9 h-9 rounded-full border object-cover">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- DROPDOWN -->
                    <div id="profileMenu"
                         class="hidden absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border overflow-hidden">

                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-3 text-sm hover:bg-neutral-100">
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="w-full text-left px-4 py-3 text-sm text-red-500 hover:bg-neutral-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <!-- MOBILE MENU BUTTON -->
            <button id="mobileBtn" class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div id="mobileMenu"
         class="hidden md:hidden px-6 pb-6 space-y-4 bg-white border-t">

        @guest
            <a href="{{ route('dashboard') }}" class="block nav-link">Home</a>
            <a href="{{ route('hotels.index') }}" class="block nav-link">Hotels</a>
            <a href="#" class="block nav-link">About</a>
        @endguest

        @auth
            <a href="{{ route('profile.edit') }}" class="block nav-link">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-500">Logout</button>
            </form>
        @endauth
    </div>
</nav>

<!-- CONTENT -->
<main class="pt-20">
    @yield('content')
</main>

<!-- JS -->
<script>
    // PROFILE DROPDOWN
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    if (profileBtn) {
        profileBtn.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });
    }

    // MOBILE MENU
    const mobileBtn = document.getElementById('mobileBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    mobileBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // CLOSE DROPDOWN ON CLICK OUTSIDE
    window.addEventListener('click', (e) => {
        if (profileMenu && !profileBtn.contains(e.target)) {
            profileMenu.classList.add('hidden');
        }
    });
</script>

<style>
    .nav-link {
        @apply text-neutral-600 hover:text-neutral-900 transition;
    }
</style>

</body>
</html>
