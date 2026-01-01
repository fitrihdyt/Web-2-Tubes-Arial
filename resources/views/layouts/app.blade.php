<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookMe</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
</head>

<body class="bg-neutral-50 min-h-screen">

<!-- NAVBAR -->
<nav id="navbar"
     class="fixed top-0 w-full z-[9999] transition-all duration-300 bg-white/80 backdrop-blur border-b">

    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">

        <!-- LEFT -->
        <div class="flex items-center gap-10">

            <!-- LOGO -->
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-neutral-900">
                BookMe<span class="text-neutral-400">.</span>
            </a>

            <!-- DESKTOP MENU -->
            <div class="hidden md:flex gap-8 text-sm font-medium nav-container">

                @guest
                    <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                @endguest

                @auth
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                        <a href="{{ route('bookings.history')}}" class="nav-link">My Bookings</a>
                    @endif
                @endauth

                @auth
                    @if(auth()->user()->role === 'hotel_admin')
                        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                        <a href="{{ route('hotels.index') }}" class="nav-link">Hotels</a>
                        <a href="{{ route('rooms.index') }}" class="nav-link">Rooms</a>
                    @endif
                @endauth

                @auth
                    @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                        <a href="{{ route('hotels.index') }}" class="nav-link">Hotels</a>
                        <a href="#" class="nav-link">Users</a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-4">

            @guest
                <a href="{{ route('login') }}" class="text-sm text-neutral-600 hover:text-neutral-900 transition">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-neutral-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-neutral-800 transition">
                    Get Started
                </a>
            @endguest

            @auth
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

                    <div id="profileMenu"
                         class="hidden absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border overflow-hidden">
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-3 text-sm hover:bg-neutral-100">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-3 text-sm text-red-500 hover:bg-neutral-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <button id="mobileBtn" class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>
</nav>

<!-- CONTENT -->
<main class="pt-20">
    @yield('content')
</main>

<!-- JS -->
<script>
    const navbar = document.getElementById('navbar');
    const navLinks = document.querySelectorAll('.nav-link');

    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    // NAVBAR SHADOW ON SCROLL
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('shadow-sm', window.scrollY > 20);
    });

    // ACTIVE MENU
    const currentPath = window.location.pathname;
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });

    // STAGGER ANIMATION
    navLinks.forEach((link, i) => {
        link.style.animationDelay = `${i * 0.08}s`;
        link.classList.add('nav-animate');
    });

    // ===== PROFILE DROPDOWN FIX =====
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // ⬅️ penting
            profileMenu.classList.toggle('hidden');
        });

        window.addEventListener('click', (e) => {
            if (!profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });
    }
</script>

<<<<<<< HEAD
=======

<!-- STYLE -->
<style>
    .nav-link {
        position: relative;
        color: #525252;
        padding: 4px 0;
        transition: color 0.3s ease, transform 0.2s ease;
        opacity: 0;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -6px;
        width: 0%;
        height: 2px;
        background-color: #171717;
        transition: width 0.3s ease;
    }

    .nav-link:hover {
        color: #171717;
        transform: translateY(-1px);
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }

    .nav-link.active {
        color: #171717;
        font-weight: 600;
    }

    /* STAGGER */
    .nav-animate {
        animation: fadeSlide 0.5s ease forwards;
    }

    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

>>>>>>> be1303c696a4846ed7aee6cd13fdbb1b901cbd81
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
@stack('scripts')

</body>
</html>
