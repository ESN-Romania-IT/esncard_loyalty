<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Edit Offer</h2>
            <a href="{{ route('business.offers.show', $offer) }}" class="text-blue-600 hover:underline text-sm">
                ← Back to Offer
            </a>
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                Please fix the errors below.
            </div>
        @endif

        <form method="POST" action="{{ route('business.offers.update', $offer) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $offer->title) }}"
                    class="w-full border p-2 rounded" required>
                @error('title')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max uses per client</label>
                <input type="number" name="uses_per_client"
                    value="{{ old('uses_per_client', $offer->uses_per_client) }}" min="1" max="1000"
                    class="w-full border p-2 rounded" required>
                @error('uses_per_client')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    If reduced below existing redemptions, no new redemptions will be allowed for those clients.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <input id="is_active" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $offer->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm text-gray-700">Active</label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Changes
                </button>
                <a href="{{ route('business.offers.show', $offer) }}" class="text-sm text-gray-600 hover:text-gray-900">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
