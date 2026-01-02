<x-app-layout>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md p-6 rounded">

        <h2 class="text-xl font-bold mb-4">Login</h2>

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <label class="block mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded mb-4"
                autofocus>
            @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mb-2">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded mb-4">
            @error('password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="mr-2" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
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
</x-app-layout>
