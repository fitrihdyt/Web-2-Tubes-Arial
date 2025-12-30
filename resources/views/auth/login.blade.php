<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | BookMe</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex">

    <div class="hidden lg:block w-2/3 relative">
        <img
            src="https://i.pinimg.com/1200x/02/5b/3f/025b3fb3bd9ad83b8c0d8a89b1d67794.jpg"
            class="absolute inset-0 w-full h-full object-cover"
            alt="Hotel"
        />
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 h-full flex flex-col justify-end p-12 text-white">
            <h1 class="text-3xl font-bold mb-2">BookMe</h1>
            <p class="text-sm max-w-md">
                Booking kamar hotel jadi lebih mudah dan cepat.
                
            </p>
        </div>
    </div>

   <div class="w-full lg:w-1/2 flex items-center justify-center 
                bg-gradient-to-br from-[#eef6f8] to-[#dcecef] px-6">

        <div class="w-full max-w-md rounded-3xl 
                    bg-white/70 backdrop-blur-xl 
                    shadow-2xl p-10 border border-white/40">

            <h2 class="text-2xl font-bold text-[#134662] mb-2">Login</h2>
            <p class="text-sm text-gray-500 mb-6">
                Masuk untuk melanjutkan booking
            </p>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        name="email"
                        required
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#134662] focus:ring-[#134662]"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#134662] focus:ring-[#134662]"
                    >
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded">
                        Remember me
                    </label>

                    <a href="{{ route('password.request') }}"
                       class="text-[#134662] hover:underline">
                        Lupa password?
                    </a>
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#134662] hover:bg-[#0f3a4e] text-white font-semibold py-3 rounded-lg transition">
                    Login
                </button>

                <p class="text-sm text-center text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-[#134662] font-medium hover:underline">
                        Daftar
                    </a>
                </p>
            </form>

        </div>
    </div>

</body>
</html>
