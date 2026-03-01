<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-7xl mx-auto bg-white shadow-md p-6 rounded-3xl border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-[#2e3192]">Businesses</h2>
                    <p class="text-sm text-gray-600">Manage all registered business profiles</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="bg-[#2e3192] text-white px-4 py-2 rounded-3xl text-sm hover:bg-[#25287a]">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>

            <form method="GET" class="flex flex-wrap gap-3 mb-6">
                <input type="text" name="q" value="{{ $q }}" placeholder="Search business name..."
                    class="flex-1 min-w-[220px] border border-gray-300 p-2 rounded-3xl">
                <button class="bg-[#ec008c] text-white px-4 py-2 rounded-3xl hover:bg-[#be0070]">
                    Search
                </button>
            </form>

            <div class="overflow-x-auto rounded-3xl border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-[#2e3192]/10 text-[#2e3192]">
                        <tr>
                            <th class="p-3 border-b">ID</th>
                            <th class="p-3 border-b">Business Name</th>
                            <th class="p-3 border-b">Email</th>
                            <th class="p-3 border-b w-40">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($businesses as $business)
                            <tr class="border-t">
                                <td class="p-3 border-b">{{ $business->id }}</td>
                                <td class="p-3 border-b font-medium text-gray-800">{{ $business->business_name }}</td>
                                <td class="p-3 border-b">{{ $business->user?->email }}</td>
                                <td class="p-3 border-b">
                                    <a href="{{ route('admin.businesses.offers.index', $business) }}"
                                        class="bg-[#2e3192] text-white px-3 py-1 rounded-3xl hover:bg-[#25287a] text-sm">
                                        Offers
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-600">No businesses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $businesses->links() }}
            </div>
        </div>
    </div>

    <x-site-footer />
</x-app-layout>
