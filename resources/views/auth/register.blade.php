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

            <label class="block mb-2">Password</label>
            <div class="relative mb-2">
                <input type="password"
                    name="password"
                    id="password"
                    autocomplete="new-password"
                    class="w-full border p-2 rounded pr-12">

                <button type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-sm text-gray-600 hover:text-gray-900">
                    Show
                </button>
            </div>

            @error('password')
                <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror


            <label class="block mb-2">Confirm Password</label>
            <div class="relative mb-4">
                <input type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    autocomplete="new-password"
                    class="w-full border p-2 rounded pr-12">

                <button type="button"
                        onclick="togglePassword('password_confirmation', this)"
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

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
                Register
            </button>
        </form>
    </div>
<script>
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

</x-app-layout>
