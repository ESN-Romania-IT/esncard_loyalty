<x-app-layout>
    {{-- Login Form --}}
    {{-- Header --}}
    <header class="flex flex-col items-center justify-center mt-2 bg-white p-6 rounded-b-full h-44">
        <a href="/" class="flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
        </a>
        <h1 class="text-3xl font-bold text-center mt-4">ESN Card Loyalty</h1>
    </header>

    {{-- Content --}}
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md p-6 rounded-3xl border border-gray-200 pt-10 ">

        <h2 class="text-xl font-bold mb-4 text-center">Login</h2>

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <label class="block mb-2 rounded-3xl">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded-3xl mb-4"
                autofocus>
            @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mb-2 rounded-3xl">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded-3xl mb-4">
            @error('password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="mr-2" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-black text-white p-2 rounded-3xl hover:bg-black/80 transition duration-200">
                Login
            </button>
        </form>

        <div class="mt-4 text-center text-sm">
            <span>Don't have an account?</span>
            <a href="{{ route('register.show') }}" class="text-blue-600 hover:underline">
                Register
            </a>
        </div>

    </div>
    {{-- End of Login Form --}}
    {{-- Footer --}}
    <x-site-footer />
</x-app-layout>
