<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | BookMe</title>
    @vite(['resources/css/app.css'])

    <style>
        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatSlow {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }
    </style>
</head>

<body class="min-h-screen flex bg-[#eef6f8] overflow-hidden">

    <!-- LEFT IMAGE -->
    <div class="hidden lg:block w-2/3 relative">
        <img
            src="https://i.pinimg.com/1200x/02/5b/3f/025b3fb3bd9ad83b8c0d8a89b1d67794.jpg"
            class="absolute inset-0 w-full h-full object-cover scale-105"
            alt="Hotel"
        />
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 h-full flex flex-col justify-end p-14 text-white animate-[fadeSlide_1s_ease-out]">
            <h1 class="text-4xl font-bold mb-3 tracking-wide">BookMe</h1>
            <p class="text-sm max-w-md leading-relaxed opacity-90">
                Mulai perjalanan booking hotel dengan pengalaman yang nyaman dan eksklusif.
            </p>
        </div>
    </div>

    <!-- RIGHT REGISTER -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 relative">

        <!-- floating blur -->
        <div class="absolute -top-20 -right-20 w-72 h-72 bg-[#134662]/20 rounded-full blur-3xl animate-[floatSlow_6s_ease-in-out_infinite]"></div>
        <div class="absolute bottom-10 left-10 w-48 h-48 bg-[#6fb1c8]/30 rounded-full blur-3xl animate-[floatSlow_8s_ease-in-out_infinite]"></div>

        <div class="relative z-10 w-full max-w-md rounded-3xl 
                    bg-white/75 backdrop-blur-xl 
                    shadow-[0_20px_60px_-15px_rgba(0,0,0,0.25)]
                    p-10 border border-white/40
                    animate-[fadeSlide_0.8s_ease-out]">

            <h2 class="text-3xl font-bold text-[#134662] mb-1">
                Create Account
            </h2>
            <p class="text-sm text-gray-500 mb-8">
                Daftar untuk mulai booking hotel impianmu
            </p>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- NAME -->
                <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="w-full rounded-xl border-gray-300
                               focus:border-[#134662] focus:ring-[#134662]
                               transition-all duration-300
                               group-hover:border-[#134662]/60
                               focus:-translate-y-[1px]
                               focus:shadow-[0_8px_20px_rgba(19,70,98,0.15)]"
                    >
                    @error('name')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-[#134662] focus:ring-[#134662]
                               transition-all duration-300
                               group-hover:border-[#134662]/60
                               focus:-translate-y-[1px]
                               focus:shadow-[0_8px_20px_rgba(19,70,98,0.15)]"
                    >
                    @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-[#134662] focus:ring-[#134662]
                               transition-all duration-300
                               group-hover:border-[#134662]/60
                               focus:-translate-y-[1px]
                               focus:shadow-[0_8px_20px_rgba(19,70,98,0.15)]"
                    >
                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CONFIRM PASSWORD -->
                <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Confirm Password
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full rounded-xl border-gray-300
                               focus:border-[#134662] focus:ring-[#134662]
                               transition-all duration-300
                               group-hover:border-[#134662]/60
                               focus:-translate-y-[1px]
                               focus:shadow-[0_8px_20px_rgba(19,70,98,0.15)]"
                    >
                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="w-full bg-[#134662] text-white font-semibold py-3 rounded-xl
                           transition-all duration-300
                           hover:bg-[#0f3a4e]
                           hover:shadow-[0_12px_30px_rgba(19,70,98,0.4)]
                           active:scale-[0.98]">
                    Register
                </button>

                <p class="text-sm text-center text-gray-600 pt-2">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                       class="text-[#134662] font-medium hover:underline">
                        Login
                    </a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>