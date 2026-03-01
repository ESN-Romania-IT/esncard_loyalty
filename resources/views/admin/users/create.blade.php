<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-2xl mx-auto mt-2 bg-white shadow-md p-6 rounded-3xl border border-gray-200">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-[#2e3192]">Create User</h2>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a]">
                    ← Back to users
                </a>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div id="standard-fields">
                    <label class="block mb-2 text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                        class="w-full border border-gray-300 p-2 rounded-3xl mb-4">

                    <label class="block mb-2 text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                        class="w-full border border-gray-300 p-2 rounded-3xl mb-4">
                </div>

                <div id="business-fields">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Business Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name') }}"
                        class="w-full border border-gray-300 p-2 rounded-3xl mb-4">
                </div>


                <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input name="email" type="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 p-2 rounded-3xl mb-2">
                @error('email')
                    <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
                @enderror

                <div id="esn-code">
                    <label class="block mb-2 text-sm font-medium text-gray-700">ESNcard Code</label>
                    <input name="esncard_code" value="{{ old('esncard_code', $user->esncard_code ?? '') }}"
                        class="w-full border border-gray-300 p-2 rounded-3xl mb-2">
                    @error('esncard_code')
                        <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
                    @enderror
                </div>

                <label class="block mb-2 text-sm font-medium text-gray-700">Account Type</label>
                <select name="role" id="role" class="w-full border border-gray-300 p-2 rounded-3xl mb-4">
                    <option value="standard_user" {{ old('role') === 'standard_user' ? 'selected' : '' }}>Standard
                    </option>
                    <option value="business_user" {{ old('role') === 'business_user' ? 'selected' : '' }}>Business
                    </option>
                    @if (isset($allowAdmin) && $allowAdmin)
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    @endif
                </select>
                @error('role')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror


                <label class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                <input name="password" type="password" class="w-full border border-gray-300 p-2 rounded-3xl mb-2">
                @error('password')
                    <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
                @enderror

                <label class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
                <input name="password_confirmation" type="password"
                    class="w-full border border-gray-300 p-2 rounded-3xl mb-4">

                <button class="w-full bg-[#ec008c] text-white p-2 rounded-3xl hover:bg-[#be0070]">
                    Create
                </button>
            </form>
        </div>
    </div>

    <x-site-footer />
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
