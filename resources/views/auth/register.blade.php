<x-app-layout>
    {{-- Header --}}
    <header class="flex flex-col items-center justify-center mt-2 bg-white p-6 rounded-b-full h-44">
        <a href="/" class="flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
        </a>
        <h1 class="text-3xl font-bold text-center mt-4">ESN Card Loyalty</h1>
    </header>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md p-6 rounded-3xl border border-gray-200 pt-10 ">

        <h2 class="text-xl font-bold mb-4">Register</h2>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <label class="block mb-2">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}"
                class="w-full border p-2 rounded-3xl mb-4">
            @error('first_name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mb-2">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}"
                class="w-full border p-2 rounded-3xl mb-4">
            @error('last_name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded-3xl mb-4">
            @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mb-2">ESNcard Code</label>
            <input type="text" name="esncard_code" value="{{ old('esncard_code') }}"
                class="w-full border p-2 rounded-3xl mb-4">
            @error('esncard_code')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mb-2">Password</label>
            <div class="relative mb-2">
                <input type="password" name="password" id="password" autocomplete="new-password"
                    class="w-full border p-2 rounded-3xl pr-12">

                <button type="button" onclick="togglePassword('password', this)"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-sm text-gray-600 hover:text-gray-900">
                    <button type="button" onclick="togglePassword('password', this)"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-sm text-gray-600 hover:text-gray-900">
                        Show
                    </button>
            </div>

            @error('password')
                <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror


            <label class="block mb-2">Confirm Password</label>
            <div class="relative mb-4">
                <input type="password" name="password_confirmation" id="password_confirmation"
                    autocomplete="new-password" class="w-full border p-2 rounded-3xl pr-12">

                <button type="button" onclick="togglePassword('password_confirmation', this)"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-sm text-gray-600 hover:text-gray-900">
                    <button type="button" onclick="togglePassword('password_confirmation', this)"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-sm text-gray-600 hover:text-gray-900">
                        Show
                    </button>
            </div>

            @error('password_confirmation')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror



            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="terms" class="mr-2" {{ old('terms') ? 'checked' : '' }}>
                    <span>I accept the terms and conditions</span>
                </label>

                @error('terms')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="w-full bg-black text-white p-2 rounded-3xl hover:bg-black/80">
                Register
            </button>
        </form>
        <div class="mt-4 text-center text-sm">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                Login
            </a>
        </div>
    </div>
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId); <
            script >
                function togglePassword(inputId, btn) {
                    const input = document.getElementById(inputId);

                    if (input.type === 'password') {
                        input.type = 'text';
                        btn.textContent = 'Hide';
                    } else {
                        input.type = 'password';
                        btn.textContent = 'Show';
                    }
                }
    </script>
    <footer
        class="max-w-4xl mx-auto flex items-center justify-center mt-10 gap-4 border-t border-gray-100 py-4 text-center">
        <a href="{{ route('about') }}" class="text-sm text-white hover:underline pr-20">
            About us
        </a>
        <a href="{{ route('terms-and-conditions') }}" class="text-sm text-white hover:underline pl-20">
            Terms & Conditions
        </a>
    </footer>

    <div class="flex h-4 w-full mt-12">
        <div class="flex-1 bg-[#2e3192]"></div>
        <div class="flex-1 bg-[#ec008c]"></div>
        <div class="flex-1 bg-[#7ac143]"></div>
    </div>
</x-app-layout>
