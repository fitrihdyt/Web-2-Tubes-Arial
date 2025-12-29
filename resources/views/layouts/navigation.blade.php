<nav x-data="{ open: false }" class="bg-white border-b sticky top-0 z-50">
    <!-- FULL WIDTH BAR -->
    <div class="w-full">
        <!-- CONTAINER -->
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="flex justify-between h-16 items-center">

                <!-- LEFT -->
                <div class="flex items-center gap-10">
                    <!-- LOGO -->
                    <a href="{{ route('dashboard') }}"
                       class="text-xl font-bold text-neutral-900 tracking-tight">
                        StayFinder
                    </a>

                    <!-- MENU -->
                    <div class="hidden md:flex items-center gap-8 text-sm font-medium text-neutral-600">
                        <a href="{{ route('dashboard') }}"
                           class="hover:text-neutral-900 transition">
                            Home
                        </a>

                        <a href="{{ route('dashboard') }}#hotels"
                           class="hover:text-neutral-900 transition">
                            Explore
                        </a>

                        <a href="{{ route('bookings.index') }}"
                           class="hover:text-neutral-900 transition">
                            My Bookings
                        </a>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('hotels.index') }}" class="hover:text-neutral-900">
                                Hotels
                            </a>
                            <a href="{{ route('rooms.index') }}" class="hover:text-neutral-900">
                                Rooms
                            </a>
                        @endif
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="hidden md:flex items-center gap-6">
                    <span class="text-sm text-neutral-500">
                        {{ Auth::user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="text-sm font-semibold text-white bg-neutral-900 px-4 py-2 rounded-full hover:bg-neutral-800 transition">
                            Logout
                        </button>
                    </form>
                </div>

                <!-- MOBILE BUTTON -->
                <button @click="open = !open" class="md:hidden text-neutral-700 text-2xl">
                    ☰
                </button>
            </div>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="open" x-transition class="md:hidden bg-white border-t">
        <div class="px-6 py-4 space-y-4 text-sm font-medium">
            <a href="{{ route('dashboard') }}" class="block">Home</a>
            <a href="{{ route('dashboard') }}#hotels" class="block">Explore</a>
            <a href="{{ route('bookings.index') }}" class="block">My Bookings</a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('hotels.index') }}" class="block">Hotels</a>
                <a href="{{ route('rooms.index') }}" class="block">Rooms</a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-600 font-semibold mt-2">Logout</button>
            </form>
        </div>
    </div>
</nav>
