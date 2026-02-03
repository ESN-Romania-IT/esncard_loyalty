<x-app-layout>
    <div class="max-w-7xl mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold">Offers</h2>
                <p class="text-sm text-gray-600">Manage your business offers</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('business.dashboard') }}" class="text-blue-600 hover:underline text-sm">
                    ← Back to Dashboard
                </a>

                <a href="{{ route('business.offers.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + New Offer
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('business.offers.index') }}" class="flex flex-wrap gap-3 mb-6">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search by name..."
                class="w-full sm:w-64 border p-2 rounded">

            <select name="active" class="border p-2 rounded w-full sm:w-40">
                <option value="" {{ $active === null || $active === '' ? 'selected' : '' }}>All</option>
                <option value="1" {{ $active === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $active === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            <select name="sort" class="border p-2 rounded w-full sm:w-56">
                <option value="redemptions_desc" {{ $sort === 'redemptions_desc' ? 'selected' : '' }}>
                    Redemptions (High → Low)
                </option>
                <option value="redemptions_asc" {{ $sort === 'redemptions_asc' ? 'selected' : '' }}>
                    Redemptions (Low → High)
                </option>
            </select>

            <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                Filter
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 border">ID</th>
                        <th class="p-3 border">Title</th>
                        <th class="p-3 border">Max uses / client</th>
                        <th class="p-3 border">Active</th>
                        <th class="p-3 border">Redemptions</th>
                        <th class="p-3 border w-36">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offers as $offer)
                        <tr class="border-t">
                            <td class="p-3 border">{{ $offer->id }}</td>
                            <td class="p-3 border">{{ $offer->title }}</td>
                            <td class="p-3 border">{{ $offer->uses_per_client }}</td>
                            <td class="p-3 border">{{ $offer->is_active ? 'Yes' : 'No' }}</td>
                            <td class="p-3 border">{{ $offer->redemptions_count }}</td>
                            <td class="p-3 border">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('business.offers.show', $offer) }}"
                                        class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-900">
                                        View
                                    </a>
                                    <a href="{{ route('business.offers.edit', $offer) }}"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-600">No offers found.</td>
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
