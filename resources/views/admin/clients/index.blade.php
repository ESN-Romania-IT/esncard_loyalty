<x-app-layout>
    <div class="max-w-7xl mx-auto mt-10 bg-white shadow-md p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Clients</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline text-sm">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <form method="GET" class="flex gap-3 mb-6">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search first/last name..."
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
                        <th class="p-3 border">Name</th>
                        <th class="p-3 border">Email</th>
                        <th class="p-3 border w-40">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr class="border-t">
                            <td class="p-3 border">{{ $client->id }}</td>
                            <td class="p-3 border">{{ $client->first_name }} {{ $client->last_name }}</td>
                            <td class="p-3 border">{{ $client->user?->email }}</td>
                            <td class="p-3 border">
                                <a href="{{ route('admin.clients.show', $client) }}"
                                    class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-600">No clients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $clients->links() }}
        </div>
    </div>
</x-app-layout>
