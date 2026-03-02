<x-app-layout>
    <nav class="relative w-full bg-white shadow-sm z-50">
        <div class="w-full px-10 py-6 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/icons/ESN_Logo.svg') }}" alt="ESN Logo" class="h-10 w-auto">
                <span class="text-xl font-semibold text-black tracking-wide">
                    ESN Loyalty
                </span>
            </div>

        </div>
        <div class="block w-full h-2 bg-gradient-to-r from-[#00AEEF] via-[#8DC63F] via-[#F7941D] to-[#EC008C]"></div>
    </nav>

    <div class="min-h-screen flex flex-col items-center justify-center bg-white text-center px-6 py-20">

        <h1 class="text-4xl md:text-5xl font-bold text-black mb-6">
            Join the ESN Loyalty Experience
        </h1>

        <p class="text-gray-600 text-lg max-w-xl mb-10">
            Collect points. Unlock exclusive rewards. Be part of the ESN community.
        </p>

        <div class="flex space-x-4">
            <a href="{{ auth()->check() ? route('me') : route('register.show') }}"
                class="bg-[#00AEEF] hover:opacity-90 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition">
                Create Account
            </a>

            <a href="{{ auth()->check() ? route('me') : route('login') }}"
                class="border border-black text-black px-6 py-3 rounded-lg hover:bg-black hover:text-white transition">
                Login
            </a>
        </div>
    </div>

    <x-site-footer />
</x-app-layout>
