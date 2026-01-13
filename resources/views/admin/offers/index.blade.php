<x-app-layout>
    <div class="max-w-7xl mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold">Offers</h2>
                <p class="text-sm text-gray-600">
                    Business: {{ $business->business_name }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.businesses.index') }}" class="text-blue-600 hover:underline text-sm">
                    ← Back to Businesses
                </a>

                <a href="{{ route('admin.businesses.offers.create', $business) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + New Offer
                </a>
            </div>

        </div>

        @if (session('status'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 border">ID</th>
                        <th class="p-3 border">Title</th>
                        <th class="p-3 border">Max uses / client</th>
                        <th class="p-3 border">Active</th>
                        <th class="p-3 border w-56">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offers as $offer)
                        <tr class="border-t">
                            <td class="p-3 border">{{ $offer->id }}</td>
                            <td class="p-3 border">{{ $offer->title }}</td>
                            <td class="p-3 border">{{ $offer->max_uses_per_client }}</td>
                            <td class="p-3 border">{{ $offer->is_active ? 'Yes' : 'No' }}</td>
                            <td class="p-3 border">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.businesses.offers.show', [$business, $offer]) }}"
                                        class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-900">
                                        View
                                    </a>

                                    <a href="{{ route('admin.businesses.offers.edit', [$business, $offer]) }}"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    <form method="POST"
                                        action="{{ route('admin.businesses.offers.destroy', [$business, $offer]) }}"
                                        onsubmit="return confirm('Delete this offer?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-600">No offers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $offers->links() }}
        </div>
    </div>
</x-app-layout>
