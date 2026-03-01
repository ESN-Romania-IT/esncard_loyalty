<x-app-layout>
    <header class="flex flex-col items-center justify-center mt-2 bg-white p-6 rounded-b-full h-44">
        <a href="/" class="flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
        </a>
        <h1 class="text-3xl font-bold text-center mt-4 text-[#2e3192]">Business Offers</h1>
    </header>
    <div class="max-w-7xl mx-auto mt-10 bg-white shadow-md p-6 rounded-3xl border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold">Offers</h2>
                <p class="text-sm text-gray-600">Manage your business offers</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('business.dashboard') }}"
                    class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a] inline-flex items-center gap-1">
                    ← Back to Dashboard
                </a>

                <a href="{{ route('business.offers.create') }}"
                    class="bg-[#ec008c] text-white px-4 py-2 rounded-3xl hover:bg-[#be0070]">
                    + New Offer
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('business.offers.index') }}" class="flex flex-wrap gap-3 mb-6">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search by name..."
                class="w-full sm:w-64 border p-2 rounded-3xl">

            <select name="active" class="border p-2 rounded-3xl w-full sm:w-40">
                <option value="" {{ $active === null || $active === '' ? 'selected' : '' }}>All</option>
                <option value="1" {{ $active === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $active === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            <select name="sort" class="border p-2 rounded-3xl w-full sm:w-56">
                <option value="redemptions_desc" {{ $sort === 'redemptions_desc' ? 'selected' : '' }}>
                    Redemptions (High → Low)
                </option>
                <option value="redemptions_asc" {{ $sort === 'redemptions_asc' ? 'selected' : '' }}>
                    Redemptions (Low → High)
                </option>
            </select>

            <button class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl hover:bg-[#25287a]">
                Filter
            </button>
        </form>

        <div class="overflow-x-auto rounded-3xl border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-[#2e3192]/10 text-[#2e3192]">
                    <tr>
                        <th class="p-3 border-b">ID</th>
                        <th class="p-3 border-b">Title</th>
                        <th class="p-3 border-b">Max uses / client</th>
                        <th class="p-3 border-b">Active</th>
                        <th class="p-3 border-b">Redemptions</th>
                        <th class="p-3 border-b w-36">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offers as $offer)
                        <tr class="border-t">
                            <td class="p-3 border-b">{{ $offer->id }}</td>
                            <td class="p-3 border-b">{{ $offer->title }}</td>
                            <td class="p-3 border-b">{{ $offer->uses_per_client }}</td>
                            <td class="p-3 border-b">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $offer->is_active ? 'bg-[#7ac143]/15 text-[#4f8a27]' : 'bg-[#ec008c]/15 text-[#a2005f]' }}">
                                    {{ $offer->is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="p-3 border-b">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-[#7ac143]/15 text-[#4f8a27]">
                                    {{ $offer->redemptions_count }}
                                </span>
                            </td>
                            <td class="p-3 border-b">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('business.offers.show', $offer) }}"
                                        class="bg-[#2e3192] text-white px-3 py-1 rounded-3xl hover:bg-[#25287a]">
                                        View
                                    </a>
                                    <a href="{{ route('business.offers.edit', $offer) }}"
                                        class="bg-[#ec008c] text-white px-3 py-1 rounded-3xl hover:bg-[#be0070]">
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

    <x-site-footer />
</x-app-layout>
