<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-3xl mx-auto mt-2 bg-white shadow-md p-6 rounded-3xl border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-[#2e3192]">Edit Offer</h2>
                    <p class="text-sm text-gray-600">
                        Business: {{ $business->business_name }} · Offer #{{ $offer->id }}
                    </p>
                </div>

                <a href="{{ route('admin.businesses.offers.index', $business) }}"
                    class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a]">
                    ← Back to offers
                </a>
            </div>

            <form method="POST" action="{{ route('admin.businesses.offers.update', [$business, $offer]) }}">
                @csrf
                @method('PUT')

                <label class="block mb-2 text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" value="{{ old('title', $offer->title) }}"
                    class="w-full border border-gray-300 p-2 rounded-3xl mb-2">
                @error('title')
                    <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
                @enderror

                <label class="block mb-2 text-sm font-medium text-gray-700">Max uses per client</label>
                <input type="number" name="uses_per_client" min="1"
                    value="{{ old('uses_per_client', $offer->uses_per_client) }}"
                    class="w-full border border-gray-300 p-2 rounded-3xl mb-2">
                @error('uses_per_client')
                    <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
                @enderror

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="mr-2"
                            {{ old('is_active', $offer->is_active) ? 'checked' : '' }}>
                        <span>Active</span>
                    </label>
                    @error('is_active')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-[#ec008c] text-white p-2 rounded-3xl hover:bg-[#be0070]">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

    <x-site-footer />
</x-app-layout>
