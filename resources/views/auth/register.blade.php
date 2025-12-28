<x-app-layout>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md p-6 rounded">

        <h2 class="text-xl font-bold mb-4">Register</h2>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <label class="block mb-2">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border p-2 rounded mb-4">
            @error('first_name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mb-2">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}"
                class="w-full border p-2 rounded mb-4">
            @error('last_name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded mb-4">
            @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mb-2">ESNcard Code</label>
            <input type="text" name="esncard_code" value="{{ old('esncard_code') }}"
                class="w-full border p-2 rounded mb-4">
            @error('esncard_code')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            {{-- 🔒 PASSWORD FIELD --}}
            <label class="block mb-2">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded mb-4">
            @error('password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            {{-- 📄 TERMS CHECKBOX --}}
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="terms" class="mr-2" {{ old('terms') ? 'checked' : '' }}>
                    <span>I accept the terms and conditions</span>
                    {{-- You can add <a href="/terms" class="text-blue-600">terms and conditions</a> later --}}
                </label>

                @error('terms')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
                Register
            </button>
        </form>
    </div>
</x-app-layout>
