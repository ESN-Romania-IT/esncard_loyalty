<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-7xl mx-auto bg-white shadow-md p-6 rounded-3xl border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-[#2e3192]">Offers</h2>
                    <p class="text-sm text-gray-600">
                        Business: {{ $business->business_name }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.businesses.index') }}"
                        class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a]">
                        ← Back to Businesses
                    </a>

                    <a href="{{ route('admin.businesses.offers.create', $business) }}"
                        class="bg-[#ec008c] text-white px-4 py-2 rounded-3xl hover:bg-[#be0070] text-sm">
                        + New Offer
                    </a>
                </div>

            </div>

            @if (session('status'))
                <div class="mb-4 p-3 rounded-3xl bg-[#7ac143]/15 text-[#4f8a27] border border-[#7ac143]/30">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded-3xl border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-[#2e3192]/10 text-[#2e3192]">
                        <tr>
                            <th class="p-3 border-b">ID</th>
                            <th class="p-3 border-b">Title</th>
                            <th class="p-3 border-b">Max uses / client</th>
                            <th class="p-3 border-b">Active</th>
                            <th class="p-3 border-b w-56">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            <tr class="border-t">
                                <td class="p-3 border-b">{{ $offer->id }}</td>
                                <td class="p-3 border-b font-medium text-gray-800">{{ $offer->title }}</td>
                                <td class="p-3 border-b">{{ $offer->max_uses_per_client }}</td>
                                <td class="p-3 border-b">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $offer->is_active ? 'bg-[#7ac143]/15 text-[#4f8a27]' : 'bg-[#ec008c]/15 text-[#a2005f]' }}">
                                        {{ $offer->is_active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="p-3 border-b">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.businesses.offers.show', [$business, $offer]) }}"
                                            class="bg-[#2e3192] text-white px-3 py-1 rounded-3xl hover:bg-[#25287a] text-sm">
                                            View
                                        </a>

                                        <a href="{{ route('admin.businesses.offers.edit', [$business, $offer]) }}"
                                            class="bg-[#ec008c] text-white px-3 py-1 rounded-3xl hover:bg-[#be0070] text-sm">
                                            Edit
                                        </a>

                                        <form method="POST"
                                            action="{{ route('admin.businesses.offers.destroy', [$business, $offer]) }}"
                                            onsubmit="return confirm('Delete this offer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="bg-red-600 text-white px-3 py-1 rounded-3xl hover:bg-red-700 text-sm">
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
    </div>

    <x-site-footer />
</x-app-layout>
