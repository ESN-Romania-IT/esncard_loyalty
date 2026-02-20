<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 bg-white shadow-md p-6 rounded-3xl border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-[#2e3192]">New Offer</h2>
            <a href="{{ route('business.offers.index') }}"
                class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a] inline-flex items-center gap-1">
                ← Offers
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                Please fix the errors below.
            </div>
        @endif

        <form method="POST" action="{{ route('business.offers.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full border p-2 rounded-3xl"
                    required>
                @error('title')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max uses per client</label>
                <input type="number" name="uses_per_client" value="{{ old('uses_per_client', 1) }}" min="1"
                    max="1000" class="w-full border p-2 rounded-3xl" required>
                @error('uses_per_client')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input id="is_active" type="checkbox" name="is_active" value="1" class="accent-[#7ac143]"
                    {{ old('is_active') ? 'checked' : '' }}>
                <label for="is_active" class="text-sm text-gray-700">Active</label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-[#ec008c] text-white px-4 py-2 rounded-3xl hover:bg-[#be0070]">
                    Create Offer
                </button>
                <a href="{{ route('business.offers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <x-site-footer />
</x-app-layout>
