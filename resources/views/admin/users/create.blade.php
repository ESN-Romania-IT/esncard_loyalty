<x-app-layout>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md p-6 rounded">

        <h2 class="text-xl font-bold mb-4">Create User</h2>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div id="standard-fields">
                <label class="block mb-2">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}"
                    class="w-full border p-2 rounded mb-4">

                <label class="block mb-2">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}"
                    class="w-full border p-2 rounded mb-4">
            </div>

            <div id="business-fields">
                <label class="block mb-2">Business Name</label>
                <input type="text" name="business_name" value="{{ old('business_name') }}"
                    class="w-full border p-2 rounded mb-4">
            </div>


            <label class="block mb-2">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" class="w-full border p-2 rounded mb-2">
            @error('email')
                <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror

            <div id="esn-code">
                <label class="block mb-2">ESNcard Code</label>
                <input name="esncard_code" value="{{ old('esncard_code', $user->esncard_code ?? '') }}"
                    class="w-full border p-2 rounded mb-2">
                @error('esncard_code')
                    <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
                @enderror
            </div>

            <label class="block mb-2">Account Type</label>
            <select name="role" id="role" class="w-full border p-2 rounded mb-4">
                <option value="standard_user" {{ old('role') === 'standard_user' ? 'selected' : '' }}>Standard</option>
                <option value="business_user" {{ old('role') === 'business_user' ? 'selected' : '' }}>Business</option>
                @if (isset($allowAdmin) && $allowAdmin)
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                @endif
            </select>
            @error('role')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror


            <label class="block mb-2">Password</label>
            <input name="password" type="password" class="w-full border p-2 rounded mb-2">
            @error('password')
                <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror

            <label class="block mb-2">Confirm Password</label>
            <input name="password_confirmation" type="password" class="w-full border p-2 rounded mb-4">

            <button class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
                Create
            </button>

            <div class="mt-4 text-center">
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">
                    Back to users
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const role = document.getElementById('role');
        const standard = document.getElementById('standard-fields');
        const business = document.getElementById('business-fields');
        const esnCode = document.getElementById('esn-code');

        function sync() {
            const v = role.value;
            standard.style.display = (v === 'standard_user') ? 'block' : 'none';
            business.style.display = (v === 'business_user') ? 'block' : 'none';
            esnCode.style.display = (v === 'standard_user') ? 'block' : 'none';
        }

        role.addEventListener('change', sync);
        sync();
    });
</script>
