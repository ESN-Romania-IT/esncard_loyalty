<x-app-layout>
    <div class="max-w-7xl mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Businesses</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline text-sm">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <form method="GET" class="flex gap-3 mb-6">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search business name..."
                class="flex-1 border p-2 rounded">
            <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                Search
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 border">ID</th>
                        <th class="p-3 border">Business Name</th>
                        <th class="p-3 border">Email</th>
                        <th class="p-3 border w-40">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($businesses as $business)
                        <tr class="border-t">
                            <td class="p-3 border">{{ $business->id }}</td>
                            <td class="p-3 border">{{ $business->business_name }}</td>
                            <td class="p-3 border">{{ $business->user?->email }}</td>
                            <td class="p-3 border">
                                <a href="{{ route('admin.businesses.offers.index', $business) }}"
                                    class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
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
</x-app-layout>
