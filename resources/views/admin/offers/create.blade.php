<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold">Create Offer</h2>
                <p class="text-sm text-gray-600">Business: {{ $business->business_name }}</p>
            </div>

            <a href="{{ route('admin.businesses.offers.index', $business) }}" class="text-blue-600 hover:underline">
                ← Back to offers
            </a>
        </div>

        <form method="POST" action="{{ route('admin.businesses.offers.store', $business) }}">
            @csrf

            <label class="block mb-2">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border p-2 rounded mb-2">
            @error('title')
                <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
            @enderror

            <label class="block mb-2">Max uses per client</label>
            <input type="number" name="uses_per_client" min="1" value="{{ old('uses_per_client', 1) }}"
                class="w-full border p-2 rounded mb-2">
            @error('uses_per_client')
                <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
            @enderror

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="mr-2"
                        {{ old('is_active', 1) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
                @error('is_active')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
                Create Offer
            </button>
        </form>
    </div>
</x-app-layout>
